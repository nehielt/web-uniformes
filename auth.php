<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include 'conexion_db.php';

function schema_table_exists($mysqli, $table)
{
    $tableEsc = mysqli_real_escape_string($mysqli, $table);
    $sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = '$tableEsc' LIMIT 1";
    $result = mysqli_query($mysqli, $sql);

    return $result && mysqli_num_rows($result) > 0;
}

function schema_column_exists($mysqli, $table, $column)
{
    $tableEsc = mysqli_real_escape_string($mysqli, $table);
    $columnEsc = mysqli_real_escape_string($mysqli, $column);
    $sql = "SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '$tableEsc' AND column_name = '$columnEsc' LIMIT 1";
    $result = mysqli_query($mysqli, $sql);

    return $result && mysqli_num_rows($result) > 0;
}

function schema_get_column_metadata($mysqli, $table, $column)
{
    $tableEsc = mysqli_real_escape_string($mysqli, $table);
    $columnEsc = mysqli_real_escape_string($mysqli, $column);
    $sql = "SELECT COLUMN_TYPE, IS_NULLABLE
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name = '$tableEsc'
              AND column_name = '$columnEsc'
            LIMIT 1";
    $result = mysqli_query($mysqli, $sql);

    if (!$result) {
        return null;
    }

    $row = mysqli_fetch_assoc($result);

    return $row ?: null;
}

function schema_index_exists($mysqli, $table, $indexName)
{
    $tableEsc = mysqli_real_escape_string($mysqli, $table);
    $indexEsc = mysqli_real_escape_string($mysqli, $indexName);
    $sql = "SHOW INDEX FROM `$tableEsc` WHERE Key_name = '$indexEsc'";
    $result = mysqli_query($mysqli, $sql);

    return $result && mysqli_num_rows($result) > 0;
}

function schema_foreign_key_exists($mysqli, $table, $constraintName)
{
    $tableEsc = mysqli_real_escape_string($mysqli, $table);
    $constraintEsc = mysqli_real_escape_string($mysqli, $constraintName);
    $sql = "SELECT 1
            FROM information_schema.table_constraints
            WHERE table_schema = DATABASE()
              AND table_name = '$tableEsc'
              AND constraint_type = 'FOREIGN KEY'
              AND constraint_name = '$constraintEsc'
            LIMIT 1";
    $result = mysqli_query($mysqli, $sql);

    return $result && mysqli_num_rows($result) > 0;
}

function schema_add_index($mysqli, $table, $indexName, $definition, $unique = false)
{
    if (!schema_table_exists($mysqli, $table) || schema_index_exists($mysqli, $table, $indexName)) {
        return;
    }

    $uniqueSql = $unique ? 'UNIQUE ' : '';
    $sql = "ALTER TABLE `$table` ADD {$uniqueSql}INDEX `$indexName` ($definition)";
    mysqli_query($mysqli, $sql);
}

function schema_align_foreign_key_column($mysqli, $table, $column, $referencedTable, $referencedColumn)
{
    if (!schema_table_exists($mysqli, $table) || !schema_table_exists($mysqli, $referencedTable)) {
        return;
    }

    if (!schema_column_exists($mysqli, $table, $column) || !schema_column_exists($mysqli, $referencedTable, $referencedColumn)) {
        return;
    }

    $childColumn = schema_get_column_metadata($mysqli, $table, $column);
    $parentColumn = schema_get_column_metadata($mysqli, $referencedTable, $referencedColumn);

    if ($childColumn === null || $parentColumn === null) {
        return;
    }

    if ($childColumn['COLUMN_TYPE'] === $parentColumn['COLUMN_TYPE']) {
        return;
    }

    $nullability = $childColumn['IS_NULLABLE'] === 'YES' ? 'NULL' : 'NOT NULL';
    $sql = "ALTER TABLE `$table` MODIFY `$column` {$parentColumn['COLUMN_TYPE']} $nullability";
    mysqli_query($mysqli, $sql);
}

function schema_add_foreign_key($mysqli, $table, $constraintName, $column, $referencedTable, $referencedColumn, $onDelete = 'RESTRICT', $onUpdate = 'CASCADE')
{
    if (!schema_table_exists($mysqli, $table) || !schema_table_exists($mysqli, $referencedTable)) {
        return;
    }

    if (!schema_column_exists($mysqli, $table, $column) || !schema_column_exists($mysqli, $referencedTable, $referencedColumn)) {
        return;
    }

    if (schema_foreign_key_exists($mysqli, $table, $constraintName)) {
        return;
    }

    schema_align_foreign_key_column($mysqli, $table, $column, $referencedTable, $referencedColumn);

    $sql = "ALTER TABLE `$table`
            ADD CONSTRAINT `$constraintName`
            FOREIGN KEY (`$column`) REFERENCES `$referencedTable` (`$referencedColumn`)
            ON DELETE $onDelete
            ON UPDATE $onUpdate";
    mysqli_query($mysqli, $sql);
}

function schema_cleanup_orphans($mysqli)
{
    $cleanupQueries = [
        "DELETE e FROM empleados e LEFT JOIN cargos c ON e.cargo = c.id WHERE e.cargo IS NOT NULL AND c.id IS NULL",
        "DELETE e FROM empleados e LEFT JOIN ubicaciones u ON e.ubicacion = u.id WHERE e.ubicacion IS NOT NULL AND u.id IS NULL",
        "DELETE i FROM inventario i LEFT JOIN articulos a ON i.idarticulos = a.id WHERE i.idarticulos IS NOT NULL AND a.id IS NULL",
        "DELETE i FROM inventario i LEFT JOIN colores c ON i.idcolores = c.id WHERE i.idcolores IS NOT NULL AND c.id IS NULL",
        "DELETE i FROM inventario i LEFT JOIN tallas t ON i.idtallas = t.id WHERE i.idtallas IS NOT NULL AND t.id IS NULL",
        "DELETE i FROM inventario i LEFT JOIN generos g ON i.idgeneros = g.id WHERE i.idgeneros IS NOT NULL AND g.id IS NULL",
        "DELETE o FROM ordenes o LEFT JOIN empleados e ON o.empleado = e.id WHERE o.empleado IS NOT NULL AND e.id IS NULL",
        "DELETE d FROM detalles d LEFT JOIN ordenes o ON d.orden = o.id WHERE d.orden IS NOT NULL AND o.id IS NULL",
        "DELETE d FROM detalles d LEFT JOIN inventario i ON d.inventario = i.id WHERE d.inventario IS NOT NULL AND i.id IS NULL"
    ];

    foreach ($cleanupQueries as $sql) {
        mysqli_query($mysqli, $sql);
    }
}

function schema_integridad_init($mysqli)
{
    schema_cleanup_orphans($mysqli);

    $indexes = [
        ['empleados', 'idx_empleados_cargo', '`cargo`'],
        ['empleados', 'idx_empleados_ubicacion', '`ubicacion`'],
        ['empleados', 'idx_empleados_activo_expediente', '`activo`, `expediente`'],
        ['inventario', 'idx_inventario_articulo', '`idarticulos`'],
        ['inventario', 'idx_inventario_color', '`idcolores`'],
        ['inventario', 'idx_inventario_talla', '`idtallas`'],
        ['inventario', 'idx_inventario_genero', '`idgeneros`'],
        ['inventario', 'idx_inventario_catalogo', '`idarticulos`, `idcolores`, `idtallas`, `idgeneros`'],
        ['ordenes', 'idx_ordenes_empleado', '`empleado`'],
        ['ordenes', 'idx_ordenes_empleado_fecha', '`empleado`, `fecha`'],
        ['detalles', 'idx_detalles_orden', '`orden`'],
        ['detalles', 'idx_detalles_inventario', '`inventario`'],
        ['detalles', 'idx_detalles_orden_inventario', '`orden`, `inventario`'],
        ['auditoria', 'idx_auditoria_fecha', '`fecha`']
    ];

    foreach ($indexes as $index) {
        schema_add_index($mysqli, $index[0], $index[1], $index[2]);
    }

    $foreignKeys = [
        ['empleados', 'fk_empleados_cargo', 'cargo', 'cargos', 'id', 'RESTRICT'],
        ['empleados', 'fk_empleados_ubicacion', 'ubicacion', 'ubicaciones', 'id', 'RESTRICT'],
        ['inventario', 'fk_inventario_articulo', 'idarticulos', 'articulos', 'id', 'RESTRICT'],
        ['inventario', 'fk_inventario_color', 'idcolores', 'colores', 'id', 'RESTRICT'],
        ['inventario', 'fk_inventario_talla', 'idtallas', 'tallas', 'id', 'RESTRICT'],
        ['inventario', 'fk_inventario_genero', 'idgeneros', 'generos', 'id', 'RESTRICT'],
        ['ordenes', 'fk_ordenes_empleado', 'empleado', 'empleados', 'id', 'RESTRICT'],
        ['detalles', 'fk_detalles_orden', 'orden', 'ordenes', 'id', 'CASCADE'],
        ['detalles', 'fk_detalles_inventario', 'inventario', 'inventario', 'id', 'RESTRICT']
    ];

    foreach ($foreignKeys as $foreignKey) {
        schema_add_foreign_key(
            $mysqli,
            $foreignKey[0],
            $foreignKey[1],
            $foreignKey[2],
            $foreignKey[3],
            $foreignKey[4],
            $foreignKey[5]
        );
    }
}

function seguridad_init()
{
    global $mysqli;
    $qry = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(50) NOT NULL,
        nombre VARCHAR(100) DEFAULT NULL,
        activo TINYINT(1) NOT NULL DEFAULT 1,
        permiso_archivo TINYINT(1) NOT NULL DEFAULT 0,
        permiso_inventario TINYINT(1) NOT NULL DEFAULT 0,
        permiso_ordenes TINYINT(1) NOT NULL DEFAULT 0,
        permiso_reportes TINYINT(1) NOT NULL DEFAULT 0,
        permiso_seguridad TINYINT(1) NOT NULL DEFAULT 0,
        permiso_auditoria TINYINT(1) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    mysqli_query($mysqli, $qry);

    $requiredColumns = [
        'permiso_archivo' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'permiso_inventario' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'permiso_ordenes' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'permiso_reportes' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'permiso_seguridad' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'permiso_auditoria' => 'TINYINT(1) NOT NULL DEFAULT 0'
    ];

    foreach ($requiredColumns as $column => $definition) {
        $check = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'usuarios' AND column_name = '$column'";
        $result = mysqli_query($mysqli, $check);
        if ($result && mysqli_num_rows($result) === 0) {
            $alter = "ALTER TABLE usuarios ADD COLUMN $column $definition";
            mysqli_query($mysqli, $alter);
        }
    }

    $qry = "CREATE TABLE IF NOT EXISTS auditoria (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) DEFAULT NULL,
        tabla VARCHAR(100) NOT NULL,
        accion VARCHAR(20) NOT NULL,
        descripcion TEXT DEFAULT NULL,
        sql_query TEXT DEFAULT NULL,
        fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    mysqli_query($mysqli, $qry);

    schema_integridad_init($mysqli);

    $qry = "SELECT id FROM usuarios WHERE username = 'admin' LIMIT 1";
    $result = mysqli_query($mysqli, $qry);
    if ($result) {
        if (mysqli_num_rows($result) === 0) {
            $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
            $insert = "INSERT INTO usuarios (username, password, role, activo, permiso_archivo, permiso_inventario, permiso_ordenes, permiso_reportes, permiso_seguridad, permiso_auditoria) VALUES ('admin', '$passwordHash', 'Administrador', 1, 1, 1, 1, 1, 1, 1)";
            mysqli_query($mysqli, $insert);
            return true;
        } else {
            $row = mysqli_fetch_assoc($result);
            $update = "UPDATE usuarios SET activo = 1, permiso_archivo = 1, permiso_inventario = 1, permiso_ordenes = 1, permiso_reportes = 1, permiso_seguridad = 1, permiso_auditoria = 1 WHERE id = " . intval($row['id']);
            mysqli_query($mysqli, $update);
        }
    }
    return false;
}

// Ensure security tables exist when auth is included.
seguridad_init();

function require_login()
{
    if (empty($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }
}

function has_perm($perm)
{
    return !empty($_SESSION[$perm]);
}

function require_permission($perm)
{
    if (!has_perm($perm)) {
        header('Location: no_autorizado.php');
        exit;
    }
}

function registro_auditoria($tabla, $accion, $descripcion = '', $sql_query = '')
{
    global $mysqli;
    $usuario = !empty($_SESSION['usuario']) ? $_SESSION['usuario'] : 'system';
    $tablaEsc = mysqli_real_escape_string($mysqli, $tabla);
    $accionEsc = mysqli_real_escape_string($mysqli, $accion);
    $descEsc = mysqli_real_escape_string($mysqli, $descripcion);
    $sqlEsc = mysqli_real_escape_string($mysqli, $sql_query);
    $qry = "INSERT INTO auditoria (usuario, tabla, accion, descripcion, sql_query) VALUES ('$usuario', '$tablaEsc', '$accionEsc', '$descEsc', '$sqlEsc')";
    mysqli_query($mysqli, $qry);
}

function verificar_acceso_pagina()
{
    $pagina = basename($_SERVER['PHP_SELF']);
    $perm = null;

    if (preg_match('/^(articulos|cargos|colores|empleados|tallas|ubicaciones)/', $pagina)) {
        $perm = 'perm_archivo';
    } elseif (preg_match('/^(productos)/', $pagina)) {
        $perm = 'perm_inventario';
    } elseif (preg_match('/^(ordenes|ordenesseleccion)/', $pagina)) {
        $perm = 'perm_ordenes';
    } elseif (preg_match('/reporte|reportes/', $pagina)) {
        $perm = 'perm_reportes';
    } elseif ($pagina === 'seguridad.php') {
        $perm = 'perm_seguridad';
    } elseif ($pagina === 'auditoria.php') {
        $perm = 'perm_auditoria';
    }

    if ($perm !== null && !has_perm($perm)) {
        header('Location: no_autorizado.php');
        exit;
    }
}

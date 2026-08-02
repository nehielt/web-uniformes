<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include 'conexion_db.php';

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

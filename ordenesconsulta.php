<?php
include 'conexion_db.php';
include 'funciones.php';

$cedula = '';
$empleado = null;
$ordenes = [];
$detalles_por_orden = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');
    $cedula_buscar = mysqli_real_escape_string($mysqli, $cedula);

    $qry_empleado = "SELECT id, nombres, apellidos, cedula FROM empleados WHERE cedula = '$cedula_buscar' LIMIT 1";
    $res_empleado = mysqli_query($mysqli, $qry_empleado) or die('La consulta falló: ' . $mysqli->error);

    if ($row_empleado = mysqli_fetch_assoc($res_empleado)) {
        $empleado = $row_empleado;

        $qry_ordenes = "SELECT id, fecha, usuario_nombre FROM ordenes WHERE empleado = {$empleado['id']} ORDER BY id DESC";
        $res_ordenes = mysqli_query($mysqli, $qry_ordenes) or die('La consulta falló: ' . $mysqli->error);

        while ($row_orden = mysqli_fetch_assoc($res_ordenes)) {
            $ordenes[] = [
                'id' => $row_orden['id'],
                'fecha' => invierte_fecha($row_orden['fecha'], 0),
                'usuario_nombre' => trim($row_orden['usuario_nombre'])
            ];
        }

        if (!empty($ordenes)) {
            $orden_ids = array_column($ordenes, 'id');
            $orden_ids_sql = implode(',', array_map('intval', $orden_ids));

            $qry_detalles = "SELECT d.orden, d.cantidad, i.idarticulos, i.idcolores, i.idtallas, i.idgeneros " .
                "FROM detalles d JOIN inventario i ON d.inventario = i.id " .
                "WHERE d.orden IN ($orden_ids_sql) ORDER BY d.orden";
            $res_detalles = mysqli_query($mysqli, $qry_detalles) or die('La consulta falló: ' . $mysqli->error);

            while ($row_detalle = mysqli_fetch_assoc($res_detalles)) {
                $orden_id = (int)$row_detalle['orden'];
                $producto = trim(b_combo($mysqli, 'nombre', 'articulos', 'id', $row_detalle['idarticulos']));
                $color = trim(b_combo($mysqli, 'nombre', 'colores', 'id', $row_detalle['idcolores']));
                $genero = trim(b_combo($mysqli, 'nombre', 'generos', 'id', $row_detalle['idgeneros']));
                $talla = trim(b_combo($mysqli, 'nombre', 'tallas', 'id', $row_detalle['idtallas']));

                $detalle_text = $row_detalle['cantidad'] . ' x ' . $producto;
                if ($color !== '') {
                    $detalle_text .= ' - ' . $color;
                }
                if ($genero !== '') {
                    $detalle_text .= ' (' . $genero . ')';
                }
                if ($talla !== '') {
                    $detalle_text .= ' - Talla ' . $talla;
                }

                $detalles_por_orden[$orden_id][] = $detalle_text;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de órdenes</title>
    <link rel="stylesheet" href="formatos.css" type="text/css" charset="utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            max-width: 900px;
            margin: 20px 0 20px 10px;
            padding: 0;
        }

        .formulario {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .formulario input[type="text"] {
            padding: 8px;
            width: 250px;
            margin-right: 10px;
        }

        h1, h2 {
            color: #000000;
        }

        .logo {
            margin-bottom: 15px;
        }

        .logo img {
            max-width: 220px;
            height: auto;
            display: block;
        }

        .detalle-orden {
            margin: 0;
            padding-left: 18px;
        }

        .detalle-orden li {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="logo">
            <img src="logo_setecsa.png" alt="Logo SETECSA">
        </div>
        <h1>CONSULTA DE ORDENES DE ENTREGA</h1>
        <p>Ingrese su número de cédula para ver las órdenes de entrega emitidas a su nombre.</p>

        <form class="formulario" method="POST">
            <label for="cedula">Cédula:</label>
            <input type="text" id="cedula" name="cedula" value="<?php echo htmlspecialchars($cedula); ?>" required>
            <input type="submit" class="boton boton-verde" value="CONSULTAR">
        </form>

        <?php if ($cedula !== ''): ?>
            <?php if ($empleado): ?>
                <h2>Empleado: <?php echo htmlspecialchars($empleado['nombres'] . ' ' . $empleado['apellidos']); ?></h2>

                <?php if (!empty($ordenes)): ?>
                    <table class="styled-table-5">
                        <thead>
                            <tr>
                                <th>Nro. ORDEN</th>
                                <th>FECHA</th>
                                <th>EMITIDA POR</th>
                                <th>DETALLE</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenes as $orden): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($orden['id']); ?></td>
                                    <td><?php echo htmlspecialchars($orden['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars(!empty($orden['usuario_nombre']) ? $orden['usuario_nombre'] : '-'); ?></td>
                                    <td>
                                        <?php if (!empty($detalles_por_orden[$orden['id']])): ?>
                                            <ul class="detalle-orden">
                                                <?php foreach ($detalles_por_orden[$orden['id']] as $detalle): ?>
                                                    <li><?php echo htmlspecialchars($detalle); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <input type="button" class="boton boton-verde" value="VER" onclick="javascript:{lanzar('nota.php?id=<?php echo $orden['id']; ?>')}">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No se han emitido órdenes de entrega para este empleado.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>No se encontró ningún empleado con esa cédula.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="funciones_javascript.js"></script>
</body>
</html>

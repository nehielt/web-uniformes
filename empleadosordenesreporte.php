<?php
include 'encabezado.php';
include 'conexion_db.php';
include 'funciones.php';

$hoy = date('Y-m-d');
$fecha6 = date('Y-m-d', strtotime('-6 months', strtotime($hoy)));
$fecha12 = date('Y-m-d', strtotime('-12 months', strtotime($hoy)));
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

$qry = "
    SELECT * FROM (
        SELECT e.id, e.cedula, e.nombres, e.apellidos, MAX(o.fecha) AS ultima_fecha
        FROM empleados e
        INNER JOIN ordenes o ON o.empleado = e.id
        WHERE e.activo = 1
        GROUP BY e.id, e.cedula, e.nombres, e.apellidos
    ) AS t
    ORDER BY ultima_fecha ASC, apellidos, nombres
";

$result = mysqli_query($mysqli, $qry) or die('La consulta falló: ' . $mysqli->error);

$registros = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ultima_fecha = $row['ultima_fecha'];
    $estado = 'Al día';
    if ($ultima_fecha <= $fecha6) {
        $estado = 'Más de 6 meses';
    }
    if ($ultima_fecha <= $fecha12) {
        $estado = 'Más de 12 meses';
    }

    $registros[] = [
        'id' => $row['id'],
        'cedula' => $row['cedula'],
        'nombres' => $row['nombres'],
        'apellidos' => $row['apellidos'],
        'ultima_fecha' => !empty($ultima_fecha) ? invierte_fecha($ultima_fecha, 0) : '-',
        'estado' => $estado
    ];
}

if ($filtro === 'al-dia') {
    $registros = array_values(array_filter($registros, function($r) {
        return $r['estado'] === 'Al día';
    }));
} elseif ($filtro === '6-meses') {
    $registros = array_values(array_filter($registros, function($r) {
        return $r['estado'] === 'Más de 6 meses';
    }));
} elseif ($filtro === '12-meses') {
    $registros = array_values(array_filter($registros, function($r) {
        return $r['estado'] === 'Más de 12 meses';
    }));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de órdenes de entrega</title>
    <link rel="stylesheet" href="formatos.css" type="text/css" charset="utf-8" />
    <style>
        body { font-family: Arial, sans-serif; background: #f2f2f2; }
        .contenedor { max-width: 1100px; margin: 20px auto 20px auto; padding: 0 10px; }
        h1 { color: #000000; text-align: center; }
        .info { margin-bottom: 15px; color: #444; text-align: center; }
        .styled-table-5 {
            margin: 0 auto;
            width: auto;
            min-width: 0;
            max-width: 100%;
            table-layout: fixed;
        }

        .styled-table-5 th,
        .styled-table-5 td {
            white-space: nowrap;
            padding: 10px 12px;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>REPORTE DE EMPLEADOS SEGÚN ORDENES</h1>
        <div class="info">
            <p>Se muestran los empleados activos con la fecha de su última orden de entrega y la clasificación según si supera los 6 o 12 meses.</p>
        </div>

        <form method="get" action="empleadosordenesreporte.php" style="text-align:center; margin-bottom:15px;">
            <label for="filtro" style="font-weight:bold; margin-right:8px;">Filtrar:</label>
            <select name="filtro" id="filtro" style="padding:6px 8px; margin-right:8px;">
                <option value="todos" <?php echo $filtro === 'todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="al-dia" <?php echo $filtro === 'al-dia' ? 'selected' : ''; ?>>Al día</option>
                <option value="6-meses" <?php echo $filtro === '6-meses' ? 'selected' : ''; ?>>Anteriores a 6 meses</option>
                <option value="12-meses" <?php echo $filtro === '12-meses' ? 'selected' : ''; ?>>Anteriores a 12 meses</option>
            </select>
            <button type="submit" class="boton">APLICAR</button>
            <a href="reporte_empleados_pdf.php?filtro=<?php echo urlencode($filtro); ?>" class="boton" target="_blank">EXPORTAR PDF</a>
        </form>

        <table class="styled-table-5">
            <thead>
                <tr>
                    <th>CÉDULA</th>
                    <th>NOMBRES</th>
                    <th>APELLIDOS</th>
                    <th>ORDENES</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['cedula']); ?></td>
                        <td><?php echo htmlspecialchars($r['nombres']); ?></td>
                        <td><?php echo htmlspecialchars($r['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($r['ultima_fecha']); ?></td>
                        <td><?php echo htmlspecialchars($r['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

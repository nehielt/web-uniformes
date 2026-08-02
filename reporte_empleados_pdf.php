<?php
require 'fpdf185/fpdf.php';
include 'conexion_db.php';
include 'funciones.php';

$hoy = date('Y-m-d');
$fecha6 = date('Y-m-d', strtotime('-6 months', strtotime($hoy)));
$fecha12 = date('Y-m-d', strtotime('-12 months', strtotime($hoy)));
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

$qry = "
    SELECT e.cedula, e.nombres, e.apellidos, MAX(o.fecha) AS ultima_fecha
    FROM empleados e
    INNER JOIN ordenes o ON o.empleado = e.id
    WHERE e.activo = 1
    GROUP BY e.id, e.cedula, e.nombres, e.apellidos
    ORDER BY ultima_fecha ASC, e.apellidos, e.nombres
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

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'REPORTE DE EMPLEADOS SEGUN ORDENES', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, 'Generado el ' . date('d/m/Y'), 0, 1, 'R');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(25, 7, 'CEDULA', 1, 0, 'C');
$pdf->Cell(40, 7, 'NOMBRES', 1, 0, 'C');
$pdf->Cell(40, 7, 'APELLIDOS', 1, 0, 'C');
$pdf->Cell(35, 7, 'ORDENES', 1, 0, 'C');
$pdf->Cell(35, 7, 'ESTADO', 1, 1, 'C');
$pdf->SetFont('Arial', '', 8);

foreach ($registros as $r) {
    $pdf->Cell(25, 6, $r['cedula'], 1, 0, 'C');
    $pdf->Cell(40, 6, utf8_decode($r['nombres']), 1, 0, 'C');
    $pdf->Cell(40, 6, utf8_decode($r['apellidos']), 1, 0, 'C');
    $pdf->Cell(35, 6, $r['ultima_fecha'], 1, 0, 'C');
    $pdf->Cell(35, 6, utf8_decode($r['estado']), 1, 1, 'C');
}

$pdf->Output('reporte_empleados.pdf', 'I');

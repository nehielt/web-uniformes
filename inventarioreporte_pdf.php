<?php
include('conexion_db.php');
include('funciones.php');
require('fpdf185/fpdf.php');

$qry = "SELECT * FROM inventario ORDER BY idarticulos";
$result = mysqli_query($mysqli, $qry) or die('La consulta falló: ' . $mysqli->error);

$hoy = date('d/m/Y');
$registros = [];
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $idarticulos = $row['idarticulos'];
    $producto = b_combo($mysqli, 'nombre', 'articulos', 'id', $idarticulos);
    $idcolores = $row['idcolores'];
    $producto = $producto . ' ' . b_combo($mysqli, 'nombre', 'colores', 'id', $idcolores);
    $idtallas = $row['idtallas'];
    $tallas = b_combo($mysqli, 'nombre', 'tallas', 'id', $idtallas);
    $idgeneros = $row['idgeneros'];
    $producto = $producto . ' PARA ' . b_combo($mysqli, 'nombre', 'generos', 'id', $idgeneros);
    $existencia = $row['existencia'];
    $minimo = $row['minimo'];
    $porDebajo = ($existencia < $minimo);

    $registros[] = [
        'producto' => $producto,
        'talla' => $tallas,
        'existencia' => $existencia,
        'minimo' => $minimo,
        'porDebajo' => $porDebajo,
    ];
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, 'REPORTE DE INVENTARIO', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 6, 'Generado el ' . date('d/m/Y'), 0, 1, 'R');
$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(100, 7, 'ARTICULO', 1, 0, 'C');
$pdf->Cell(30, 7, 'TALLA', 1, 0, 'C');
$pdf->Cell(30, 7, 'CANTIDAD', 1, 0, 'C');
$pdf->Cell(30, 7, 'ESTADO', 1, 1, 'C');
$pdf->SetFont('Arial', '', 8);

foreach ($registros as $r) {
    if ($r['porDebajo']) {
        $pdf->SetFillColor(255, 243, 205);
        $pdf->SetTextColor(133, 100, 4);
    } else {
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0);
    }

    $pdf->Cell(100, 6, utf8_decode($r['producto']), 1, 0, 'L', true);
    $pdf->Cell(30, 6, utf8_decode($r['talla']), 1, 0, 'C', true);
    $pdf->Cell(30, 6, utf8_decode($r['existencia']), 1, 0, 'C', true);
    $pdf->Cell(30, 6, utf8_decode($r['porDebajo'] ? 'BAJO MÍNIMO' : 'OK'), 1, 1, 'C', true);
}
$pdf->SetTextColor(0);

$nombre_archivo = 'INFORME DE INVENTARIO.pdf';
$pdf->Output('D', $nombre_archivo);

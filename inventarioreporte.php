<?php
    include('encabezado.php');
    include('conexion_db.php');
    include('funciones.php');

    $qry = "SELECT * FROM inventario ORDER BY idarticulos";
    $result = mysqli_query($mysqli, $qry) or die('La consulta fall�: ' . $mysqli->error);

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de inventario</title>
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
        .styled-table-5 td:nth-child(2),
        .styled-table-5 td:nth-child(3),
        .styled-table-5 td:nth-child(4),
        .styled-table-5 th:nth-child(2),
        .styled-table-5 th:nth-child(3),
        .styled-table-5 th:nth-child(4) {
            text-align: center;
        }
        .alerta {
            background-color: #fff3cd;
            color: #856404;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>REPORTE DE INVENTARIO</h1>
        <div class="info">
            <p>Se muestran los productos del inventario con su talla y existencia actual.</p>
        </div>

        <div style="text-align:center; margin-bottom:15px;">
            <a href="inventarioreporte_pdf.php" class="boton" target="_blank">EXPORTAR PDF</a>
        </div>

        <table class="styled-table-5">
            <thead>
                <tr>
                    <th>ARTÍCULO</th>
                    <th>TALLA</th>
                    <th>CANTIDAD</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $r): ?>
                    <tr class="<?php echo $r['porDebajo'] ? 'alerta' : ''; ?>">
                        <td><?php echo htmlspecialchars($r['producto']); ?></td>
                        <td><?php echo htmlspecialchars($r['talla']); ?></td>
                        <td><?php echo htmlspecialchars($r['existencia']); ?></td>
                        <td><?php echo $r['porDebajo'] ? 'BAJO MÍNIMO' : 'OK'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
include ('conexion_db.php');
include ('funciones.php');

$numero = 0;
$fecha = date("d/m/Y");
$nombre = "";
$apellido = "";
$cedula = "";
$usuario_nombre = "";
$rows_detalle = array();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $qry = "SELECT * FROM ordenes WHERE id = $id";
    $result = mysqli_query($mysqli, $qry) or die('La consulta falló: '.$mysqli->error);
    if ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $numero = $row['id'];
        $fecha = invierte_fecha($row['fecha'], 0);
        $idempleado = $row['empleado'];
        $nombre = trim(b_combo($mysqli, 'nombres', 'empleados', 'id', $idempleado));
        $apellido = trim(b_combo($mysqli, 'apellidos', 'empleados', 'id', $idempleado));
        $cedula = b_combo($mysqli, 'cedula', 'empleados', 'id', $idempleado);
        $usuario_nombre = isset($row['usuario_nombre']) ? trim($row['usuario_nombre']) : '';

        $qry2 = "SELECT d.cantidad, i.idarticulos, i.idcolores, i.idtallas, i.idgeneros, i.precio " .
                "FROM detalles d JOIN inventario i ON d.inventario = i.id " .
                "WHERE d.orden = $id";
        $result2 = mysqli_query($mysqli, $qry2) or die('La consulta falló: '.$mysqli->error);
        while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
            $producto = b_combo($mysqli, 'nombre', 'articulos', 'id', $row2['idarticulos']);
            $producto .= ' ' . b_combo($mysqli, 'nombre', 'colores', 'id', $row2['idcolores']);
            $producto .= ' PARA ' . b_combo($mysqli, 'nombre', 'generos', 'id', $row2['idgeneros']);
            $talla = b_combo($mysqli, 'nombre', 'tallas', 'id', $row2['idtallas']);
            $precio = number_format($row2['precio'], 2, ',', '.');
            $rows_detalle[] = array(
                'cantidad' => $row2['cantidad'],
                'articulo' => $producto,
                'talla' => $talla,
                'precio' => $precio
            );
        }
    }
}
if (empty($rows_detalle)) {
    $rows_detalle[] = array(
        'cantidad' => 0,
        'articulo' => 'Sin artículos asignados',
        'talla' => '-',
        'precio' => '0,00'
    );
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nota de Entrega</title>

<style>

body{
    font-family: Arial, sans-serif;
    background:#f2f2f2;
}

.contenedor{
    width: 700px;
    margin:auto;
    background:white;
    padding:40px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.logo{
    font-size:60px;
    font-weight:bold;
    color:black;
}

.logo img {
    width: 50%;
    height: auto;
    display: block;
}

.logo span{
    color:red;
}

.info{
    text-align:left;
    font-size:20px;
}

h1{
    text-align:center;
    margin-top:60px;
    margin-bottom:50px;
}

p{
    font-size:14px;
}

.datos{
    margin-top:20px;
    line-height:35px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
    margin-bottom:30px;
}

table, th, td{
    border:1px solid gray;
}

th, td{
    padding:8px;
    text-align:left;
}

.firmas{
    margin-top:40px;
    line-height:40px;
}

.linea{
    display:inline-block;
    border-bottom:1px solid black;
    width:250px;
}

</style>
</head>

<body>

<div class="contenedor">

    <div class="header">

        <div class="logo">
            <img src="logo_setecsa.png" alt="SETESCA" />
        </div>

        <div class="info">
            <p><b>Número:</b> <?php echo $numero; ?></p>
            <p><b>Fecha:</b> <?php echo $fecha; ?></p>
        </div>

    </div>

    <h1 style="font-size: 18px; text-align: center;">NOTA DE ENTREGA</h1>

    <p>Por medio de la presente hago constar que yo:</p>

    <div class="datos">
        <div><b>Nombre:</b> <?php echo $nombre; ?></div>
        <div><b>Apellido:</b> <?php echo $apellido; ?></div>
        <div><b>Cédula:</b> <?php echo $cedula; ?></div>
    </div>

    <p style="margin-top:30px;">He recibido:</p>

    <table>
        <tr>
            <th>Cantidad</th>
            <th>Artículo</th>
            <th>Talla</th>
            <th>Precio estimado</th>
        </tr>

        <?php foreach ($rows_detalle as $detalle): ?>
        <tr>
            <td><?php echo htmlspecialchars($detalle['cantidad']); ?></td>
            <td><?php echo htmlspecialchars($detalle['articulo']); ?></td>
            <td><?php echo htmlspecialchars($detalle['talla']); ?></td>
            <td>$<?php echo htmlspecialchars($detalle['precio']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p>
        Como dotación de uniforme, el cual me comprometo a entregar
        a la culminación de mi relación laboral con la empresa.
    </p>

    <p>
        Cabe destacar que el USO DE LOS IMPLEMENTOS DE SEGURIDAD
        SON DE CARÁCTER OBLIGATORIOS.
    </p>

    <div class="firmas">

        <p>Entregado por</p>
        <p><?php echo htmlspecialchars($usuario_nombre); ?></p>

        <br>

        <p>Recibido por:</p>

        <p>
            Nombre y Apellido: <span class="linea"></span><br>
            Firma: <span class="linea"></span><br>
            C.I: <span class="linea"></span><br>
            Cargo: <span class="linea"></span><br>
            Fecha: <span class="linea"></span>
        </p>

    </div>

</div>

</body>
</html>
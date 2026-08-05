<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    $idorden = intval($_GET['id'] ?? 0);
    $qryOrden = "select * from ordenes where id=$idorden";
    $resultOrden = mysqli_query($mysqli,$qryOrden) or die('La consulta falló: '.$mysqli -> error);
    $rowOrden = mysqli_fetch_array($resultOrden, MYSQLI_ASSOC);

    $idempleados = trim($rowOrden['empleado']);
    $fecha = invierte_fecha($rowOrden['fecha'],0);
    $nombres = trim(b_combo($mysqli,'nombres','empleados','id',$idempleados));
    $apellidos = trim(b_combo($mysqli,'apellidos','empleados','id',$idempleados));
    $expediente = b_combo($mysqli,'expediente','empleados','id',$idempleados);
    $empleado = $nombres.' '.$apellidos;

    $cantidadesActuales = array();
    $qryDetalles = "select * from detalles where orden=$idorden";
    $resultDetalles = mysqli_query($mysqli,$qryDetalles) or die('La consulta falló: '.$mysqli -> error);
    while($rowDetalle = mysqli_fetch_array($resultDetalles, MYSQLI_ASSOC))
    {
        $cantidadesActuales[$rowDetalle['inventario']] = $rowDetalle['cantidad'];
    }

    $nombreUsuario = '';
    if (!empty($_SESSION['nombre_completo'])) {
        $nombreUsuario = trim($_SESSION['nombre_completo']);
    } elseif (!empty($_SESSION['usuario'])) {
        $usuarioLogueado = mysqli_real_escape_string($mysqli, $_SESSION['usuario']);
        $qryNombre = "SELECT nombre FROM usuarios WHERE username = '$usuarioLogueado' LIMIT 1";
        $resNombre = mysqli_query($mysqli, $qryNombre);
        if ($resNombre && $rowNombre = mysqli_fetch_assoc($resNombre)) {
            $nombreUsuario = trim($rowNombre['nombre']);
        }
        if ($nombreUsuario === '') {
            $nombreUsuario = $_SESSION['usuario'];
        }
    }

    $qry = "select * from inventario where existencia > 0 or id in (select inventario from detalles where orden = $idorden) order by idarticulos";
    $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);

    $css1= "sr_grid1";
    $color[1]=$css1;
    $ncolor=0;
    $i=1;
?>

<html lang="en">
    
    <form action="metodos.php?metodo=ordenesmodificar&id=<?php echo $idorden?>" method="POST" >
        <input type="hidden" name="nombre_usuario" value="<?php echo htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8'); ?>">
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th colspan="2">DATOS DE LA ORDEN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ORDEN:</td>
                    <td><input type="text" size="8" maxlength="8" value="<?php echo $idorden?>" disabled></td>
                </tr>
                <tr>
                    <td>FECHA:</td>
                    <td><input type="text" size="10" maxlength="10" value="<?php echo $fecha?>" disabled></td>
                </tr>
                <tr>
                    <td>EXPEDIENTE:</td>
                    <td><input type="text" id="expediente" name="expediente" size="8" maxlength="8" value="<?php echo $expediente?>" disabled></td>
                </tr>
                <tr>
                    <td>EMPLEADO:</td>
                    <td><input type="text" id="empleado" name="empleado" size="40" maxlength="40" value="<?php echo $empleado?>" disabled></td>
                </tr>
            </tbody>
        </table>
        <div style="margin: 20px 400px 10px;">
            <label for="buscarArticulo" style="font-weight:bold;">Buscar artículo:</label>
            <input type="text" id="buscarArticulo" onkeyup="filtrarArticulos()" size="40" placeholder="Buscar artículo, color, talla o género">
        </div>
        <table class="tablabotones">
            <tr>
                <td><input type="submit" name="guardar" id="guardar" value="GUARDAR" class = "boton" title="GUARDAR"/></td>
                <td><input type="button" name="cancelar" id="cancelar" class = "boton" value="CANCELAR" title="CANCELAR" onclick="javascript:{lanzar('ordenesvista.php')}"></td>
            </tr>
        </table>
        <table class="styled-table-5">
            <thead>
                <tr>
                    <th>ARTICULO</th>
                    <th>CANTIDAD</th>
                </tr>
            </thead>
            <tbody>
                    <?php 
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                        {
                            $id = $row['id'];
                            $idarticulos = $row['idarticulos'];
                            $producto = b_combo($mysqli,'nombre','articulos','id',$idarticulos);
                            $idcolores = $row['idcolores'];
                            $producto = $producto.' '.b_combo($mysqli,'nombre','colores','id',$idcolores);
                            $idtallas = $row['idtallas'];
                            $tallas = b_combo($mysqli,'nombre','tallas','id',$idtallas);
                            $idgeneros = $row['idgeneros'];
                            $producto = $producto.' PARA '.b_combo($mysqli,'nombre','generos','id',$idgeneros);
                            $producto = $producto.' TALLA '.$tallas;
                            $cantidadActual = isset($cantidadesActuales[$id]) ? $cantidadesActuales[$id] : '';
                    ?>	
                            <tr class="<?php echo $color[$ncolor]?>">
                                <td><?php echo nl2br(trim($producto))?></td>
                                <td><input type="text" id="cantidad[<?php echo $id?>]" name="cantidad[<?php echo $id?>]" size="5" maxlength="5" value="<?php echo $cantidadActual?>"></td>
                            </tr> 
                    <?php
                            $i++;
                            $ncolor = $ncolor + 1;
                            if ($ncolor > 1 ) { $ncolor=0;}
                        }
                    ?>
                </tbody>
        </table>

    </form>

    <script>
        function filtrarArticulos() {
            var filtro = document.getElementById('buscarArticulo').value.toLowerCase();
            var filas = document.querySelectorAll('.styled-table-5 tbody tr');
            filas.forEach(function(fila) {
                var texto = fila.textContent.toLowerCase();
                fila.style.display = texto.indexOf(filtro) > -1 ? '' : 'none';
            });
        }
    </script>
</html>
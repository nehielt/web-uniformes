<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');

    $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
    if ($busqueda !== '') {
        $busqueda_esc = mysqli_real_escape_string($mysqli, $busqueda);
        $qry = "select * from empleados where activo = 1 and (expediente like '%{$busqueda_esc}%' or nombres like '%{$busqueda_esc}%' or apellidos like '%{$busqueda_esc}%') order by expediente";
    } else {
        $qry = "select * from empleados where activo = 1 order by expediente";
    }
    // SQL DE LA BUSQUEDA DE LOS DATOS
    //echo $qry."<br>";
    $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);

    // DEFINICION DEL FONDO DE LOS TR DE LA TABLA EN LOS DATOS
    $css1= "sr_grid1";
    $color[1]=$css1;
    $ncolor=0;
    $i=1;
?>
<html lang="en">
    <br><br><br><br>
    <table class="tablabotones">
        <tr>
            <td>
                <form method="GET" action="ordenesseleccionempleado.php" style="display: flex; align-items: center; gap: 10px; margin: 0;">
                    <input type="text" name="busqueda" size="30" maxlength="50" value="<?php echo htmlspecialchars($busqueda); ?>" placeholder="Buscar expediente, nombre o apellido">
                    <input type="submit" class="boton boton-verde" value="BUSCAR" title="BUSCAR">
                    <?php if ($busqueda !== '') { ?>
                        <input type="button" class="boton" value="LIMPIAR" title="LIMPIAR" onclick="window.location='ordenesseleccionempleado.php'">
                    <?php } ?>
                </form>
            </td>
            <td><input type="button"  class="boton" value="CANCELAR" title="CANCELAR" onclick="javascript:{lanzar('ordenesvista.php')}"></td>
         
        </tr>
    </table>
    
    <table class="styled-table-5">
        <thead>
            <tr>
                <th>EXPEDIENTE</th>
                <th>NOMBRES</th>
                <th>APELLIDOS</th>
                <th>SELECIONAR</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    {
                        //echo "<pre>";print_r($row);echo "</pre>";
                        $id = $row['id'];
                        $expediente = $row['expediente'];
                        $nombres = $row['nombres'];
                        $apellidos = $row['apellidos'];
                   
                ?>	
                        <tr class="<?php echo $color[$ncolor]?>">
                            <td><?php echo nl2br(trim($expediente))?></td>
                            <td><?php echo nl2br(trim($nombres))?></td>
                            <td><?php echo nl2br(trim($apellidos))?></td>
                            <td>
                                <input type="button"  class="boton" title="ARTICULOS" value="ARTICULOS" onclick="javascript:{lanzar('ordenesseleccionarticulos.php?idempleados=<?php echo $id?>')}" />
                            </td>
                        </tr> 
                <?php
                        $i++;
                        $ncolor = $ncolor + 1;
                        if ($ncolor > 1 ) { $ncolor=0;}
                    }
                ?>
            </tbody>
    </table>
</html>
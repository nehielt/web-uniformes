<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');

    // SQL DE LA BUSQUEDA DE LOS DATOS
    $qry = "select * from empleados where activo = 0 order by expediente";
    //echo $qry."<br>";
    $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);

    // DEFINICION DEL FONDO DE LOS TR DE LA TABLA EN LOS DATOS
    $css1= "sr_grid1";
    $color[1]=$css1;
    $ncolor=0;
?>
<html lang="en">
    <br><br><br><br>
   
    <table class="styled-table-5">
        <thead>
            <tr>
                <th>EXPEDIENTE</th>
                <th>NOMBRES</th>
                <th>APELLIDOS</th>
                <th>ACCION</th>
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
                                <input type="button"  class="boton boton-verde" title="ACTIVAR EMPLEADO" value="ACTIVAR" onclick="javascript:{lanzar('empleadosactivarretirar.php?id=<?php echo $id?>&status=ACTIVAR')}" />
                            </td>
                        </tr> 
                <?php
                        $ncolor = $ncolor + 1;
                        if ($ncolor > 1 ) { $ncolor=0;}
                    }
                ?>
            </tbody>
    </table>
</html>
<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    // SQL DE LA BUSQUEDA DE LOS DATOS
    $qry = "select * from ordenes order by id desc";
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
            <td><input type="button"  class="boton boton-azul" value="CREAR" title="CREAR ORDEN" onclick="javascript:{lanzar('ordenesseleccionempleado.php')}"></td>
         
        </tr>
    </table>
    
    <table class="styled-table-5">
        <thead>
            <tr>
                <th>Nro. ORDEN</th>
                <th>EN FECHA</th>
                <th>PARA</th>
                <th>ACCIONES</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    {
                        //echo "<pre>";print_r($row);echo "</pre>";
                        $id = $row['id'];
                        $fecha = invierte_fecha($row['fecha'],0);
                        $idempleados = $row['empleado'];
                        $nombres = trim(b_combo($mysqli,'nombres','empleados','id',$idempleados));
                        $apellidos = trim(b_combo($mysqli,'apellidos','empleados','id',$idempleados));
                        $expediente = b_combo($mysqli,'expediente','empleados','id',$idempleados);
                        $empleado = $nombres.' '.$apellidos;
               
                ?>	
                        <tr class="<?php echo $color[$ncolor]?>">
                            <td><?php echo nl2br(trim($id))?></td>
                            <td><?php echo nl2br(trim($fecha))?></td>
                            <td><?php echo nl2br(trim($empleado))?></td>
                            <td>
                                <input type="button"  class="boton boton-verde" title="VER" value="VER" onclick="javascript:{lanzar('nota.php?id=<?php echo $id?>')}" />
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
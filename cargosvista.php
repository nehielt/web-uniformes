<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');

    // SQL DE LA BUSQUEDA DE LOS DATOS
    $qry = "select * from cargos order by nombre";
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
            <td><input type="button"  class="boton" value="AGREGAR" title="AGREGAR CARGO" onclick="javascript:{lanzar('cargosagregar.php')}"></td>
         
        </tr>
    </table>
    
    <table class="styled-table-3">
        <thead>
            <tr>
                <th>CARGOS</th>
                <th>ACCION</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    {
                        //echo "<pre>";print_r($row);echo "</pre>";
                        $id = $row['id'];
                        $nombre = $row['nombre'];
                   
                ?>	
                        <tr class="<?php echo $color[$ncolor]?>">
                            <td><?php echo nl2br(trim($nombre))?></td>
                            <td><input type="button"  class="boton boton-amarillo" title="MODIFICAR CARGO" value="MODIFICAR" onclick="javascript:{lanzar('cargosmodificar.php?id=<?php echo $id?>')}" /></td>
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
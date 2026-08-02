<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    // SQL DE LA BUSQUEDA DE LOS DATOS
    $qry = "select * from inventario order by idarticulos";
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
            <td><input type="button"  class="boton" value="AGREGAR" title="AGREGAR PRODUCTO" onclick="javascript:{lanzar('productosagregar.php')}"></td>
         
        </tr>
    </table>
    
    <table class="styled-table-5">
        <thead>
            <tr>
                <th>PRODUCTOS</th>
                <th>TALLAS</th>
                <th>UNIDADES EXISTENTES</th>
                <th>PRECIO C/U</th>
                <th>ACTUALIZAR</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                    {
                        //echo "<pre>";print_r($row);echo "</pre>";
                        $id = $row['id'];
                        $idarticulos = $row['idarticulos'];
                        $producto = b_combo($mysqli,'nombre','articulos','id',$idarticulos);
                        $idcolores = $row['idcolores'];
                        $producto = $producto.' '.b_combo($mysqli,'nombre','colores','id',$idcolores);
                        $idtallas = $row['idtallas'];
                        $tallas = b_combo($mysqli,'nombre','tallas','id',$idtallas);
                        $idgeneros = $row['idgeneros'];
                        $producto = $producto.' PARA '.b_combo($mysqli,'nombre','generos','id',$idgeneros);
                        $minimo = $row['minimo'];
                        $existencia = $row['existencia'];
                        $precio = $row['precio'];
                   
                ?>	
                        <tr class="<?php echo $color[$ncolor]?>">
                            <td><?php echo nl2br(trim($producto))?></td>
                            <td><?php echo nl2br(trim($tallas))?></td>
                            <td><?php echo nl2br(trim($existencia))?></td>
                            <td><?php echo nl2br(trim(number_format($precio,2,',','.')))?></td>
                            <td>
                                <input type="button"  class="boton boton-amarillo" title="ACTUALIZAR PRECIO" value="PRECIO" onclick="javascript:{lanzar('productosprecios.php?id=<?php echo $id?>&nombre=<?php echo $producto?> TALLA <?php echo $tallas?>')}" />
                                <input type="button"  class="boton boton-amarillo" title="ACTUALIZAR UNIDADES" value="UNIDADES" onclick="javascript:{lanzar('productosunidades.php?id=<?php echo $id?>&nombre=<?php echo $producto?> TALLA <?php echo $tallas?>')}" />
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
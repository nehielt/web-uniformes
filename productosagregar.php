<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    $qry = "select * from articulos order by nombre";
    $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);

    $qry2 = "select * from colores order by nombre";
    $result2 = mysqli_query($mysqli,$qry2) or die('La consulta falló: '.$mysqli -> error);

    $qry3 = "select * from tallas order by nombre";
    $result3 = mysqli_query($mysqli,$qry3) or die('La consulta falló: '.$mysqli -> error);

    $qry4 = "select * from generos order by nombre";
    $result4 = mysqli_query($mysqli,$qry4) or die('La consulta falló: '.$mysqli -> error);
?>

<script>
    function valida_campos()
    {
        //Valida los campor para que no esten en blanco.
        //alert("Entra");
        var band_trabaj=true;
        mens = "DEBE INDICAR UN VALOR PARA LOS SIGUIENTES CAMPOS: \n"
        
        if(1>(document.getElementById("minimos").value).length)
        {
            mens = mens+"- CANTIDAD MINIMA \n"
            band_trabaj=false; 
        }
                if(1>(document.getElementById("existencias").value).length)
        {
            mens = mens+"- CANTIDAD EXISTENTE \n"
            band_trabaj=false; 
        }
        if(band_trabaj)
        {
            return (true);
        }else{
            alert(mens);
            return (false);
        }
    }
</script>

<html lang="en">
    
    <form action="metodos.php?metodo=productosagregar" method="POST" onsubmit="return valida_campos()">
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th colspan="2">DATOS DEL PRODUCTO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ARTICULO:</td>
                    <td>
                        <select id="seleccionarticulos" name="seleccionarticulos">
					        <!--option value="0" selected>Seleccionar</option-->
					<?php 
                            while($lin = mysqli_fetch_array($result, MYSQLI_ASSOC))
                            {
                                echo '<option value="'.$lin['id'].'">'.$lin['nombre'].'</option>';
                            }
                    ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>COLOR:</td>
                    <td>
                        <select id="seleccioncolores" name="seleccioncolores">
					        <!--option value="0" selected>Seleccionar</option-->
					<?php 
                            while($lin2 = mysqli_fetch_array($result2, MYSQLI_ASSOC))
                            {
                                echo '<option value="'.$lin2['id'].'">'.$lin2['nombre'].'</option>';
                            }
                    ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>TALLA:</td>
                    <td>
                        <select id="selecciontallas" name="selecciontallas">
					        <!--option value="0" selected>Seleccionar</option-->
					<?php 
                            while($lin3 = mysqli_fetch_array($result3, MYSQLI_ASSOC))
                            {
                                echo '<option value="'.$lin3['id'].'">'.$lin3['nombre'].'</option>';
                            }
                    ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>GENERO:</td>
                    <td>
                        <select id="selecciongeneros" name="selecciongeneros">
					        <!--option value="0" selected>Seleccionar</option-->
					<?php 
                            while($lin4 = mysqli_fetch_array($result4, MYSQLI_ASSOC))
                            {
                                echo '<option value="'.$lin4['id'].'">'.$lin4['nombre'].'</option>';
                            }
                    ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>CANTIDAD MINIMA:</td>
                    <td><input type="text" id="minimos" name="minimos" size="2" maxlength="3" onKeyPress='return sincoma(event)' ></td>
                </tr>
                <tr>
                    <td>EXISTENCIA:</td>
                    <td><input type="text" id="existencias" name="existencias" size="4" maxlength="5" onKeyPress='return sincoma(event)' ></td>
                </tr>
                <tr>
                    <td>PRECIO:</td>
                    <td><input type="text" id="precios" name="precios" size="10" maxlength="10" onKeyPress='return solonumero(event)' ></td>
                </tr>
            </tbody>
        </table>
        <table class="tablabotones">
            <tr>
                <td><input type="submit" name="save" id="save"   value="GUARDAR" class = "boton" /></td>    
                <td><input type="button" name="cancelar" id="cancelar" class = "boton" value="CANCELAR" title="CANCELAR"  onclick="javascript:{lanzar('productosvista.php')}"></td>
            </tr>
        </table>
    </form>
</html>
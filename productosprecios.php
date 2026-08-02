<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    //echo "<pre>";print_r($_GET);print_r($_POST);echo "</pre>";

    $id = trim($_GET['id']);
    $nombre = trim($_GET['nombre']);

?>


<html lang="en">
    
    <form action="metodos.php?metodo=productospreciosmodificar" method="POST" >
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th colspan="2">ACTUALIZAR PRECIO DEL PRODUCTO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PRODUCTO:</td>
                    <td>
                        <input type="text" id="productos" name="productos" size="60" maxlength="60" value="<?php echo $nombre?>" disabled>
                        <input type="hidden" id="hiddenid" name="hiddenid" value = "<?php echo $id?>" >
                        <input type="hidden" id="hiddeninombre" name="hiddennombre" value = "<?php echo $nombre?>" >
                    </td>
                </tr>
                <tr>
                    <td>PRECIO ACTUAL:</td>
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
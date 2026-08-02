<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    //echo "<pre>";print_r($_GET);print_r($_POST);echo "</pre>";
    $id = trim($_GET['id']);
    $status = trim($_GET['status']);
?>


<html lang="en">
    
    <form action="metodos.php?metodo=empleadosactivarretirar" method="POST">
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th>ATENCION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align:center">ESTÁ SEGURO DE <?php echo $status?> ESTE EMPLEADO? </td>
                    <input type="hidden" id="hiddenid" name="hiddenid" value = "<?php echo $id?>" >
                    <input type="hidden" id="hiddenstatus" name="hiddenstatus" value = "<?php echo $status?>" >
                </tr>
            </tbody>
        </table>
        <table class="tablabotones">
            <tr>
                <td><input type="submit" name="save" id="save"   value="GUARDAR" class = "boton" /></td>     
                <td><input type="button" name="cancelar" id="cancelar" class = "boton" value="CANCELAR" title="CANCELAR"  onclick="javascript:{lanzar('empleadosactivosvista.php')}"></td>
            </tr>
        </table>
    </form>
</html>
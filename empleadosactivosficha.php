<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    //echo "<pre>";print_r($_GET);print_r($_POST);echo "</pre>";
    $id = trim($_GET['id']);
    $qry = "select * from empleados where id=$id";
    //echo $qry."<br>";
    $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $expediente = trim($row['expediente']);
    $cedula = trim($row['cedula']);
    $nombres = trim($row['nombres']);
    $apellidos = trim($row['apellidos']);
    $idcargo = trim($row['cargo']);
    $idubicacion = trim($row['ubicacion']);
    $fechaingreso = trim($row['ingreso']);

    $cargo = b_combo($mysqli,'nombre','cargos','id',$idcargo);
    $ubicacion = b_combo($mysqli,'nombre','ubicaciones','id',$idubicacion);

?>


<html lang="en">
    
    <form>
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th colspan="2">DATOS DEL EMPLEADO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>EXPEDIENTE:</td>
                    <td><input type="text" id="expediente" name="expediente" size="7" maxlength="07" value="<?php echo $expediente?>" disabled></td>
                </tr>
                <tr>
                    <td>CEDULA Nro.:</td>
                    <td><input type="text" id="cedula" name="cedula" size="8" maxlength="8" value="<?php echo $cedula?>" disabled></td>
                </tr>
                <tr>
                    <td>NOMBRES:</td>
                    <td><input type="text" id="nombres" name="nombres" size="40" maxlength="40" value="<?php echo $nombres?>" disabled></td>
                </tr>
                <tr>
                    <td>APELLIDOS:</td>
                    <td><input type="text" id="apellidos" name="apellidos" size="40" maxlength="40" value="<?php echo $apellidos?>"  disabled></td>
                </tr>
                <tr>
                    <td>CARGO:</td>
                    <td><input type="text" id="cargo" name="cargo" size="40" maxlength="40" value="<?php echo $cargo?>" disabled></td>
                </tr>
                <tr>
                    <td>UBICACION:</td>
                    <td><input type="text" id="ubicacion" name="ubicacion" size="40" maxlength="40" value="<?php echo $ubicacion?>" disabled></td>
                </tr>
                <tr>
                    <td>FECHA DE INGRESO:</td>
                    <td><input type="text" id="ingreso" name="ingreso" size="10" maxlength="10" value="<?php echo invierte_fecha(($fechaingreso),0)?>" disabled></td>
                </tr>
            </tbody>
        </table>
        <table class="tablabotones">
            <tr>
                <td><input type="button" name="aceptar" id="aceptar" class = "boton" value="ACEPTAR" title="ACEPTAR"  onclick="javascript:{lanzar('empleadosactivosvista.php')}"></td>
            </tr>
        </table>
    </form>
</html>
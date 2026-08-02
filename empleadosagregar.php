<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

    $qry = "select * from cargos order by nombre";
    $result = mysqli_query($mysqli,$qry) or die('La consulta falló: '.$mysqli -> error);

    $qry2 = "select * from ubicaciones order by nombre";
    $result2 = mysqli_query($mysqli,$qry2) or die('La consulta falló: '.$mysqli -> error);
?>

<script>
    function convertirCamposTextoAMayusculas() {
        var camposTexto = document.querySelectorAll('input[type="text"]');
        for (var i = 0; i < camposTexto.length; i++) {
            camposTexto[i].value = camposTexto[i].value.toUpperCase();
        }
    }

    function valida_campos()
    {
        convertirCamposTextoAMayusculas();

        //Valida los campor para que no esten en blanco.
        //alert("Entra");
        var band_trabaj=true;
        mens = "DEBE INDICAR UN VALOR PARA LOS SIGUIENTES CAMPOS: \n"
        
        if(1>(document.getElementById("expediente").value).length)
        {
            mens = mens+"- EXPEDIENTE \n"
            band_trabaj=false; 
        }
                if(1>(document.getElementById("cedula").value).length)
        {
            mens = mens+"- CEDULA \n"
            band_trabaj=false; 
        }
        if(1>(document.getElementById("nombres").value).length)
        {
            mens = mens+"- NOMBRES \n"
            band_trabaj=false; 
        }
        if(1>(document.getElementById("apellidos").value).length)
        {
            mens = mens+"- APELLIDOS \n"
            band_trabaj=false; 
        }
        if(1>(document.getElementById("ingreso").value).length)
        {
            mens = mens+"- FECHA DE INGRESO \n"
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
    
    <form action="metodos.php?metodo=empleadosagregar" method="POST" onsubmit="return valida_campos()">
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th colspan="2">DATOS DEL EMPLEADO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>EXPEDIENTE:</td>
                    <td><input type="text" id="expediente" name="expediente" size="7" maxlength="07" oninput="convertirCamposTextoAMayusculas()" style="text-transform: uppercase;" ></td>
                </tr>
                <tr>
                    <td>CEDULA Nro.:</td>
                    <td><input type="text" id="cedula" name="cedula" size="8" maxlength="8" oninput="convertirCamposTextoAMayusculas()" style="text-transform: uppercase;" ></td>
                </tr>
                <tr>
                    <td>NOMBRES:</td>
                    <td><input type="text" id="nombres" name="nombres" size="40" maxlength="40" oninput="convertirCamposTextoAMayusculas()" style="text-transform: uppercase;"  ></td>
                </tr>
                <tr>
                    <td>APELLIDOS:</td>
                    <td><input type="text" id="apellidos" name="apellidos" size="40" maxlength="40" oninput="convertirCamposTextoAMayusculas()" style="text-transform: uppercase;"   ></td>
                </tr>
                <tr>
                    <td>CARGO:</td>
                    <td>
                        <select id="seleccioncargos" name="seleccioncargos">
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
                    <td>UBICACION:</td>
                    <td>
                        <select id="seleccionubicaciones" name="seleccionubicaciones">
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
                    <td>FECHA DE INGRESO:</td>
                    <td><input type="date" id="ingreso" name="ingreso" size="10" maxlength="10"  ></td>
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
<?php 
    include ('encabezado.php'); 
    include ('conexion_db.php');
    include ('funciones.php');

?>

<script>
    function convertirDescripcionAMayusculas() {
        var campoDescripcion = document.getElementById("descripcion");
        if (campoDescripcion) {
            campoDescripcion.value = campoDescripcion.value.toUpperCase();
        }
    }

    function valida_campos()
    {
        convertirDescripcionAMayusculas();

        //Valida los campor para que no esten en blanco.
        //alert("Entra");
        var band_trabaj=true;
        mens = "DEBE INDICAR UN VALOR PARA LOS SIGUIENTES CAMPOS: \n"
        
        if(1>(document.getElementById("descripcion").value).length)
        {
            mens = mens+"- DESCRIPCION \n"
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
    
    <form action="metodos.php?metodo=articulosguardar" method="POST" onsubmit="return valida_campos()">
        <table class="styled-table-1">
            <thead>
                <tr>
                    <th colspan="2">AGREGAR ARTICULO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>DESCRIPCION:</td>
                    <td><input type="text" id="descripcion" name="descripcion" size="40" maxlength="40" autofocus oninput="convertirDescripcionAMayusculas()" style="text-transform: uppercase;"></td>
                </tr>
            </tbody>
        </table>
        <table class="tablabotones">
            <tr>
                <td><input type="submit" name="guardar" id="guardar"   value="GUARDAR" class = "boton" title="GUARDAR"/></td>
                <td><input type="button" name="cancelar" id="cancelar" class = "boton" value="CANCELAR" title="CANCELAR"  onclick="javascript:{lanzar('articulosvista.php')}"></td>
            </tr>
        </table>
    </form>
</html>
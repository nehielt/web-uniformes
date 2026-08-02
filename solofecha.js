// JavaScript Document
//Validar solo numero dentro de la funcion 
var nav5 = window.Event ? true : false;
function solofecha(evt)
{	
    // NOTA OJO CON ESTO: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
    var key = nav5 ? evt.which : evt.keyCode;	
    return (key <= 13 || key == 45 || (key >= 48 && key <= 57));
}


<?php
include_once 'auth.php';
require_login();
verificar_acceso_pagina();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GESTION DE UNIFORMES</title>
        <link rel="stylesheet" href="formatos.css" type="text/css" charset="utf-8" />
        <script type="text/javascript" src="funciones_javascript.js"></script>
        <script type="text/javascript" src="solonum.js"></script>
        <script type="text/javascript" src="solofecha.js"></script>
        <script type="text/javascript" src="solonumsincoma.js"></script>
    </head>
    <body>
        <div class="home-logo home-logo-left">
            <img src="logo_setecsa.png" alt="Logo SETECSA">
        </div>
        <header>
            <h1 class = "cabeza">GESTION DE UNIFORMES</h1>
        </header>  
        
        <menu class="menu">
            <ul>
                <li><a href="">ARCHIVOS</a>
                    <ul id="submenu">
                            <li><a href="articulosvista.php">ARTICULOS</a></li>
                            <li><a href="tallasvista.php">TALLAS</a></li>
                            <li><a href="coloresvista.php">COLORES</a></li>
                            <li><a href="empleadosactivosvista.php">EMPLEADOS ACTIVOS</a></li>
                            <li><a href="empleadosretiradosvista.php">EMPLEADOS RETIRADOS</a></li>
                            <li><a href="cargosvista.php">CARGOS</a></li>
                            <li><a href="ubicacionesvista.php">UBICACIONES</a></li>
                    </ul>
                </li>
                <li><a href="productosvista.php">INVENTARIO</a></li>
                <li><a href="ordenesvista.php">ORDEN DE ENTREGA</a></li>
                <li><a href="#">REPORTES</a>
                    <ul id="submenu">
                            <li><a href="inventarioreporte.php">INVENTARIO</a></li>
                            <li><a href="empleadosordenesreporte.php">ORDENES</a></li>
                    </ul>
                </li>
                <li><a href="seguridad.php">SEGURIDAD</a></li>
                <?php if (has_perm('perm_auditoria')): ?>
                    <li><a href="auditoria.php">AUDITORÍA</a></li>
                <?php endif; ?>
                <li><a href="index.php">MENU PRINCIPAL</a></li>
                <li><a href="logout.php">SALIR</a></li>
            </ul>
        </menu>

    </body>
</html>
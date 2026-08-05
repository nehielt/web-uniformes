<?php
include_once 'auth.php';
require_login();
verificar_acceso_pagina();

$nombreUsuarioSesion = '';
if (!empty($_SESSION['nombre_completo'])) {
    $nombreUsuarioSesion = trim($_SESSION['nombre_completo']);
} elseif (!empty($_SESSION['usuario'])) {
    $nombreUsuarioSesion = trim($_SESSION['usuario']);
}

$rolUsuarioSesion = !empty($_SESSION['rol']) ? trim($_SESSION['rol']) : '';
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
        <header class="app-header">
            <h1 class = "cabeza">GESTION DE UNIFORMES</h1>
            <?php if ($nombreUsuarioSesion !== '' || $rolUsuarioSesion !== ''): ?>
                <div class="session-info" aria-label="Sesión de usuario" style="position: fixed; top: 12px; right: 20px; z-index: 10001; display: flex; flex-direction: row; align-items: center; justify-content: flex-end; gap: 0; min-width: 280px; padding: 8px 12px; border: 1px solid #d7d7d7; border-radius: 10px; background-color: rgba(255, 255, 255, 0.95); box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); text-align: right;">
                    <?php if ($nombreUsuarioSesion !== ''): ?>
                        <span class="session-info-name" style="font-size: 13px; font-weight: bold; color: #111111; line-height: 1.2; display: inline;">Usuario: <?php echo htmlspecialchars($nombreUsuarioSesion, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                    <?php if ($rolUsuarioSesion !== ''): ?>
                        <span class="session-info-role" style="font-size: 12px; color: #555555; text-transform: uppercase; letter-spacing: 0.4px; display: inline;"> - Rol: <?php echo htmlspecialchars($rolUsuarioSesion, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
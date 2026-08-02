<?php
include 'auth.php';
if (empty($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>No autorizado</title>
    <link rel="stylesheet" href="formatos.css" type="text/css" />
    <style>
        body { background:#f2f2f2; font-family: Arial, sans-serif; }
        .error-box { width: 500px; margin: 120px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); text-align: center; }
        .error-box h2 { margin-bottom: 20px; color:#f70000; }
        .error-box a { color:#f70000; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
    <div class="error-box">
        <h2>Acceso no autorizado</h2>
        <p>No tienes permisos para acceder a esta página.</p>
        <p><a href="index.php">Volver al menú principal</a></p>
    </div>
</body>
</html>

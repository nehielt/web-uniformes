<?php
include_once 'auth.php';
$adminCreated = seguridad_init();

if (!empty($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Debe ingresar usuario y contraseña.';
    } else {
        $usuarioEscaped = mysqli_real_escape_string($mysqli, $usuario);
        $qry = "SELECT * FROM usuarios WHERE username = '$usuarioEscaped' LIMIT 1";
        $result = mysqli_query($mysqli, $qry);
        if ($result && $row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = $row['username'];
                $_SESSION['rol'] = $row['role'];
                $_SESSION['nombre_completo'] = !empty($row['nombre']) ? trim($row['nombre']) : $row['username'];
                $_SESSION['perm_archivo'] = !empty($row['permiso_archivo']) ? 1 : 0;
                $_SESSION['perm_inventario'] = !empty($row['permiso_inventario']) ? 1 : 0;
                $_SESSION['perm_ordenes'] = !empty($row['permiso_ordenes']) ? 1 : 0;
                $_SESSION['perm_reportes'] = !empty($row['permiso_reportes']) ? 1 : 0;
                $_SESSION['perm_seguridad'] = !empty($row['permiso_seguridad']) ? 1 : 0;
                $_SESSION['perm_auditoria'] = !empty($row['permiso_auditoria']) ? 1 : 0;
                header('Location: index.php');
                exit;
            }
        }
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Seguridad</title>
    <link rel="stylesheet" href="formatos.css" type="text/css" />
    <style>
        body { background:#f2f2f2; font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .login-box { width: 360px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); text-align: center; }
        .login-box h2 { margin: 0 0 20px; font-size: 22px; color: #333; }
        .login-box label { display:block; margin-bottom:6px; font-weight:bold; text-align: left; }
        .login-box input[type="text"], .login-box input[type="password"] { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:4px; }
        .login-box input[type="submit"] { width:100%; padding:10px; background:#f70000; color:#fff; border:0; border-radius:4px; cursor:pointer; font-size:16px; }
        .login-box .message { margin-bottom: 15px; color: #d00; }
        .login-box .hint { margin-top: 15px; color: #555; font-size: 14px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div style="text-align:center; margin-bottom: 20px;">
            <img src="logo_setecsa.png" alt="Logo SETECSA" style="width:100%; max-width:360px; height:auto; display:inline-block;">
        </div>
        <h2>Acceso de Seguridad</h2>
        <?php if ($error): ?>
            <div class="message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($adminCreated): ?>
            <div class="hint">Se creó el usuario administrador:<br><strong>admin / admin123</strong></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" maxlength="50" autofocus>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" maxlength="50">
            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>
</html>

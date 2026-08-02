<?php
include_once 'auth.php';
require_login();
if (!has_perm('perm_seguridad')) {
    header('Location: no_autorizado.php');
    exit;
}
include 'conexion_db.php';

$mensaje = '';
$error = '';
$editId = null;
$usuario = '';
$nombre = '';
$nombres = '';
$apellidos = '';
$rol = 'Usuario';
$perm_archivo = 0;
$perm_inventario = 0;
$perm_ordenes = 0;
$perm_reportes = 0;
$perm_seguridad = 0;
$perm_auditoria = 0;
$passwordRequired = true;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['editar'])) {
    $editId = intval($_GET['editar']);
    $qry = "SELECT id, username, nombre, role, permiso_archivo, permiso_inventario, permiso_ordenes, permiso_reportes, permiso_seguridad, permiso_auditoria FROM usuarios WHERE id = $editId LIMIT 1";
    $result = mysqli_query($mysqli, $qry);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $usuario = $row['username'];
        $nombre = $row['nombre'];
        $rol = $row['role'];
        $perm_archivo = $row['permiso_archivo'];
        $perm_inventario = $row['permiso_inventario'];
        $perm_ordenes = $row['permiso_ordenes'];
        $perm_reportes = $row['permiso_reportes'];
        $perm_seguridad = $row['permiso_seguridad'];
        $perm_auditoria = $row['permiso_auditoria'];
        $passwordRequired = false;
        $nombre = trim($nombre);
        if (strpos($nombre, ' ') !== false) {
            $lastSpace = strrpos($nombre, ' ');
            $nombres = substr($nombre, 0, $lastSpace);
            $apellidos = substr($nombre, $lastSpace + 1);
        } else {
            $nombres = $nombre;
            $apellidos = '';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['usuario'])) {
    $editId = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $usuario = trim($_POST['usuario']);
    $rol = trim($_POST['rol'] ?? 'Usuario');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $nombres = trim($_POST['nombres'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');

    if (function_exists('mb_strtoupper')) {
        $nombres = mb_strtoupper($nombres, 'UTF-8');
        $apellidos = mb_strtoupper($apellidos, 'UTF-8');
    } else {
        $nombres = strtoupper($nombres);
        $apellidos = strtoupper($apellidos);
    }

    $nombre = trim($nombres . ' ' . $apellidos);
    $perm_archivo = isset($_POST['perm_archivo']) ? 1 : 0;
    $perm_inventario = isset($_POST['perm_inventario']) ? 1 : 0;
    $perm_ordenes = isset($_POST['perm_ordenes']) ? 1 : 0;
    $perm_reportes = isset($_POST['perm_reportes']) ? 1 : 0;
    $perm_seguridad = (!empty($_POST['perm_seguridad']) && $_POST['perm_seguridad'] == '1') ? 1 : 0;
    $perm_auditoria = isset($_POST['perm_auditoria']) ? 1 : 0;

    // Seguridad solo puede otorgarse automáticamente al rol Administrador.
    $perm_seguridad = strtolower($rol) === 'administrador' ? 1 : 0;

    if ($usuario === '' || ($editId === null && $password === '')) {
        $error = 'Usuario y contraseña son obligatorios para crear un usuario.';
    } elseif ($password !== '' && (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password)
    )) {
        $error = 'La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y un carácter especial.';
    } elseif ($password !== '' && $password !== $passwordConfirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $usuarioEscaped = mysqli_real_escape_string($mysqli, $usuario);
        if ($editId === null) {
            $qry = "SELECT id FROM usuarios WHERE username = '$usuarioEscaped' LIMIT 1";
            $result = mysqli_query($mysqli, $qry);
            if ($result && mysqli_num_rows($result) > 0) {
                $error = 'El usuario ya existe.';
            }
        } else {
            $qry = "SELECT id FROM usuarios WHERE username = '$usuarioEscaped' AND id != $editId LIMIT 1";
            $result = mysqli_query($mysqli, $qry);
            if ($result && mysqli_num_rows($result) > 0) {
                $error = 'Otro usuario ya usa ese nombre.';
            }
        }

        if ($error === '') {
            if (strtolower($rol) === 'administrador') {
                $perm_archivo = $perm_inventario = $perm_ordenes = $perm_reportes = $perm_seguridad = $perm_auditoria = 1;
            } elseif (strtolower($rol) === 'gerente') {
                $perm_archivo = $perm_inventario = $perm_ordenes = $perm_reportes = $perm_auditoria = 1;
                $perm_seguridad = 0;
            } elseif (strtolower($rol) === 'coordinador') {
                $perm_archivo = $perm_inventario = $perm_ordenes = $perm_reportes = 1;
                $perm_seguridad = 0;
                $perm_auditoria = 0;
            } elseif (strtolower($rol) === 'analista') {
                $perm_archivo = 0;
                $perm_inventario = 0;
                $perm_ordenes = 1;
                $perm_reportes = 1;
                $perm_seguridad = 0;
                $perm_auditoria = 0;
            } elseif (strtolower($rol) === 'auditor') {
                $perm_archivo = 0;
                $perm_inventario = 0;
                $perm_ordenes = 0;
                $perm_reportes = 1;
                $perm_seguridad = 0;
                $perm_auditoria = 1;
            }

            if ($editId === null) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $nombreEscaped = mysqli_real_escape_string($mysqli, $nombre);
                $insert = "INSERT INTO usuarios (username, password, nombre, role, permiso_archivo, permiso_inventario, permiso_ordenes, permiso_reportes, permiso_seguridad, permiso_auditoria) VALUES ('$usuarioEscaped', '$hash', '$nombreEscaped', '" . mysqli_real_escape_string($mysqli, $rol) . "', $perm_archivo, $perm_inventario, $perm_ordenes, $perm_reportes, $perm_seguridad, $perm_auditoria)";
                mysqli_query($mysqli, $insert);
                registro_auditoria('usuarios', 'INSERT', "Creó usuario $usuarioEscaped con nombre completo $nombreEscaped", $insert);
                $mensaje = 'Usuario creado correctamente.';
                $usuario = '';
                $nombre = '';
                $rol = 'Usuario';
                $perm_archivo = $perm_inventario = $perm_ordenes = $perm_reportes = $perm_seguridad = $perm_auditoria = 0;
            } else {
                $nombreEscaped = mysqli_real_escape_string($mysqli, $nombre);
                $updateParts = [
                    "username = '$usuarioEscaped'",
                    "nombre = '$nombreEscaped'",
                    "role = '" . mysqli_real_escape_string($mysqli, $rol) . "'",
                    "permiso_archivo = $perm_archivo",
                    "permiso_inventario = $perm_inventario",
                    "permiso_ordenes = $perm_ordenes",
                    "permiso_reportes = $perm_reportes",
                    "permiso_seguridad = $perm_seguridad",
                    "permiso_auditoria = $perm_auditoria"
                ];
                if ($password !== '') {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $updateParts[] = "password = '$hash'";
                }
                $update = 'UPDATE usuarios SET ' . implode(', ', $updateParts) . " WHERE id = $editId";
                mysqli_query($mysqli, $update);
                registro_auditoria('usuarios', 'UPDATE', "Modificó usuario ID $editId ($usuarioEscaped)", $update);
                $mensaje = 'Usuario actualizado correctamente.';
            }
        }
    }
}

$usuarios = [];
$result = mysqli_query($mysqli, "SELECT id, username, nombre, role, permiso_archivo, permiso_inventario, permiso_ordenes, permiso_reportes, permiso_seguridad, permiso_auditoria FROM usuarios ORDER BY username");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
}
?>
<?php include 'encabezado.php'; ?>
<html lang="es">
    <br><br><br><br>
    <div style="margin: 0 auto; max-width: 1100px; text-align: center;">
        <h2>Seguridad - Usuarios y permisos</h2>
        <?php if ($mensaje): ?><div style="color: green; margin-bottom: 15px;"><?php echo htmlspecialchars($mensaje); ?></div><?php endif; ?>
        <?php if ($error): ?><div style="color: red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <form id="form-seguridad" method="POST" style="margin-bottom: 30px;">
            <table class="styled-table-1" style="margin: 0 auto; width: 100%; max-width: 760px; text-align: left;">
                <thead>
                    <tr><th colspan="2"><?php echo $editId ? 'Editar usuario' : 'Crear usuario'; ?></th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Usuario:</td>
                        <td><input type="text" name="usuario" size="30" maxlength="50" value="<?php echo htmlspecialchars($usuario); ?>" required></td>
                    </tr>
                    <tr>
                        <td>Nombres:</td>
                        <td><input type="text" name="nombres" size="30" maxlength="100" value="<?php echo htmlspecialchars($nombres); ?>" style="text-transform: uppercase;" required></td>
                    </tr>
                    <tr>
                        <td>Apellidos:</td>
                        <td><input type="text" name="apellidos" size="30" maxlength="100" value="<?php echo htmlspecialchars($apellidos); ?>" style="text-transform: uppercase;" required></td>
                    </tr>
                    <tr>
                        <td>Nombre completo:</td>
                        <td><input type="text" name="nombre" size="30" maxlength="100" value="<?php echo htmlspecialchars($nombre); ?>" readonly></td>
                    </tr>
                    <tr>
                        <td>Contraseña:</td>
                        <td>
                            <input type="password" name="password" size="30" maxlength="50" <?php echo $editId ? '' : 'required'; ?>>
                            <small>Mínimo 8 caracteres, con mayúsculas, minúsculas y un carácter especial.</small>
                        </td>
                    </tr>
                    <tr>
                        <td>Repetir contraseña:</td>
                        <td><input type="password" name="password_confirm" size="30" maxlength="50" <?php echo $editId ? '' : 'required'; ?>></td>
                    </tr>
                    <tr>
                        <td>Rol:</td>
                        <td>
                            <select name="rol" id="rol" style="width: 100%; max-width: 320px;">
                                <option value="Usuario" <?php echo $rol === 'Usuario' ? 'selected' : ''; ?>>Usuario</option>
                                <option value="Auditor" <?php echo $rol === 'Auditor' ? 'selected' : ''; ?>>Auditor</option>
                                <option value="Analista" <?php echo $rol === 'Analista' ? 'selected' : ''; ?>>Analista</option>
                                <option value="Coordinador" <?php echo $rol === 'Coordinador' ? 'selected' : ''; ?>>Coordinador</option>
                                <option value="Gerente" <?php echo $rol === 'Gerente' ? 'selected' : ''; ?>>Gerente</option>
                                <option value="Administrador" <?php echo $rol === 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Permisos:</td>
                        <td>
                            <label><input type="checkbox" name="perm_archivo" class="perm-check" <?php echo $perm_archivo ? 'checked' : ''; ?>> Archivos</label><br>
                            <label><input type="checkbox" name="perm_inventario" class="perm-check" <?php echo $perm_inventario ? 'checked' : ''; ?>> Inventario</label><br>
                            <label><input type="checkbox" name="perm_ordenes" class="perm-check" <?php echo $perm_ordenes ? 'checked' : ''; ?>> Orden de entrega</label><br>
                            <label><input type="checkbox" name="perm_reportes" class="perm-check" <?php echo $perm_reportes ? 'checked' : ''; ?>> Reportes</label><br>
                            <label><input type="checkbox" id="perm_seguridad" class="perm-check" <?php echo $perm_seguridad ? 'checked' : ''; ?> disabled> Seguridad</label>
                            <input type="hidden" name="perm_seguridad" id="perm_seguridad_hidden" value="<?php echo $perm_seguridad ? '1' : '0'; ?>"><br>
                            <label><input type="checkbox" name="perm_auditoria" class="perm-check" <?php echo $perm_auditoria ? 'checked' : ''; ?>> Auditoría</label>
                        </td>
                    </tr>
                    <?php if ($editId): ?>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="id" value="<?php echo intval($editId); ?>">
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2" style="text-align:center;"><input type="submit" class="boton <?php echo $editId ? '' : 'boton-azul'; ?>" value="<?php echo $editId ? 'Actualizar usuario' : 'CREAR'; ?>"></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <script>
            (function() {
                var rolSelect = document.getElementById('rol');
                var permChecks = document.querySelectorAll('.perm-check');
                var formSeguridad = document.getElementById('form-seguridad');
                var nombresInput = document.querySelector('input[name="nombres"]');
                var apellidosInput = document.querySelector('input[name="apellidos"]');
                var nombreCompletoInput = document.querySelector('input[name="nombre"]');
                var permSeguridadCheck = document.getElementById('perm_seguridad');
                var permSeguridadHidden = document.getElementById('perm_seguridad_hidden');
                function updatePerms() {
                    if (!rolSelect) return;
                    if (rolSelect.value === 'Administrador') {
                        permChecks.forEach(function(checkbox) {
                            checkbox.checked = true;
                        });
                    } else if (rolSelect.value === 'Auditor') {
                        permChecks.forEach(function(checkbox) {
                            if (checkbox.name === 'perm_reportes' || checkbox.name === 'perm_auditoria') {
                                checkbox.checked = true;
                            } else {
                                checkbox.checked = false;
                            }
                        });
                    } else if (rolSelect.value === 'Analista') {
                        permChecks.forEach(function(checkbox) {
                            if (checkbox.name === 'perm_ordenes' || checkbox.name === 'perm_reportes') {
                                checkbox.checked = true;
                            } else {
                                checkbox.checked = false;
                            }
                        });
                    } else if (rolSelect.value === 'Coordinador') {
                        permChecks.forEach(function(checkbox) {
                            if (checkbox.name === 'perm_archivo' || checkbox.name === 'perm_inventario' || checkbox.name === 'perm_ordenes' || checkbox.name === 'perm_reportes') {
                                checkbox.checked = true;
                            } else {
                                checkbox.checked = false;
                            }
                        });
                    } else if (rolSelect.value === 'Gerente') {
                        permChecks.forEach(function(checkbox) {
                            checkbox.checked = checkbox.id !== 'perm_seguridad';
                        });
                    } else {
                        permChecks.forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                    }

                    var isLockedRole = rolSelect.value === 'Administrador' || rolSelect.value === 'Gerente' || rolSelect.value === 'Coordinador' || rolSelect.value === 'Analista' || rolSelect.value === 'Auditor';
                    permChecks.forEach(function(checkbox) {
                        if (checkbox.id === 'perm_seguridad') {
                            checkbox.disabled = true;
                        } else {
                            checkbox.disabled = isLockedRole;
                        }
                    });

                    if (permSeguridadCheck && permSeguridadHidden) {
                        permSeguridadCheck.checked = rolSelect.value === 'Administrador';
                        permSeguridadHidden.value = permSeguridadCheck.checked ? '1' : '0';
                    }
                }
                function updateNombreCompleto() {
                    if (!nombresInput || !apellidosInput || !nombreCompletoInput) return;

                    nombresInput.value = nombresInput.value.toUpperCase();
                    apellidosInput.value = apellidosInput.value.toUpperCase();

                    var nombres = nombresInput.value.trim();
                    var apellidos = apellidosInput.value.trim();
                    nombreCompletoInput.value = nombres + (nombres !== '' && apellidos !== '' ? ' ' : '') + apellidos;
                }
                if (rolSelect) {
                    rolSelect.addEventListener('change', updatePerms);
                    updatePerms();
                }
                if (nombresInput) {
                    nombresInput.addEventListener('input', updateNombreCompleto);
                }
                if (apellidosInput) {
                    apellidosInput.addEventListener('input', updateNombreCompleto);
                }
                if (formSeguridad) {
                    formSeguridad.addEventListener('submit', function() {
                        updateNombreCompleto();
                        if (permSeguridadCheck && permSeguridadHidden) {
                            permSeguridadHidden.value = permSeguridadCheck.checked ? '1' : '0';
                        }
                    });
                }
                updateNombreCompleto();
            })();
        </script>

        <table class="styled-table-3" style="margin: 0 auto; width: 100%; max-width: 1100px;">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Archivos</th>
                    <th>Inventario</th>
                    <th>Ordenes</th>
                    <th>Reportes</th>
                    <th>Seguridad</th>
                    <th>Auditoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['role']); ?></td>
                    <td><?php echo $usuario['permiso_archivo'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $usuario['permiso_inventario'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $usuario['permiso_ordenes'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $usuario['permiso_reportes'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $usuario['permiso_seguridad'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $usuario['permiso_auditoria'] ? 'Sí' : 'No'; ?></td>
                    <td><a href="seguridad.php?editar=<?php echo intval($usuario['id']); ?>" class="boton boton-amarillo" style="display: inline-block; text-decoration: none; line-height: 40px;">EDITAR</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</html>
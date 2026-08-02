<?php
include_once 'auth.php';
require_login();
if (!has_perm('perm_auditoria')) {
    header('Location: no_autorizado.php');
    exit;
}
include 'conexion_db.php';

$auditorias = [];
$qry = "SELECT id, usuario, tabla, accion, descripcion, sql_query, fecha FROM auditoria ORDER BY fecha DESC LIMIT 200";
$result = mysqli_query($mysqli, $qry);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $auditorias[] = $row;
    }
}
?>
<?php include 'encabezado.php'; ?>
<html lang="es">
    <br><br><br><br>
    <div style="margin: 0 80px;">
        <h2>Auditoría de cambios</h2>
        <table class="styled-table-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Tabla</th>
                    <th>Acción</th>
                    <th>Descripción</th>
                    <th>SQL</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditorias as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($item['tabla']); ?></td>
                    <td><?php echo htmlspecialchars($item['accion']); ?></td>
                    <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($item['sql_query']); ?></td>
                    <td><?php echo htmlspecialchars($item['fecha']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</html>
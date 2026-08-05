<?php
include 'encabezado.php';
include 'conexion_db.php';

function obtener_escalar($mysqli, $sql)
{
    $resultado = mysqli_query($mysqli, $sql);
    if (!$resultado) {
        return 0;
    }

    $fila = mysqli_fetch_assoc($resultado);
    if (!$fila) {
        return 0;
    }

    return (int) array_shift($fila);
}

$kpiTotalArticulosEntregados = obtener_escalar(
    $mysqli,
    "SELECT COALESCE(SUM(cantidad), 0) FROM detalles"
);

$kpiArticulosDebajoMinimo = obtener_escalar(
    $mysqli,
    "SELECT COUNT(*) FROM inventario WHERE existencia < minimo"
);

$kpiUnidadesFaltantes = obtener_escalar(
    $mysqli,
    "SELECT COALESCE(SUM(GREATEST(minimo - existencia, 0)), 0) FROM inventario WHERE existencia < minimo"
);

$kpiTotalOrdenes = obtener_escalar(
    $mysqli,
    "SELECT COUNT(*) FROM ordenes"
);

$kpiTotalEmpleadosActivos = obtener_escalar(
    $mysqli,
    "SELECT COUNT(*) FROM empleados WHERE activo = 1"
);

$kpiEmpleadosMasUnAno = obtener_escalar(
    $mysqli,
    "SELECT COUNT(*)
     FROM empleados e
     LEFT JOIN (
         SELECT empleado, MAX(fecha) AS ultima_fecha
         FROM ordenes
         GROUP BY empleado
     ) o ON o.empleado = e.id
     WHERE e.activo = 1
       AND o.ultima_fecha IS NOT NULL
       AND o.ultima_fecha < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)"
);

$kpiEmpleadosSinEntregas = obtener_escalar(
    $mysqli,
    "SELECT COUNT(*)
     FROM empleados e
     LEFT JOIN (
         SELECT empleado, MAX(fecha) AS ultima_fecha
         FROM ordenes
         GROUP BY empleado
     ) o ON o.empleado = e.id
     WHERE e.activo = 1
       AND o.ultima_fecha IS NULL"
);

$topArticulos = [];
$qryTopArticulos = "SELECT a.nombre AS articulo, COALESCE(SUM(d.cantidad), 0) AS total_entregado
                    FROM detalles d
                    INNER JOIN inventario i ON i.id = d.inventario
                    INNER JOIN articulos a ON a.id = i.idarticulos
                    GROUP BY a.id, a.nombre
                    ORDER BY total_entregado DESC, a.nombre ASC
                    LIMIT 5";
$resultTopArticulos = mysqli_query($mysqli, $qryTopArticulos);
if ($resultTopArticulos) {
    while ($fila = mysqli_fetch_assoc($resultTopArticulos)) {
        $topArticulos[] = $fila;
    }
}

$alertasStock = [];
$qryAlertasStock = "SELECT
                        a.nombre AS articulo,
                        c.nombre AS color,
                        t.nombre AS talla,
                        i.existencia,
                        i.minimo,
                        (i.minimo - i.existencia) AS faltante
                    FROM inventario i
                    INNER JOIN articulos a ON a.id = i.idarticulos
                    LEFT JOIN colores c ON c.id = i.idcolores
                    LEFT JOIN tallas t ON t.id = i.idtallas
                    WHERE i.existencia < i.minimo
                    ORDER BY faltante DESC, a.nombre ASC
                    LIMIT 5";
$resultAlertasStock = mysqli_query($mysqli, $qryAlertasStock);
if ($resultAlertasStock) {
    while ($fila = mysqli_fetch_assoc($resultAlertasStock)) {
        $alertasStock[] = $fila;
    }
}
?>

<style>
    .dashboard-wrap {
        margin: 32px auto;
        padding: 0 20px 20px 20px;
        max-width: 1200px;
    }

    .dashboard-title {
        margin: 0 0 8px 0;
        text-align: center;
        color: #111111;
        letter-spacing: 0.5px;
    }

    .dashboard-subtitle {
        margin: 0 0 24px 0;
        text-align: center;
        color: #4a4a4a;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #d9d9d9;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .kpi-card h3 {
        margin: 0;
        font-size: 13px;
        color: #505050;
        text-transform: uppercase;
    }

    .kpi-card p {
        margin: 10px 0 0 0;
        font-size: 30px;
        font-weight: bold;
        color: #1e1e1e;
        line-height: 1.05;
    }

    .kpi-alerta {
        border-left: 6px solid #dc3545;
    }

    .kpi-ok {
        border-left: 6px solid #28a745;
    }

    .panel-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #d9d9d9;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    }

    .panel h3 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #1f1f1f;
    }

    .resumen-tabla {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .resumen-tabla th,
    .resumen-tabla td {
        border-bottom: 1px solid #ececec;
        padding: 8px 6px;
        text-align: left;
    }

    .resumen-tabla th {
        background: #2f2f2f;
        color: #ffffff;
        font-size: 12px;
        letter-spacing: 0.3px;
    }

    .resumen-vacio {
        color: #6d6d6d;
        font-style: italic;
    }

    .dashboard-logo {
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 9999;
    }

    .dashboard-logo img {
        max-height: 120px;
        width: auto;
        display: block;
        cursor: pointer;
    }

    @media (max-width: 960px) {
        .panel-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-wrap">
    <h2 class="dashboard-title">DASHBOARD DE GESTION DE UNIFORMES</h2>
    <p class="dashboard-subtitle">Indicadores clave del sistema en tiempo real.</p>

    <div class="kpi-grid">
        <div class="kpi-card kpi-ok">
            <h3>Total de articulos entregados</h3>
            <p><?php echo number_format($kpiTotalArticulosEntregados); ?></p>
        </div>
        <div class="kpi-card <?php echo $kpiArticulosDebajoMinimo > 0 ? 'kpi-alerta' : 'kpi-ok'; ?>">
            <h3>Articulos por debajo del minimo</h3>
            <p><?php echo number_format($kpiArticulosDebajoMinimo); ?></p>
        </div>
        <div class="kpi-card <?php echo $kpiUnidadesFaltantes > 0 ? 'kpi-alerta' : 'kpi-ok'; ?>">
            <h3>Unidades faltantes para minimo</h3>
            <p><?php echo number_format($kpiUnidadesFaltantes); ?></p>
        </div>
        <div class="kpi-card kpi-ok">
            <h3>Total de ordenes emitidas</h3>
            <p><?php echo number_format($kpiTotalOrdenes); ?></p>
        </div>
        <div class="kpi-card kpi-ok">
            <h3>Empleados activos</h3>
            <p><?php echo number_format($kpiTotalEmpleadosActivos); ?></p>
        </div>
        <div class="kpi-card <?php echo $kpiEmpleadosMasUnAno > 0 ? 'kpi-alerta' : 'kpi-ok'; ?>">
            <h3>Empleados con ultima entrega mayor a 1 ano</h3>
            <p><?php echo number_format($kpiEmpleadosMasUnAno); ?></p>
        </div>
        <div class="kpi-card <?php echo $kpiEmpleadosSinEntregas > 0 ? 'kpi-alerta' : 'kpi-ok'; ?>">
            <h3>Empleados activos sin entregas</h3>
            <p><?php echo number_format($kpiEmpleadosSinEntregas); ?></p>
        </div>
    </div>

    <div class="panel-grid">
        <section class="panel">
            <h3>Articulos mas entregados</h3>
            <?php if (empty($topArticulos)) { ?>
                <p class="resumen-vacio">No hay entregas registradas para mostrar.</p>
            <?php } else { ?>
                <table class="resumen-tabla">
                    <thead>
                        <tr>
                            <th>Articulo</th>
                            <th>Total entregado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topArticulos as $item) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['articulo']); ?></td>
                                <td><?php echo number_format($item['total_entregado']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </section>

        <section class="panel">
            <h3>Alertas de inventario (top 5 faltantes)</h3>
            <?php if (empty($alertasStock)) { ?>
                <p class="resumen-vacio">No hay articulos por debajo del stock minimo.</p>
            <?php } else { ?>
                <table class="resumen-tabla">
                    <thead>
                        <tr>
                            <th>Articulo</th>
                            <th>Color</th>
                            <th>Talla</th>
                            <th>Existencia</th>
                            <th>Minimo</th>
                            <th>Faltante</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alertasStock as $item) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['articulo']); ?></td>
                                <td><?php echo htmlspecialchars($item['color'] ?: '-'); ?></td>
                                <td><?php echo htmlspecialchars($item['talla'] ?: '-'); ?></td>
                                <td><?php echo number_format($item['existencia']); ?></td>
                                <td><?php echo number_format($item['minimo']); ?></td>
                                <td><?php echo number_format($item['faltante']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </section>
    </div>
</div>

<div class="dashboard-logo">
    <img src="logouneti.jpg" alt="Logo UNETI" id="logouneti">
</div>

<script>
    document.getElementById('logouneti').addEventListener('click', function () {
        alert('Este aplicaci\u00f3n fue desarrollada por Monica Mendez y Nehiel Tovar');
    });
</script>

<?php
// Conexión a la base de datos
include("../config/db.php");
include("../layout/sidebar.php");
include("../layout/topbar.php");

// Totales de visitas
$visitas = [
    "total" => 0,
    "pendientes" => 0,
    "en_progreso" => 0,
    "finalizadas" => 0
];

$resVisitas = $conexion->query("
    SELECT estado, COUNT(*) as total 
    FROM visitas 
    GROUP BY estado
");

while($row = $resVisitas->fetch_assoc()){
    $visitas[strtolower($row['estado'])] = $row['total'];
    $visitas['total'] += $row['total'];
}

// Totales de hallazgos
$hallazgos = [
    "pendientes" => 0,
    "en_proceso" => 0,
    "cumplidos" => 0,
    "vencidos" => 0
];

$resHallazgos = $conexion->query("
    SELECT estado, COUNT(*) as total 
    FROM hallazgos 
    GROUP BY estado
");

while($row = $resHallazgos->fetch_assoc()){
    $hallazgos[strtolower($row['estado'])] = $row['total'];
}

// Calcular visitas realizadas este año
$anioActual = date('Y');
$resVisitasAnio = $conexion->query("
    SELECT COUNT(*) as total 
    FROM visitas 
    WHERE YEAR(fecha_visita) = $anioActual
");
$visitasAnio = $resVisitasAnio->fetch_assoc()['total'] ?? 0;

// Porcentaje de cumplimiento hallazgos
$totalHallazgos = array_sum($hallazgos);
$porcCumplimiento = $totalHallazgos > 0 ? round(($hallazgos['cumplidos'] / $totalHallazgos) * 100, 1) : 0;

// Hallazgos próximos a vencer (máx 10)
$proximosVencer = [];
$resProximos = $conexion->query("
    SELECT h.id, h.descripcion, h.fecha_mejora, u.nombre as responsable
    FROM hallazgos h
    JOIN usuarios u ON h.responsable_id = u.id
    WHERE h.estado != 'cumplido'
    AND h.fecha_mejora <= DATE_ADD(CURDATE(), INTERVAL 20 DAY)
    ORDER BY h.fecha_mejora ASC
    LIMIT 10
");
while($row = $resProximos->fetch_assoc()){
    $proximosVencer[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Auditorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,.1); }
        .section-title { margin: 30px 0 15px; font-weight: bold; }
        
        .main-content { margin-left: 220px; }
    </style>
</head>
<body>
<div class="main-content">
<div class="container py-4" style="margin-top:70px;">
    <div class="quick-btns mb-3">
        <a href="#" class="btn btn-primary btn-sm">Registrar nueva visita</a>
        <a href="#" class="btn btn-secondary btn-sm">Ver historial de auditorías</a>
        <a href="#" class="btn btn-success btn-sm">Subir evidencias</a>
    </div>

    <!-- Fila 1: Tarjetas resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary p-3">
                <h6>Visitas año actual</h6>
                <h3><?= $visitasAnio ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning p-3">
                <h6>Hallazgos pendientes</h6>
                <h3><?= $hallazgos['pendientes'] ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success p-3">
                <h6>% Cumplimiento Hallazgos</h6>
                <h3><?= $porcCumplimiento ?>%</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-danger p-3">
                <h6>Próximos a vencer</h6>
                <h3><?= count($proximosVencer) ?></h3>
            </div>
        </div>
    </div>

    <!-- Fila 2: Gráficas (placeholders) -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Hallazgos por estado</h6>
                <div style="height:220px;display:flex;align-items:center;justify-content:center;color:#aaa;">
                    [Gráfica de barras aquí]
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h6>Distribución por responsable</h6>
                <div style="height:220px;display:flex;align-items:center;justify-content:center;color:#aaa;">
                    [Gráfica de pastel aquí]
                </div>
            </div>
        </div>
    </div>

    <!-- Fila 3: Próximos hallazgos a vencer -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card p-3">
                <h6>Próximos hallazgos a vencer (10)</h6>
                <?php if(count($proximosVencer) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-danger">
                            <tr>
                                <th>ID</th>
                                <th>Descripción</th>
                                <th>Fecha Mejora</th>
                                <th>Responsable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($proximosVencer as $alerta): ?>
                            <tr>
                                <td><?= $alerta['id'] ?></td>
                                <td><?= $alerta['descripcion'] ?></td>
                                <td><?= $alerta['fecha_mejora'] ?></td>
                                <td><?= $alerta['responsable'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="alert alert-success">✅ No hay hallazgos próximos a vencer.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>

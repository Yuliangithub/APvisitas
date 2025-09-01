<?php
include("../config/db.php");

if(isset($_GET['hallazgos']) && isset($_GET['visita_id'])) {
    $id = intval($_GET['visita_id']);
    $res = $conexion->query("
        SELECT h.id, h.descripcion, h.fecha_mejora, h.prioridad, h.estado, u.nombre as responsable
        FROM hallazgos h
        JOIN usuarios u ON h.responsable_id = u.id
        WHERE h.visita_id = $id
        ORDER BY h.id ASC
    ");
    $hallazgos = [];
    while($row = $res->fetch_assoc()) $hallazgos[] = $row;
    echo json_encode($hallazgos);
    exit;
}

// Listado de visitas con conteo de hallazgos
$res = $conexion->query("
    SELECT v.id, v.fecha_visita, v.area, v.estado, v.observaciones, u.nombre as responsable,
        (SELECT COUNT(*) FROM hallazgos WHERE visita_id = v.id) as hallazgos_count
    FROM visitas v
    JOIN usuarios u ON v.responsable_id = u.id
    ORDER BY v.fecha_visita DESC, v.id DESC
");
$visitas = [];
while($row = $res->fetch_assoc()) $visitas[] = $row;
echo json_encode($visitas);

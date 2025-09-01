<?php
include("config/db.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visita_id = intval($_POST['visita_id']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $responsable_id = intval($_POST['responsable_id']);
    $fecha_mejora = $conexion->real_escape_string($_POST['fecha_mejora']);
    $prioridad = $conexion->real_escape_string($_POST['prioridad']);
    $conexion->query("INSERT INTO hallazgos (visita_id, descripcion, responsable_id, fecha_mejora, prioridad) VALUES ($visita_id, '$descripcion', $responsable_id, '$fecha_mejora', '$prioridad')");
}
header("Location: visitas.php");
exit;

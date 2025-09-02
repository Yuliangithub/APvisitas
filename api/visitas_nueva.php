<?php
include("../config/db.php");

$usuarios = $conexion->query("SELECT id, nombre FROM usuarios");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_visita = $conexion->real_escape_string($_POST['nombre_visita']);
    $fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
    $fecha_fin = $conexion->real_escape_string($_POST['fecha_fin']);
    $observacion = $conexion->real_escape_string($_POST['observacion']);
    $resultado = $conexion->real_escape_string($_POST['resultado']);
    $plazo = $conexion->real_escape_string($_POST['plazo']);
    $recurrente = isset($_POST['recurrente']) ? 1 : 0;
    $plan_accion = $conexion->real_escape_string($_POST['plan_accion']);
    $responsable_id = intval($_POST['responsable_id']);
    $observaciones_adicionales = $conexion->real_escape_string($_POST['observaciones_adicionales']);
    $evidencia = "";

    // Crea la visita SIN aspecto_id
    $conexion->query("INSERT INTO visitas (aspecto_id, nombre_visita, fecha_inicio, fecha_fin, observacion, resultado, plazo, recurrente, plan_accion, responsable_id, evidencia, observaciones_adicionales)
        VALUES (NULL, '$nombre_visita', '$fecha_inicio', '$fecha_fin', '$observacion', '$resultado', " . ($plazo ? "'$plazo'" : "NULL") . ", $recurrente, '$plan_accion', $responsable_id, '$evidencia', '$observaciones_adicionales')");
    $visita_id = $conexion->insert_id;

    // Redirige a asignar o crear aspecto
    header("Location: ../asig_aspecto.php?visita_id=$visita_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Observación de Aspecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Registrar Nueva Visita</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-2">
            <label>Nombre de la visita</label>
            <input type="text" name="nombre_visita" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Fecha inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Fecha fin</label>
            <input type="date" name="fecha_fin" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Observación</label>
            <textarea name="observacion" class="form-control"></textarea>
        </div>
        <div class="mb-2">
            <label>Resultado</label>
            <select name="resultado" class="form-control">
                <option value="">-- Seleccionar --</option>
                <option value="cumple">Cumple</option>
                <option value="no cumple">No Cumple</option>
                <option value="parcial">Parcial</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Plazo para cumplir</label>
            <input type="date" name="plazo" class="form-control">
        </div>
        <div class="mb-2 form-check">
            <input type="checkbox" name="recurrente" class="form-check-input" id="rec">
            <label class="form-check-label" for="rec">Recurrente</label>
        </div>
        <div class="mb-2">
            <label>Plan de acción</label>
            <textarea name="plan_accion" class="form-control"></textarea>
        </div>
        <div class="mb-2">
            <label>Responsable</label>
            <select name="responsable_id" class="form-control">
                <option value="">Seleccione</option>
                <?php while($u = $usuarios->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-2">
            <label>Observaciones adicionales</label>
            <textarea name="observaciones_adicionales" class="form-control"></textarea>
        </div>
        <!-- Evidencia: puedes agregar input file aquí si lo deseas -->
        <button type="submit" class="btn btn-success">Registrar Observación</button>
    </form>
</div>
</body>

</html>

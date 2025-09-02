<?php
include("../config/db.php");

$id = intval($_GET['id'] ?? 0);
if (!$id) die("ID inválido");

$res = $conexion->query("
    SELECT va.*, a.nombre as aspecto_nombre, a.descripcion as aspecto_desc
    FROM visita_aspectos va
    JOIN aspectos a ON va.aspecto_id = a.id
    WHERE va.id = $id
");
$va = $res->fetch_assoc();
if (!$va) die("No encontrado");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $conexion->real_escape_string($_POST['resultado']);
    $observaciones = $conexion->real_escape_string($_POST['observaciones']);
    $plazo = $conexion->real_escape_string($_POST['plazo']);
    $recurrente = isset($_POST['recurrente']) ? 1 : 0;
    $plan_accion = $conexion->real_escape_string($_POST['plan_accion']);
    $conexion->query("UPDATE visita_aspectos SET 
        resultado='$resultado',
        observaciones='$observaciones',
        plazo=" . ($plazo ? "'$plazo'" : "NULL") . ",
        recurrente=$recurrente,
        plan_accion='$plan_accion'
        WHERE id=$id
    ");
    header("Location: visitas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Aspecto de Visita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Editar Aspecto Verificado</h3>
    <form method="post">
        <div class="mb-2">
            <label>Aspecto</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($va['aspecto_nombre']) ?>" disabled>
        </div>
        <div class="mb-2">
            <label>Descripción del aspecto</label>
            <textarea class="form-control" disabled><?= htmlspecialchars($va['aspecto_desc']) ?></textarea>
        </div>
        <div class="mb-2">
            <label>Observación (cumple/no cumple/parcial)</label>
            <select name="resultado" class="form-control" required>
                <option value="">-- Seleccionar --</option>
                <option value="cumple" <?= $va['resultado']=='cumple'?'selected':'' ?>>Cumple</option>
                <option value="no cumple" <?= $va['resultado']=='no cumple'?'selected':'' ?>>No Cumple</option>
                <option value="parcial" <?= $va['resultado']=='parcial'?'selected':'' ?>>Parcial</option>
            </select>
        </div>
        <div class="mb-2">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"><?= htmlspecialchars($va['observaciones']) ?></textarea>
        </div>
        <div class="mb-2">
            <label>Plazo para cumplir</label>
            <input type="date" name="plazo" class="form-control" value="<?= htmlspecialchars($va['plazo']) ?>">
        </div>
        <div class="mb-2 form-check">
            <input type="checkbox" name="recurrente" class="form-check-input" id="recurrente" <?= $va['recurrente'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="recurrente">Recurrente</label>
        </div>
        <div class="mb-2">
            <label>Plan de acción</label>
            <textarea name="plan_accion" class="form-control"><?= htmlspecialchars($va['plan_accion']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="visitas.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>

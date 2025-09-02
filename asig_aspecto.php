<?php include("config/db.php"); ?>

<?php
$visita_id = $_GET['visita_id'] ?? 0;
if (!$visita_id) die("❌ ID de visita no válido.");

$aspectos = $conexion->query("SELECT id, nombre, descripcion, prioridad FROM aspectos ORDER BY prioridad, nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['aspecto_id'])) {
        // Asignar aspecto existente
        $aspecto_id = intval($_POST['aspecto_id']);
    } elseif (!empty($_POST['nuevo_nombre'])) {
        // Crear nuevo aspecto
        $nombre = $conexion->real_escape_string($_POST['nuevo_nombre']);
        $descripcion = $conexion->real_escape_string($_POST['nuevo_descripcion']);
        $prioridad = $conexion->real_escape_string($_POST['nuevo_prioridad']);
        $conexion->query("INSERT INTO aspectos (nombre, descripcion, prioridad) VALUES ('$nombre', '$descripcion', '$prioridad')");
        $aspecto_id = $conexion->insert_id;
    } else {
        $aspecto_id = null;
    }

    if ($aspecto_id) {
        $conexion->query("UPDATE visitas SET aspecto_id=$aspecto_id WHERE id=$visita_id");
        echo "✅ Aspecto asignado correctamente.<br>";
        echo "<a href='api/visitas.php'>👉 Volver a visitas</a>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Debe seleccionar o crear un aspecto.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Aspecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Asignar o Crear Aspecto General</h3>
    <form method="post">
        <div class="mb-3">
            <label>Seleccionar aspecto existente</label>
            <select name="aspecto_id" class="form-control">
                <option value="">-- Seleccione --</option>
                <?php while($a = $aspectos->fetch_assoc()): ?>
                <option value="<?= $a['id'] ?>"><?= $a['nombre'] ?> (<?= $a['prioridad'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label>O crear nuevo aspecto</label>
            <input type="text" name="nuevo_nombre" class="form-control mb-2" placeholder="Nombre nuevo aspecto">
            <input type="text" name="nuevo_descripcion" class="form-control mb-2" placeholder="Descripción">
            <select name="nuevo_prioridad" class="form-control">
                <option value="alta">Alta</option>
                <option value="media" selected>Media</option>
                <option value="baja">Baja</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Asignar Aspecto</button>
    </form>
</div>
</body>
</html>
        <label>Responsable</label>
        <select name="nuevo_responsable_id" class="form-control">
            <option value="">Seleccione</option>
            <?php foreach($usuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" name="guardar" class="btn btn-success">Guardar Aspectos</button>
</form>

<script>
function toggleAspectoFields(checkbox, idx) {
    var div = document.getElementById('aspecto-fields-' + idx);
    div.style.display = checkbox.checked ? 'block' : 'none';
}
</script>

<?php
if (isset($_POST['guardar'])) {
    // Aspectos existentes seleccionados
    foreach ($_POST['aspectos'] ?? [] as $asp) {
        if (!empty($asp['id'])) {
            $aspecto_id = intval($asp['id']);
            $resultado = $conexion->real_escape_string($asp['resultado'] ?? '');
            $plazo = $conexion->real_escape_string($asp['plazo'] ?? '');
            $recurrente = isset($asp['recurrente']) ? 1 : 0;
            $plan_accion = $conexion->real_escape_string($asp['plan_accion'] ?? '');
            $responsable_id = intval($asp['responsable_id'] ?? 0);
            $conexion->query("INSERT INTO visita_aspectos (visita_id, aspecto_id, resultado, plazo, recurrente, plan_accion, responsable_id) VALUES ($visita_id, $aspecto_id, '$resultado', " . ($plazo ? "'$plazo'" : "NULL") . ", $recurrente, '$plan_accion', $responsable_id)");
        }
    }
    // Nuevo aspecto
    if (!empty($_POST['nuevo_nombre'])) {
        $nombre = $conexion->real_escape_string($_POST['nuevo_nombre']);
        $descripcion = $conexion->real_escape_string($_POST['nuevo_descripcion']);
        $prioridad = $conexion->real_escape_string($_POST['nuevo_prioridad']);
        $conexion->query("INSERT INTO aspectos (nombre, descripcion, prioridad) VALUES ('$nombre', '$descripcion', '$prioridad')");
        $nuevo_aspecto_id = $conexion->insert_id;
        $resultado = $conexion->real_escape_string($_POST['nuevo_resultado'] ?? '');
        $plazo = $conexion->real_escape_string($_POST['nuevo_plazo'] ?? '');
        $recurrente = isset($_POST['nuevo_recurrente']) ? 1 : 0;
        $plan_accion = $conexion->real_escape_string($_POST['nuevo_plan_accion'] ?? '');
        $responsable_id = intval($_POST['nuevo_responsable_id'] ?? 0);
        $conexion->query("INSERT INTO visita_aspectos (visita_id, aspecto_id, resultado, plazo, recurrente, plan_accion, responsable_id) VALUES ($visita_id, $nuevo_aspecto_id, '$resultado', " . ($plazo ? "'$plazo'" : "NULL") . ", $recurrente, '$plan_accion', $responsable_id)");
    }
    echo "✅ Aspectos asignados correctamente.<br>";
    echo "<a href='api/visitas.php'>👉 Volver a visitas</a>";
}
?>

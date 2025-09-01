<?php
include("config/db.php");
$hallazgo_id = intval($_GET['hallazgo_id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $hallazgo_id) {
    $tipo = $conexion->real_escape_string($_POST['tipo']);
    $archivo = $_FILES['archivo'];
    $subido_por = 1; // Cambia por el usuario logueado
    if ($archivo['tmp_name']) {
        $destino = "uploads/" . basename($archivo['name']);
        move_uploaded_file($archivo['tmp_name'], $destino);
        $conexion->query("INSERT INTO evidencias (hallazgo_id, tipo, archivo_url, subido_por) VALUES ($hallazgo_id, '$tipo', '$destino', $subido_por)");
    }
    header("Location: aspectos_visitas.php?aspecto_id=" . intval($_POST['aspecto_id']));
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Evidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Subir Evidencia</h3>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="aspecto_id" value="<?= intval($_GET['aspecto_id'] ?? 0) ?>">
        <div class="mb-3">
            <label>Tipo de archivo</label>
            <select name="tipo" class="form-control" required>
                <option value="imagen">Imagen</option>
                <option value="documento">Documento</option>
                <option value="acta">Acta</option>
                <option value="excel">Excel</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Archivo</label>
            <input type="file" name="archivo" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Subir</button>
    </form>
</div>
</body>
</html>

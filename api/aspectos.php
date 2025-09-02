<?php
include("../config/db.php");
// Agregar aspecto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $descripcion = $conexion->real_escape_string($_POST['descripcion']);
    $prioridad = $conexion->real_escape_string($_POST['prioridad']);
    $conexion->query("INSERT INTO aspectos (nombre, descripcion, prioridad) VALUES ('$nombre', '$descripcion', '$prioridad')");
    header("Location: aspectos.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aspectos Generales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Aspectos Generales</h3>
    <form method="post" class="mb-4">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
            </div>
            <div class="col-md-2">
                <select name="prioridad" class="form-control">
                    <option value="alta">Alta</option>
                    <option value="media" selected>Media</option>
                    <option value="baja">Baja</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Agregar</button>
            </div>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th><th>Descripción</th><th>Prioridad</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res = $conexion->query("SELECT * FROM aspectos ORDER BY prioridad, nombre");
        while($a = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= $a['nombre'] ?></td>
                <td><?= $a['descripcion'] ?></td>
                <td><?= ucfirst($a['prioridad']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>

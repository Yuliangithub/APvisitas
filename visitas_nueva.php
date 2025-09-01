<?php
include("config/db.php");
// Guardar visita y aspectos seleccionados/nuevos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_visita = $conexion->real_escape_string($_POST['fecha_visita']);
    $area = $conexion->real_escape_string($_POST['area']);
    $responsable_id = intval($_POST['responsable_id']);
    $observaciones = $conexion->real_escape_string($_POST['observaciones']);
    $conexion->query("INSERT INTO visitas (fecha_visita, area, responsable_id, observaciones) VALUES ('$fecha_visita', '$area', $responsable_id, '$observaciones')");
    $visita_id = $conexion->insert_id;

    // Aspectos existentes seleccionados
    if (!empty($_POST['aspectos_existentes'])) {
        foreach ($_POST['aspectos_existentes'] as $aspecto_id) {
            $aspecto_id = intval($aspecto_id);
            $conexion->query("INSERT INTO visita_aspectos (visita_id, aspecto_id, verificado) VALUES ($visita_id, $aspecto_id, 1)");
        }
    }
    // Nuevos aspectos agregados
    if (!empty($_POST['nuevo_nombre'])) {
        foreach ($_POST['nuevo_nombre'] as $idx => $nombre) {
            $nombre = $conexion->real_escape_string($nombre);
            $descripcion = $conexion->real_escape_string($_POST['nuevo_descripcion'][$idx]);
            $prioridad = $conexion->real_escape_string($_POST['nuevo_prioridad'][$idx]);
            if ($nombre) {
                $conexion->query("INSERT INTO aspectos (nombre, descripcion, prioridad) VALUES ('$nombre', '$descripcion', '$prioridad')");
                $nuevo_aspecto_id = $conexion->insert_id;
                $conexion->query("INSERT INTO visita_aspectos (visita_id, aspecto_id, verificado) VALUES ($visita_id, $nuevo_aspecto_id, 1)");
            }
        }
    }
    header("Location: visitas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Visita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function agregarAspecto() {
        var html = `
        <div class="row g-2 mb-2">
            <div class="col-md-4"><input type="text" name="nuevo_nombre[]" class="form-control" placeholder="Nombre nuevo aspecto"></div>
            <div class="col-md-4"><input type="text" name="nuevo_descripcion[]" class="form-control" placeholder="Descripción"></div>
            <div class="col-md-3">
                <select name="nuevo_prioridad[]" class="form-control">
                    <option value="alta">Alta</option>
                    <option value="media" selected>Media</option>
                    <option value="baja">Baja</option>
                </select>
            </div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm" onclick="this.parentNode.parentNode.remove()">X</button></div>
        </div>`;
        document.getElementById('nuevos-aspectos').insertAdjacentHTML('beforeend', html);
    }
    </script>
</head>
<body>
<div class="container py-4">
    <h3>Registrar Nueva Visita</h3>
    <form method="post">
        <div class="mb-2">
            <label>Fecha de visita</label>
            <input type="date" name="fecha_visita" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Área</label>
            <input type="text" name="area" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Responsable</label>
            <select name="responsable_id" class="form-control" required>
                <option value="">Seleccione</option>
                <?php
                $ures = $conexion->query("SELECT id, nombre, email FROM usuarios WHERE estado=1");
                while($u = $ures->fetch_assoc()):
                ?>
                <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-2">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Aspectos verificados existentes</label>
            <div class="border rounded p-2" style="max-height:200px;overflow:auto;">
                <?php
                $ares = $conexion->query("SELECT id, nombre, prioridad FROM aspectos ORDER BY prioridad, nombre");
                while($a = $ares->fetch_assoc()):
                ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="aspectos_existentes[]" value="<?= $a['id'] ?>" id="asp<?= $a['id'] ?>">
                    <label class="form-check-label" for="asp<?= $a['id'] ?>">
                        <?= $a['nombre'] ?> (<?= $a['prioridad'] ?>)
                    </label>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="mb-3">
            <label>Agregar nuevos aspectos verificados</label>
            <div id="nuevos-aspectos"></div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="agregarAspecto()">Agregar nuevo aspecto</button>
        </div>
        <button type="submit" class="btn btn-success">Registrar Visita</button>
    </form>
</div>
</body>
</html>

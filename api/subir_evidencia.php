<?php
include("config/db.php");
$aspecto_id = isset($_GET['aspecto_id']) ? intval($_GET['aspecto_id']) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visitas por Aspecto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Filtrar Visitas por Aspecto</h3>
    <form method="get" class="mb-4">
        <div class="row g-2">
            <div class="col-md-6">
                <select name="aspecto_id" class="form-control" required>
                    <option value="">Seleccione un aspecto</option>
                    <?php
                    $res = $conexion->query("SELECT id, nombre FROM aspectos ORDER BY prioridad, nombre");
                    while($a = $res->fetch_assoc()):
                    ?>
                    <option value="<?= $a['id'] ?>" <?= $aspecto_id==$a['id']?'selected':'' ?>><?= $a['nombre'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </div>
    </form>
    <?php if($aspecto_id): ?>
        <h5>Visitas donde se verificó este aspecto:</h5>
        <?php
        $vres = $conexion->query("
            SELECT va.*, v.fecha_visita, v.area, v.responsable_id, u.nombre as responsable
            FROM visita_aspectos va
            JOIN visitas v ON va.visita_id = v.id
            JOIN usuarios u ON v.responsable_id = u.id
            WHERE va.aspecto_id = $aspecto_id
            ORDER BY v.fecha_visita DESC
        ");
        if($vres->num_rows == 0): ?>
            <div class="alert alert-info">No hay visitas para este aspecto.</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fecha Visita</th>
                        <th>Área</th>
                        <th>Responsable</th>
                        <th>¿Verificado?</th>
                        <th>Observaciones</th>
                        <th>Hallazgos</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($v = $vres->fetch_assoc()): ?>
                    <tr>
                        <td><?= $v['fecha_visita'] ?></td>
                        <td><?= $v['area'] ?></td>
                        <td><?= $v['responsable'] ?></td>
                        <td><?= $v['verificado'] ? 'Sí' : 'No' ?></td>
                        <td><?= $v['observaciones'] ?: '-' ?></td>
                        <td>
                            <?php
                            // Hallazgos de la visita relacionados al aspecto
                            $hid = $v['visita_id'];
                            $hallazgos = $conexion->query("
                                SELECT h.*, u.nombre as responsable
                                FROM hallazgos h
                                JOIN usuarios u ON h.responsable_id = u.id
                                WHERE h.visita_id = $hid
                            ");
                            if($hallazgos->num_rows == 0) {
                                echo '<span class="text-muted">Sin hallazgos</span>';
                            } else {
                                echo '<ul class="mb-0">';
                                while($h = $hallazgos->fetch_assoc()) {
                                    echo "<li><b>{$h['descripcion']}</b> ({$h['estado']})<br>
                                    Responsable: {$h['responsable']}<br>
                                    Fecha mejora: {$h['fecha_mejora']}<br>
                                    <a href='subir_evidencia.php?hallazgo_id={$h['id']}' class='btn btn-sm btn-outline-success mt-1'>Subir evidencia</a>
                                    </li>";
                                }
                                echo '</ul>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
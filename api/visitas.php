<?php
include("../config/db.php");
include("../layout/topbar.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visitas/Auditorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Auditorías / Visitas</h3>
    <a href="visitas_nueva.php" class="btn btn-success mb-3">Registrar nueva visita</a>
    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>Fechas</th>
                <th>Nombre de la Visita</th>
                <th>Aspecto Verificado</th>
                <th>Descripción del Aspecto</th>
                <th>Estado</th>
                <th>Plazo</th>
                <th>Recurrente</th>
                <th>Plan de Acción</th>
                <th>Responsable</th>
                <th>Evidencia</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $res = $conexion->query("
            SELECT v.id as visita_id, v.nombre_visita, v.fecha_inicio, v.fecha_fin, v.observacion, v.resultado, v.plazo, v.recurrente, v.plan_accion, v.evidencia, v.observaciones_adicionales,
                   a.nombre as aspecto_nombre, a.descripcion as aspecto_desc,
                   u.nombre as responsable_nombre
            FROM visitas v
            JOIN aspectos a ON v.aspecto_id = a.id
            LEFT JOIN usuarios u ON v.responsable_id = u.id
            ORDER BY v.fecha_inicio DESC, v.id DESC
        ");
        while($row = $res->fetch_assoc()):
        ?>
            <tr>
                <td>
                    <?= htmlspecialchars($row['fecha_inicio']) ?>
                    <?php if ($row['fecha_fin'] && $row['fecha_fin'] != $row['fecha_inicio']): ?>
                        al <?= htmlspecialchars($row['fecha_fin']) ?>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['nombre_visita']) ?></td>
                <td><?= htmlspecialchars($row['aspecto_nombre']) ?></td>
                <td><?= htmlspecialchars($row['aspecto_desc']) ?></td>
                <td>
                    <?php
                        if ($row['resultado'] == 'cumple') echo '<span class="badge bg-success">Cumple</span>';
                        elseif ($row['resultado'] == 'no cumple') echo '<span class="badge bg-danger">No Cumple</span>';
                        elseif ($row['resultado'] == 'parcial') echo '<span class="badge bg-warning text-dark">Parcial</span>';
                        else echo '-';
                    ?>
                    <br>
                    <?= htmlspecialchars($row['observacion']) ?>
                </td>
                <td><?= $row['plazo'] ? htmlspecialchars($row['plazo']) : '-' ?></td>
                <td><?= $row['recurrente'] ? 'Sí' : 'No' ?></td>
                <td><?= htmlspecialchars($row['plan_accion']) ?></td>
                <td><?= htmlspecialchars($row['responsable_nombre']) ?></td>
                <td>
                    <?php if ($row['evidencia']): ?>
                        <a href="<?= htmlspecialchars($row['evidencia']) ?>" target="_blank">Ver archivo</a>
                    <?php else: ?>
                        <span class="text-muted">Sin evidencia</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="editar_visita.php?id=<?= $row['visita_id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                    <!-- Puedes agregar botón para subir evidencia aquí -->
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
include("../config/db.php");
include("../layout/sidebar.php");
include("../layout/topbar.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h3>Visitas</h3>
    <div id="visitas-list">
        <?php
        $res = $conexion->query("
            SELECT v.*, u.nombre as responsable
            FROM visitas v
            JOIN usuarios u ON v.responsable_id = u.id
            ORDER BY v.fecha_visita DESC
        ");
        while($v = $res->fetch_assoc()):
        ?>
        <div class="card mb-3">
            <div class="card-body">
                <b>Fecha:</b> <?= $v['fecha_visita'] ?> |
                <b>Área:</b> <?= $v['area'] ?> |
                <b>Responsable:</b> <?= $v['responsable'] ?> |
                <b>Estado:</b> <?= $v['estado'] ?>
                <br>
                <b>Observaciones:</b> <?= $v['observaciones'] ?: '-' ?>
                <br>
                <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="collapse" data-bs-target="#hallazgos-<?= $v['id'] ?>">Ver/Agregar Hallazgos</button>
                <div class="collapse mt-3" id="hallazgos-<?= $v['id'] ?>">
                    <!-- Listado de hallazgos -->
                    <?php
                    $hid = $v['id'];
                    $hres = $conexion->query("
                        SELECT h.*, u.nombre as responsable
                        FROM hallazgos h
                        JOIN usuarios u ON h.responsable_id = u.id
                        WHERE h.visita_id = $hid
                        ORDER BY h.id ASC
                    ");
                    if($hres->num_rows > 0): ?>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th><th>Descripción</th><th>Responsable</th><th>Fecha Mejora</th><th>Prioridad</th><th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php while($h = $hres->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $h['id'] ?></td>
                                    <td><?= $h['descripcion'] ?></td>
                                    <td><?= $h['responsable'] ?></td>
                                    <td><?= $h['fecha_mejora'] ?></td>
                                    <td><?= $h['prioridad'] ?></td>
                                    <td><?= $h['estado'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-secondary">Sin hallazgos.</div>
                    <?php endif; ?>
                    <!-- Formulario para agregar hallazgo -->
                    <form method="post" action="registrar_hallazgo.php" class="mt-3">
                        <input type="hidden" name="visita_id" value="<?= $v['id'] ?>">
                        <div class="mb-2">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" required></textarea>
                        </div>
                        <div class="mb-2">
                            <label>Responsable</label>
                            <select name="responsable_id" class="form-control" required>
                                <option value="">Seleccione</option>
                                <?php
                                $ures = $conexion->query("SELECT id, nombre FROM usuarios WHERE estado=1");
                                while($u = $ures->fetch_assoc()):
                                ?>
                                <option value="<?= $u['id'] ?>"><?= $u['nombre'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Fecha Mejora</label>
                            <input type="date" name="fecha_mejora" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Prioridad</label>
                            <select name="prioridad" class="form-control">
                                <option value="alta">Alta</option>
                                <option value="media" selected>Media</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Agregar Hallazgo</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include("config/db.php"); ?>

<?php
$visita_id = $_GET['visita_id'] ?? 0;
if (!$visita_id) {
    die("❌ ID de visita no válido.");
}
?>

<h2>Registrar Resultados - Visita #<?php echo $visita_id; ?></h2>
<form method="POST">
    <?php
    $aspectos = $conexion->query("
        SELECT va.id as visita_aspecto_id, a.nombre
        FROM visita_aspectos va
        INNER JOIN aspectos a ON va.aspecto_id=a.id
        WHERE va.visita_id='$visita_id'
    ");

    while ($row = $aspectos->fetch_assoc()) {
        echo "<label>{$row['nombre']}</label><br>";
        echo "<select name='resultado[{$row['visita_aspecto_id']}]'>
                <option value=''>-- Seleccionar --</option>
                <option value='cumple'>Cumple</option>
                <option value='no cumple'>No Cumple</option>
                <option value='parcial'>Parcial</option>
              </select><br>";
        echo "<textarea name='observaciones[{$row['visita_aspecto_id']}]' placeholder='Observaciones'></textarea><br><br>";
    }
    ?>
    <button type="submit" name="guardar">Guardar Resultados</button>
</form>

<?php
if (isset($_POST['guardar'])) {
    foreach ($_POST['resultado'] as $id => $resultado) {
        $obs = $conexion->real_escape_string($_POST['observaciones'][$id] ?? '');
        if ($resultado != "") {
            $conexion->query("UPDATE visita_aspectos SET resultado='$resultado', observaciones='$obs' WHERE id='$id'");
        }
    }
    echo "✅ Resultados guardados correctamente.";
}
?>

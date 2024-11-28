<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>No se ha especificado ningún recordatorio.</p>";
    exit();
}

$id_recordatorio = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_vacuna = $_POST['fecha_vacuna'];
    $tipo_vacuna = $_POST['tipo_vacuna'];

    if (empty($fecha_vacuna) || empty($tipo_vacuna)) {
        echo "<p style='color:red;'>Por favor, llene todos los campos.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE recordatorios SET fecha_vacuna = ?, tipo_vacuna = ? WHERE id = ?");
        $stmt->bind_param("ssi", $fecha_vacuna, $tipo_vacuna, $id_recordatorio);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Recordatorio actualizado exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al actualizar el recordatorio.</p>";
        }

        $stmt->close();
    }
}

$stmt = $conn->prepare("SELECT fecha_vacuna, tipo_vacuna FROM recordatorios WHERE id = ?");
$stmt->bind_param("i", $id_recordatorio);
$stmt->execute();
$stmt->bind_result($fecha_vacuna, $tipo_vacuna);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Recordatorio</title>
</head>
<body>
    <h2>Editar Recordatorio</h2>
    <form method="post" action="">
        <label for="fecha_vacuna">Fecha de la vacuna:</label>
        <input type="date" id="fecha_vacuna" name="fecha_vacuna" value="<?php echo htmlspecialchars($fecha_vacuna); ?>"><br><br>

        <label for="tipo_vacuna">Tipo de Vacuna:</label>
        <input type="text" id="tipo_vacuna" name="tipo_vacuna" value="<?php echo htmlspecialchars($tipo_vacuna); ?>"><br><br>

        <input type="submit" value="Actualizar Recordatorio">
    </form>
    <br>
    <a href="gestionar_recordatorios.php">Volver a Gestionar Recordatorios</a>
</body>
</html>

<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<p style='color:red;'>No se ha especificado ninguna habilidad.</p>";
    exit();
}

$id_habilidad = $_GET['id'];

// Si el formulario es enviado, actualizar los datos de la habilidad
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_truco = $_POST['nombre_truco'];
    $progreso = $_POST['progreso'];
    $dominado = isset($_POST['dominado']) ? 1 : 0;

    if (empty($nombre_truco)) {
        echo "<p style='color:red;'>El nombre del truco no puede estar vacío.</p>";
    } else {
        $stmt = $conn->prepare("UPDATE habilidades SET nombre_truco = ?, progreso = ?, dominado = ? WHERE id = ?");
        $stmt->bind_param("ssii", $nombre_truco, $progreso, $dominado, $id_habilidad);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Habilidad actualizada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al actualizar la habilidad.</p>";
        }

        $stmt->close();
    }
}

// Obtener la información actual de la habilidad para mostrarla en el formulario
$stmt = $conn->prepare("SELECT nombre_truco, progreso, dominado FROM habilidades WHERE id = ?");
$stmt->bind_param("i", $id_habilidad);
$stmt->execute();
$stmt->bind_result($nombre_truco, $progreso, $dominado);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Habilidad</title>
</head>
<body>
    <h2>Editar Habilidad</h2>
    <form method="post" action="">
        <label for="nombre_truco">Nombre del Truco:</label>
        <input type="text" id="nombre_truco" name="nombre_truco" value="<?php echo htmlspecialchars($nombre_truco); ?>" required><br><br>

        <label for="progreso">Notas de Progreso:</label>
        <textarea id="progreso" name="progreso"><?php echo htmlspecialchars($progreso); ?></textarea><br><br>

        <label for="dominado">¿Dominado?</label>
        <input type="checkbox" id="dominado" name="dominado" <?php echo $dominado ? 'checked' : ''; ?>><br><br>

        <input type="submit" value="Actualizar Habilidad">
    </form>
    <br>
    <a href="ver_habilidades.php?id_mascota=<?php echo htmlspecialchars($_GET['id_mascota']); ?>">Volver a Ver Habilidades</a>
</body>
</html>

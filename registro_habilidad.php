<?php
session_start();
include '../db_connection.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mascota = $_POST['id_mascota'];
    $nombre_truco = $_POST['nombre_truco'];
    $progreso = $_POST['progreso'];

    if (empty($id_mascota) || empty($nombre_truco)) {
        echo "<p style='color:red;'>Por favor, llene todos los campos obligatorios.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO habilidades (id_mascota, nombre_truco, progreso) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_mascota, $nombre_truco, $progreso);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Habilidad registrada exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al registrar la habilidad.</p>";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Habilidad</title>
</head>
<body>
    <h2>Registro de Habilidades para Mascotas</h2>
    <form method="post" action="">
        <label for="id_mascota">Mascota:</label>
        <select id="id_mascota" name="id_mascota">
            <?php
            list($nombre, $apellido) = explode(" ", $_SESSION['usuario']);
            $stmt = $conn->prepare("SELECT id, nombre FROM mascotas WHERE nombre_dueno = ? AND apellido_dueno = ?");
            $stmt->bind_param("ss", $nombre, $apellido);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nombre']) . "</option>";
            }

            $stmt->close();
            ?>
        </select><br><br>

        <label for="nombre_truco">Nombre del Truco:</label>
        <input type="text" id="nombre_truco" name="nombre_truco" required><br><br>

        <label for="progreso">Notas de Progreso:</label>
        <textarea id="progreso" name="progreso"></textarea><br><br>

        <input type="submit" value="Registrar Habilidad" href="../menu.php">
    </form>
    <br>
    <a href="../menu.php">Volver al Menú Principal</a>
</body>
</html>

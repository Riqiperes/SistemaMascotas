<?php
session_start();
include '../db_connection.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mascota = $_POST['id_mascota'];
    $fecha_vacuna = $_POST['fecha_vacuna'];
    $tipo_vacuna = $_POST['tipo_vacuna'];

    if (empty($id_mascota) || empty($fecha_vacuna) || empty($tipo_vacuna)) {
        echo "<p style='color:red;'>Por favor, llene todos los campos.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO recordatorios (id_mascota, fecha_vacuna, tipo_vacuna) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_mascota, $fecha_vacuna, $tipo_vacuna);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Recordatorio de vacuna registrado exitosamente.</p>";
            echo "<a href='../menu.php'><button>Volver al Menú Principal</button></a>";
            echo "<a href='recordatorios.php'><button>Registrar Otra Vacuna</button></a>";
        } else {
            echo "<p style='color:red;'>Error al registrar el recordatorio.</p>";
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
    <title>Registro de Vacunas</title>
</head>
<body>
    <h2>Registro de Vacunas para Mascotas</h2>
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

        <label for="fecha_vacuna">Fecha de la vacuna:</label>
        <input type="date" id="fecha_vacuna" name="fecha_vacuna"><br><br>

        <label for="tipo_vacuna">Tipo de Vacuna:</label>
        <input type="text" id="tipo_vacuna" name="tipo_vacuna"><br><br>

        <input type="submit" value="Registrar Vacuna">
    </form>
    <a href="../menu.php"><button>Volver al Menú Principal</button></a>
</body>
</html>

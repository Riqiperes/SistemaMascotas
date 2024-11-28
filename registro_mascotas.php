<?php
session_start();
include 'db_connection.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_mascota = $_POST['nombre_mascota'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];
    list($nombre_dueno, $apellido_dueno) = explode(" ", $_SESSION['usuario']);

    if (empty($nombre_mascota) || empty($especie) || empty($raza) || empty($edad)) {
        echo "<p style='color:red;'>Por favor, llene todos los campos.</p>";
    } else {
        // Verificar si la mascota ya existe para el usuario
        $stmt = $conn->prepare("SELECT * FROM mascotas WHERE nombre = ? AND especie = ? AND nombre_dueno = ? AND apellido_dueno = ?");
        $stmt->bind_param("ssss", $nombre_mascota, $especie, $nombre_dueno, $apellido_dueno);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<p style='color:red;'>La mascota ya está registrada.</p>";
        } else {
            // Insertar la nueva mascota
            $stmt = $conn->prepare("INSERT INTO mascotas (nombre, especie, raza, edad, nombre_dueno, apellido_dueno) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $nombre_mascota, $especie, $raza, $edad, $nombre_dueno, $apellido_dueno);
            
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Mascota registrada exitosamente.</p>";
            } else {
                echo "<p style='color:red;'>Error al registrar la mascota.</p>";
            }
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
    <title>Registro de Mascota</title>
</head>
<body>
    <h2>Registro de Mascota</h2>
    <form method="post" action="">
        <label for="nombre_mascota">Nombre de la Mascota:</label>
        <input type="text" id="nombre_mascota" name="nombre_mascota"><br><br>

        <label for="especie">Especie:</label>
        <input type="text" id="especie" name="especie"><br><br>

        <label for="raza">Raza:</label>
        <input type="text" id="raza" name="raza"><br><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad"><br><br>

        <input type="submit" value="Registrar">
    </form>
    <br>
    <a href="menu.php">Volver al Menú Principal</a>
</body>
</html>

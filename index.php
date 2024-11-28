<?php
session_start();
include 'db_connection.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $contrasena = $_POST['contrasena'];
    
    if (empty($nombre) || empty($apellido) || empty($contrasena)) {
        echo "<p style='color:red;'>Por favor, llene todos los campos.</p>";
    } else {
        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre = ? AND apellido = ? AND contrasena = ?");
        $stmt->bind_param("sss", $nombre, $apellido, $contrasena);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Usuario encontrado, iniciar sesión
            $_SESSION['usuario'] = $nombre . ' ' . $apellido;
            header("Location: menu.php");
            exit();
        } else {
            echo "<p style='color:red;'>Usuario no encontrado o contraseña incorrecta.</p>";
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
    <title>Registro / Inicio de Sesión</title>
</head>
<body>
    <h2>Registro / Inicio de Sesión</h2>
    <form method="post" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre"><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido"><br><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena"><br><br>

        <input type="submit" value="Ingresar">
    </form>
</body>
</html>

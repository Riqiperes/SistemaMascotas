<?php
session_start();
include '../db_connection.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id_mascota = $_GET['id_mascota'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_truco = $_POST['nombre_truco'];
    $progreso = $_POST['progreso'];

    if (empty($nombre_truco)) {
        echo "<p style='color:red;'>El nombre del truco no puede estar vacío.</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO habilidades (id_mascota, nombre_truco, progreso) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_mascota, $nombre_truco, $progreso);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Habilidad registrada exitosamente.</p>";
            echo "<a href='ver_habilidades.php?id_mascota=$id_mascota'><button>Volver a Ver Habilidades</button></a>";
            echo "<a href='registro_habilidad.php?id_mascota=$id_mascota'><button>Registrar Nueva Habilidad</button></a>";
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
        <label for="nombre_truco">Nombre del Truco:</label>
        <input type="text" id="nombre_truco" name="nombre_truco" required><br><br>

        <label for="progreso">Notas de Progreso:</label>
        <textarea id="progreso" name="progreso"></textarea><br><br>

        <input type="submit" value="Registrar Habilidad">
    </form>
    <br>
    <a href="ver_habilidades.php?id_mascota=<?php echo $id_mascota; ?>">Volver a Ver Habilidades</a>
</body>
</html>

<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

list($nombre, $apellido) = explode(" ", $_SESSION['usuario']);
$stmt = $conn->prepare("SELECT * FROM mascotas WHERE nombre_dueno = ? AND apellido_dueno = ?");
$stmt->bind_param("ss", $nombre, $apellido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p>No hay mascotas registradas.</p>";
} else {
    echo "<h2>Mis Mascotas</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['nombre']) . " - " . htmlspecialchars($row['especie']) . "</strong>";
        echo "<br>Raza: " . htmlspecialchars($row['raza']);
        echo "<br>Edad: " . htmlspecialchars($row['edad']);
        echo "<br><a href='./habilidades/ver_habilidades.php?id_mascota=" . $row['id'] . "'>Ver Habilidades</a> | ";
        echo "<a href='./habilidades/registro_habilidad.php?id_mascota=" . $row['id'] . "'>Registrar Nueva Habilidad</a></p><hr>";
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Mascotas</title>
</head>
<body>
    <a href="menu.php"><button>Volver al Menú Principal</button></a>
</body>
</html>
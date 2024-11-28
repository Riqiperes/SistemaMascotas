<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú Principal</title>
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?></h2>
    <ul>
    <li><a href="registro_mascotas.php">Registrar Mascota</a></li>
    <li><a href="ver_mascotas.php">Ver Mascotas</a></li>
    <li><a href="recordatorios/recordatorios.php">Registrar Recordatorio de Vacuna</a></li>
    <li><a href="recordatorios/gestionar_recordatorios.php">Ver y Gestionar Recordatorios</a></li>
</ul>
</body>
</html>

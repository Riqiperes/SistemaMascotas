<?php
session_start();
include '../db_connection.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

list($nombre, $apellido) = explode(" ", $_SESSION['usuario']);
$stmt = $conn->prepare("SELECT r.id, r.fecha_vacuna, r.tipo_vacuna, m.nombre AS mascota_nombre
                        FROM recordatorios r
                        JOIN mascotas m ON r.id_mascota = m.id
                        WHERE m.nombre_dueno = ? AND m.apellido_dueno = ?");
$stmt->bind_param("ss", $nombre, $apellido);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Recordatorios</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function eliminarRecordatorio(id) {
            $.ajax({
                url: 'eliminar_recordatorio.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response === 'success') {
                        $('#recordatorio-' + id).remove();
                    } else {
                        alert('Error al eliminar el recordatorio.');
                    }
                }
            });
        }
    </script>
</head>
<body>
    <h2>Gestionar Recordatorios</h2>
    <?php
    if ($result->num_rows == 0) {
        echo "<p>No hay recordatorios pendientes.</p>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<div id='recordatorio-" . $row['id'] . "'>";
            echo "<p><strong>" . htmlspecialchars($row['mascota_nombre']) . " - " . htmlspecialchars($row['fecha_vacuna']) . " (" . htmlspecialchars($row['tipo_vacuna']) . ")</strong>";
            echo "<br><a href='editar_recordatorio.php?id=" . $row['id'] . "'>Editar</a> | ";
            echo "<a href='#' onclick='eliminarRecordatorio(" . $row['id'] . ")'>Eliminar</a></p>";
            echo "</div><hr>";
        }
    }

    $stmt->close();
    $conn->close();
    ?>
    <a href="../menu.php">Volver al Menú Principal</a>
</body>
</html>

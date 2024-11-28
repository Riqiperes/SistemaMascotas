<?php
session_start();
include '../db_connection.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id_mascota'])) {
    echo "<p style='color:red;'>No se ha especificado ninguna mascota.</p>";
    exit();
}

$id_mascota = $_GET['id_mascota'];

// Verificar si la mascota pertenece al usuario autenticado
list($nombre, $apellido) = explode(" ", $_SESSION['usuario']);
$stmt = $conn->prepare("SELECT * FROM mascotas WHERE id = ? AND nombre_dueno = ? AND apellido_dueno = ?");
$stmt->bind_param("iss", $id_mascota, $nombre, $apellido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p style='color:red;'>No tiene permiso para ver las habilidades de esta mascota.</p>";
    exit();
}

// Obtener las habilidades registradas para la mascota
$stmt = $conn->prepare("SELECT * FROM habilidades WHERE id_mascota = ?");
$stmt->bind_param("i", $id_mascota);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Habilidades</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function eliminarHabilidad(id) {
            $.ajax({
                url: 'eliminar_habilidad.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response === 'success') {
                        $('#habilidad-' + id).remove();
                    } else {
                        alert('Error al eliminar la habilidad.');
                    }
                }
            });
        }
    </script>
</head>
<body>
    <h2>Habilidades de la Mascota</h2>
    <?php
    if ($result->num_rows == 0) {
        echo "<p>No hay habilidades registradas para esta mascota.</p>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<div id='habilidad-" . $row['id'] . "'>";
            echo "<p><strong>Truco:</strong> " . htmlspecialchars($row['nombre_truco']);
            echo "<br><strong>Progreso:</strong> " . htmlspecialchars($row['progreso']);
            echo "<br><strong>Estado:</strong> " . ($row['dominado'] ? "Dominada" : "En progreso");
            echo "<br><a href='editar_habilidad.php?id=" . $row['id'] . "&id_mascota=" . $id_mascota . "'>Editar</a> | ";
            echo "<a href='#' onclick='eliminarHabilidad(" . $row['id'] . ")'>Eliminar</a></p>";
            echo "</div><hr>";
        }
    }

    $stmt->close();
    $conn->close();
    ?>
    <a href="../ver_mascotas.php"><button>Volver a Ver Mascotas</button></a>
    <a href="registro_habilidad.php?id_mascota=<?php echo $id_mascota; ?>"><button>Registrar Nueva Habilidad</button></a>
</body>
</html>

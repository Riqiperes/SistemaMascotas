<?php
include '../db_connection.php';

$mañana = date('Y-m-d', strtotime('+1 day'));

$stmt = $conn->prepare("SELECT r.fecha_vacuna, r.tipo_vacuna, m.nombre AS mascota_nombre, u.nombre AS dueno_nombre, u.apellido AS dueno_apellido
                        FROM recordatorios r
                        JOIN mascotas m ON r.id_mascota = m.id
                        JOIN usuarios u ON m.nombre_dueno = u.nombre AND m.apellido_dueno = u.apellido
                        WHERE r.fecha_vacuna = ? AND r.notificado = FALSE");
$stmt->bind_param("s", $mañana);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "Recordatorio: Mañana tiene una vacuna programada para " . $row['mascota_nombre'] . " (" . $row['tipo_vacuna'] . ").<br>";
    // Aquí podrías enviar un correo o alguna notificación
}

// Marcar los recordatorios como notificados
$update_stmt = $conn->prepare("UPDATE recordatorios SET notificado = TRUE WHERE fecha_vacuna = ? AND notificado = FALSE");
$update_stmt->bind_param("s", $mañana);
$update_stmt->execute();

$stmt->close();
$update_stmt->close();
$conn->close();
?>

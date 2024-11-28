<?php
$servername = "ordinario_agile";
$username = "myuser";
$password = "12345";
$database = "ordinario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

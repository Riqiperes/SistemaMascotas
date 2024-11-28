<?php

$servername = "ordinario_agile";
$username = "myuser";
$password = $_ENV['SECRET']; 
$database = "ordinario";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error); 
    die("Error al conectar con la base de datos."); 
}


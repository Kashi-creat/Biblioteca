<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bibliotecav2";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
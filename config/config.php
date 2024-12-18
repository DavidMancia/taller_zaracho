<?php
// Conexión a la base de datos
$host = "localhost";
$user = "tallerZaracho";
$password = "postgres";
$dbname = "taller_zaracho";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
$servername = "localhost";
$username = "root";
$password = "ROOT";  // Adicione a senha do seu banco de dados aqui
$dbname = "myserieslist";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

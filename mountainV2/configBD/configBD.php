<?php
$servername = "localhost"; // Si es en Clever Cloud, usa el host remoto
$username = "root"; // O el usuario configurado en phpMyAdmin
$password = ""; // Si tienes contraseña en XAMPP, agrégala aquí
$dbname = "inti_cumbres"; // Debe coincidir con el nombre en phpMyAdmin

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    echo "Conexión exitosa";
}
?>

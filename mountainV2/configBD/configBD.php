<?php
// Comentamos la configuración de Clever Cloud
/*
$servername = "bgarvelirt4tpmavpych-mysql.services.clever-cloud.com";
$username = "u8z6exh2a03cekor";
$password = "nxDwvNtb4WgMLTirLFpv";
$dbname = "bgarvelirt4tpmavpych";
*/

// Usamos la configuración local
$servername = "localhost";
$username = "root";
$password = ""; // Si tienes una contraseña configurada en XAMPP, ponla aquí
$dbname = "inti_cumbres";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$servername = "bgarvelirt4tpmavpych-mysql.services.clever-cloud.com";
$username = "u8z6exh2a03cekor";
$password = "nxDwvNtb4WgMLTirLFpv";
$dbname = "bgarvelirt4tpmavpych";

//Si queremos usar localmente

#$servername = "localhost"; // Si es en Clever Cloud, usa el host remoto
#$username = "root"; // O el usuario configurado en phpMyAdmin
#$password = ""; // Si tienes contraseña en XAMPP, agrégala aquí
#$dbname = "inti_cumbres"; // Debe coincidir con el nombre en phpMyAdmin

//Comentar esto es solo para mi 
//$password = "admin2024"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

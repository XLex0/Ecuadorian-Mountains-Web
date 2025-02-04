<?php
$servername = "bgarvelirt4tpmavpych-mysql.services.clever-cloud.com"; // Si es en Clever Cloud, usa el host remoto
$username = "u8z6exh2a03cekor"; // O el usuario configurado en phpMyAdmin
$password = "nxDwvNtb4WgMLTirLFpv"; // Si tienes contraseña en XAMPP, agrégala aquí
$dbname = "bgarvelirt4tpmavpych"; // Debe coincidir con el nombre en phpMyAdmin

//Si queremos usar localmente

#$servername = "localhost"; // Si es en Clever Cloud, usa el host remoto
#$username = "root"; // O el usuario configurado en phpMyAdmin
#$password = ""; // Si tienes contraseña en XAMPP, agrégala aquí
#$dbname = "inti_cumbres"; // Debe coincidir con el nombre en phpMyAdmin

//Comentar esto es solo para mi 
//$password = "admin2024"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    //echo "Conexión exitosa";
}
?>

<?php
$servername = "bgarvelirt4tpmavpych-mysql.services.clever-cloud.com";
$username = "u8z6exh2a03cekor";
$password = "nxDwvNtb4WgMLTirLFpv";
$dbname = "bgarvelirt4tpmavpych";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
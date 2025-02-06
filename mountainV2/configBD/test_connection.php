<?php
include 'configBD.php';

$sql = "SELECT * FROM guias_operadores";
$result = $conn->query($sql);

if ($result) {
    echo "Conexión exitosa. Número de guías encontrados: " . $result->num_rows;
} else {
    echo "Error en la consulta: " . $conn->error;
}
?>
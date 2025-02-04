<?php
include 'configBD.php';


$extraer = $_POST['extraer'];
$id = $_POST['searchText'];

if($extraer == 'descripcion'){
    $sql = "SELECT nombre, longitud, latitud, ubicacion,altura, tipo, descripcion, urlImagenPrincipal FROM montanas WHERE id = $id";
    

}else if($extraer == 'mountain'){
    $sql = "SELECT longitud, latitud, ubicacion,altura, tipo FROM montanas  WHERE id = $id";
}

    $result = $conn->query($sql);    
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo json_encode($data);




?>
<?php
include 'configBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT username, email, password_hash FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $isPasswordCorrect = password_verify($password, $row['password_hash']);
        if ($isPasswordCorrect) {
            echo json_encode($isPasswordCorrect);
        } else {
        echo json_encode($isPasswordCorrect);
        }
    } else {
        echo json_encode($isPasswordCorrect);
    }

    $stmt->close();
}

$conn->close();
?>

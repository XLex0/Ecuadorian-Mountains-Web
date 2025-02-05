<?php
session_start();
session_destroy();  // Destruir toda la sesión
header("Location: ../templates2/login.html");  // Redirigir al login
exit();
?>

<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");  // Redirigir al login si no está logueado
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>encabezado</title>
    <link rel="stylesheet" href="../statics2/css/styles.css">
    <script defer src="../statics2/js/script2.js"></script>
    <script defer src="../statics2/js/mountain.js"></script>
    <script defer src="../statics2/js/comentarios.js"></script>
</head>
<body>
    
    <!-- Menú lateral -->
    <div id="menu-lateral">
        <button id="menu-close">✖</button>
        <ul id="menu-list">
            <li><a href="inicio.html">Inicio</a></li>
            <li><a href="montañas.html">Montañas</a></li>
            <li><a href="rutas.html">Rutas</a></li>
            <li><a href="guias.html">Guías</a></li>
            <li><a href="equipo.html">Equipo</a></li>
            <li><a href="temporadas.html">Temporadas</a></li>
            <li><a href="refugios.html">Refugios</a></li>
        </ul>
    </div>

    <!-- Capa oscura para cerrar el menú -->
    <div id="overlay"></div>

    <!-- Encabezado -->
    <header id="header">
        <button id="menu-btn">☰</button>
        <div id="logo">Inti Cumbres</div>
        <div id="search-container">
            <form>
                <input type="text" id="search" placeholder="Buscar...">    
            </form>
 
        </div>
        
        <a href="login.html">login</a>
    </header>

    <main id="main-content"></main>
</body>
</html>
<?php
session_start();

// Verificar si el usuario está logueado
 if (!isset($_SESSION['username'])) {
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
    <script defer src="../statics2/js/admin.js"></script>
</head>

<body>

    <!-- Menú lateral -->
    <div id="menu-lateral" class="transform transition-all duration-300 ease-in-out">
        <button id="menu-close"
            class="absolute top-4 right-4 text-white hover:bg-emerald-600 rounded-full p-2">✖</button>
        <ul id="menu-list" class="mt-16">
            <li class="mb-4">
                <a href="inicio.html"
                    class="flex items-center px-6 py-3 text-white hover:bg-emerald-600 rounded-lg transition-colors">
                    <span class="mr-3">🏠</span>
                    Inicio
                </a>
            </li>
            <li><a href="MountainsMenu.html">Montañas</a></li>
            <li><a href="guias.html">Guías</a></li>
            <li><a href="equipo.html">Equipo</a></li>
            <li><a href="temporadas.html">Temporadas</a></li>
            <li><a href="refugios.html">Refugios</a></li>

            <?php 
        // Para probar sin necesidad de loguearse, descomenta la línea a continuación
        // if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin') { 
        ?>
            <!-- Para pruebas sin sesión, simplemente muestra el enlace de "Administrar" -->
            <li class=menu-admin><a href="administrar.html">Administrar</a></li>
            <?php 
        // }
        ?>
        </ul>
    </div>

    <!-- Capa oscura para cerrar el menú -->
    <div id="overlay"></div>

    <!-- Encabezado -->
    <header id="header">
        <div class="flex items-center">
            <button id="menu-btn" class="menu-btn">☰</button>
            <div id="logo">Inti Cumbres</div>
        </div>
        <div class="header-controls">
            <div class="search-wrapper">
                <input type="text" id="search" placeholder="Buscar...">
            </div>
            <a href="../configBD/logout.php" class="logout-button">
                <span class="button-icon">➜</span>
                Cerrar sesión
            </a>
        </div>
    </header>

    <div id="menu-lateral">
        <button id="menu-close">✖</button>
        <ul id="menu-list">
            <li><a href="inicio.html">Inicio</a></li>
            <li><a href="MountainsMenu.html">Montañas</a></li>
            <li><a href="guias.html">Guías</a></li>
            <li><a href="equipo.html">Equipo</a></li>
            <li><a href="temporadas.html">Temporadas</a></li>
            <li><a href="refugios.html">Refugios</a></li>
            <li class="menu-admin"><a href="administrar.html">Administrar</a></li>
        </ul>
    </div>

    <main id="main-content"></main>
</body>

</html>
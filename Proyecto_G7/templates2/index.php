<?php
session_start();
// Removemos la redirección forzada al login
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inti Cumbres</title>
    <link rel="stylesheet" href="../statics2/css/styles.css">
    <script defer src="../statics2/js/script2.js"></script>
    <script defer src="../statics2/js/mountain.js"></script>
    <script defer src="../statics2/js/comentarios.js"></script>
    <script defer src="../statics2/js/admin.js"></script>
    <!-- Añadimos FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="menu-lateral" class="transform transition-all duration-300 ease-in-out">
        <button id="menu-close" class="absolute top-4 right-4 text-white hover:bg-emerald-600 rounded-full p-2">
            <i class="fas fa-times"></i>
        </button>
        <ul id="menu-list" class="mt-16">
            <li>
                <a href="inicio.html">
                    <i class="fas fa-home"></i>
                    Inicio
                </a>
            </li>
            <li>
                <a href="MountainsMenu.html">
                    <i class="fas fa-mountain"></i>
                    Montañas
                </a>
            </li>
            <li>
                <a href="guias.html">
                    <i class="fas fa-user-tie"></i>
                    Guías
                </a>
            </li>
            <li>
                <a href="equipo.html">
                    <i class="fas fa-hiking"></i>
                    Equipo
                </a>
            </li>
            <li>
                <a href="temporadas.html">
                    <i class="fas fa-calendar-alt"></i>
                    Temporadas
                </a>
            </li>
            <li>
                <a href="refugios.html">
                    <i class="fas fa-hotel"></i>
                    Refugios
                </a>
            </li>
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin'): ?>
            <li class="menu-admin">
                <a href="administrar.html">
                    <i class="fas fa-cog"></i>
                    Administrar
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>

    <div id="overlay"></div>

    <header id="header">
        <div class="header-left">
            <button id="menu-btn" class="menu-btn">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo-container">
                <img src="../statics2/img/IntiCumbresLogo.png" alt="Inti Cumbres" class="logo-img">
            </div>
        </div>
        <div class="header-controls">
            <div class="search-wrapper">
                <input type="text" id="search" placeholder="Buscar...">
                <i class="fas fa-search search-icon"></i>
            </div>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="../configBD/logout.php" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar sesión
                </a>
            <?php else: ?>
                <a href="login.html" class="login-button">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar sesión
                </a>
            <?php endif; ?>
        </div>
    </header>

    <main id="main-content"></main>

    <!-- Cursor personalizado -->
    <div class="custom-cursor"></div>
</body>
</html>
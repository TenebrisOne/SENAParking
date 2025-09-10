<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}


?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">

    <!-- Enlace a Bootstrap (si lo estás utilizando) -->
    <link href="./frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <!-- Favicon (ícono en la pestaña del navegador) -->
    <link rel="icon" type="x-icon" href="/SENAParking/frontend/public/images/favicon.ico">
    <title>HEADER | SENAParking</title>

    <style>
        /* ====== Estilos del Header y Menú ====== */

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #4CAF50;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left {
            display: flex;
            align-items: center;
            margin-left: 15px;
            text-align: left;
        }

        .logo {
            width: 60px;
            margin-right: 15px;
        }

        .app-name h1 {
            margin: 0;
            font-size: 24px;
        }

        .app-name p {
            margin: 0;
            font-size: 14px;
            color: #e8e8e8;
        }

        .right {
            text-align: right;
        }

        .right p {
            margin: 5px 0;
        }

        .right span {
            font-weight: bold;
        }

        /* ====== Menú Desplegable de Usuario ====== */


        body {
            margin: 0;
            padding: 0;
            height: 100vh;

            align-items: center;
            justify-content: center;
            background: #f0f0f0;
        }

        .menu-container {
            position: relative;
        }

        .menu-button {
            width: 60px;
            height: 60px;
            background-color: #007832;
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            z-index: 2;
            position: relative;
        }

        .menu {
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            background-color: #50e5f985;
            border-radius: 50%;
            overflow: hidden;
            transition: all 0.4s ease;
            z-index: 1;
            border: 1px solid #1f6691;
        }

        .menu-container:hover .menu {
            width: 180px;
            height: 150px;
            right: 0;
            transform: translateX(-20px);
            /* Despliega hacia la izquierda */
            border-radius: 20px;
        }

        .menu-items {
            opacity: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px 0 0 20px;
            transition: opacity 0.4s ease 0.3s;
        }

        .menu-container:hover .menu-items {
            opacity: 1;
        }

        .menu-items button {
            background-color: transparent;
            border: none;
            color: rgb(0, 0, 0);
            font-weight: bold; 
            padding: 8px 0;
            font-size: 16px;
            cursor: pointer;
            text-align: left;
            width: 100%;
        }

        .menu-items button:hover {
            background-color: rgba(230, 248, 248, 0.267);
        }
    </style>


<body>
    <script src="./frontend/public/js/header_functions.js"></script>
    <!-- Header Reutilizable -->
    <header class="header">
        <div class="left">
            <img src="../public/images/logo.png" alt="Logo" class="logo">
            <div class="app-name">
                <h1>SenaParking</h1>
                <p>DeveloperSOLUTIONS801</p>
            </div>
        </div>

        <div class="menu-container">
                <button 
        class="menu-button avatar" 
        title="<?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : ''; ?>"
        aria-label="Menú del usuario <?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : ''; ?>"
    ><?php
        if (isset($_SESSION['nombre']) && !empty($_SESSION['nombre'])) {
            echo strtoupper(substr($_SESSION['nombre'], 0, 1));
        } else {
            echo '';
        }
        ?></button>
            <div class="menu" id="menu">
                <div class="menu-items">
                    <button onclick="accion1()">Ayuda</button>
                    <a href="../views/informacion.php"><button type="button">Información</button></a>
                    <form action="../../../SENAParking/logout.php" method="get">
                        <button type="sumbit">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>

    </header>



</body>

</html>
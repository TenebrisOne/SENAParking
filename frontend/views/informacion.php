<?php
session_start();

if (!isset($_SESSION['rol'])) {
    header("location: ../login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Dinámica - Sistema de Parqueadero</title>
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link href="/frontend/public/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="sityles_views.css">

    <style>
        /* Fondo plano sobrio */
        body {
            background: #e3f0e3; /* Verde oscuro sobrio */
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Contenedor principal con color plano */
        .info-container {
            max-width: 900px;
            margin: 60px auto;
            background: linear-gradient(135deg, rgba(80, 229, 249, 0.3), rgba(255, 255, 255, 0.9), rgba(253, 195, 0, 0.3) );
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 10px 35px rgba(113, 39, 122, 0.2);
            position: relative;
            overflow: hidden;
            animation: pulse 2s ease-in-out infinite alternate;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            100% { transform: scale(1.02); }
        }

        .info-header {
            background: #39A900;
            color: #ececec; /* Color oscuro para mayor contraste */
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            font-size: 2.2rem;
            font-weight: 800; /* Mayor peso para legibilidad */
            margin-bottom: 40px;
            position: relative;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4); /* Sombra para resaltar */
        }

        .info-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shine 2.5s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        .info-card {
            background: linear-gradient(135deg, #ffffff, rgba(57, 169, 0, 0.2));
            border: 3px solid #FDC300;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(80, 229, 249, 0.3);
            border-color: #39A900;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(113, 39, 122, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .info-card:hover::before {
            opacity: 1;
        }

        .info-card h3 {
            color: #2e2f30; /* Color más oscuro para contraste */
            font-weight: 800; /* Mayor peso para legibilidad */
            margin-bottom: 20px;
            font-size: 1.8rem;
            position: relative;
            display: inline-block;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3); /* Sombra para resaltar */
        }

        .info-card h3::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #50E5F9, #FDC300);
            transition: width 0.4s ease;
        }

        .info-card:hover h3::after {
            width: 100%;
        }

        .info-card p {
            color: #1a1a1a; /* Color más oscuro para mayor contraste */
            font-size: 1.2rem;
            line-height: 1.8;
            font-weight: 500; /* Peso medio para mejor legibilidad */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Sombra sutil */
        }

        .contact-section {
            background: linear-gradient(135deg, rgba(113, 39, 122, 0.9), rgba(155, 71, 157, 0.7));
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            color: #ffffff;
            margin-top: 30px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            position: relative;
            animation: bounceIn 1.5s ease-in-out;
        }

        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }

        .contact-section a {
            color: #FDC300;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .contact-section a:hover {
            color: #39A900;
            transform: scale(1.05);
        }

        .contact-section h3, .contact-section p {
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4); /* Sombra para mayor contraste */
            font-weight: 600; /* Peso para legibilidad */
        }

        .back-arrow {
            position: absolute;
            top: 25px;
            left: 25px;
            font-size: 45px;
            cursor: pointer;
            color: #FDC300;
            transition: color 0.3s ease, transform 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Sombra para contraste */
        }

        .back-arrow:hover {
            color: #50E5F9;
            transform: rotate(-10deg) scale(1.15);
        }

        .feature-list li {
            margin-bottom: 12px;
            position: relative;
            padding-left: 30px;
            font-size: 1.2rem;
            color: #1a1a1a; /* Color oscuro para contraste */
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Sombra sutil */
        }

        .feature-list li::before {
            content: '🚗';
            position: absolute;
            left: 0;
            color: #39A900;
            font-size: 1.3rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Sombra para contraste */
        }

        .highlight {
            color: #00304D; /* Color más oscuro y vibrante para resaltar */
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Sombra para contraste */
        }

        @media (max-width: 768px) {
            .info-container {
                width: 95%;
                padding: 25px;
            }

            .info-header {
                font-size: 1.8rem;
                padding: 20px;
            }

            .info-card {
                padding: 20px;
                border-width: 2px;
            }

            .info-card h3 {
                font-size: 1.5rem;
            }

            .back-arrow {
                font-size: 35px;
                left: 15px;
                top: 15px;
            }

            .contact-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Flecha de retroceso -->
        <a href="./../login.php" class="back-arrow">&#8678;</a>

        <!-- Contenedor principal -->
        <div class="info-container">
            <div class="info-header">
                ¡Explora el Sistema de Parqueadero!
            </div>

            <!-- Card: Propósito del Sistema -->
            <div class="info-card">
                <h3>Nuestra Misión</h3>
                <p>
                    Revolucionamos la gestión de estacionamientos con una plataforma <span class="highlight">moderna, colorida y eficiente</span>. Facilitamos el control de vehículos, accesos y reportes, asegurando una experiencia <span class="highlight">fluida y segura</span> para todos los usuarios.
                </p>
            </div>

            <!-- Card: Características Principales -->
            <div class="info-card">
                <h3>¿Por Qué Elegirnos?</h3>
                <ul class="feature-list">
                    <li>Gestión <span class="highlight">intuitiva</span> de usuarios y vehículos.</li>
                    <li>Control de accesos y salidas en <span class="highlight">tiempo real</span>.</li>
                    <li>Reportes <span class="highlight">vibrantes</span> y personalizables.</li>
                    <li>Diseño <span class="highlight">responsivo</span> para cualquier dispositivo.</li>
                    <li><span class="highlight">Seguridad</span> avanzada con validación de datos.</li>
                </ul>
            </div>

            <!-- Card: Contacto -->
            <div class="contact-section">
                <h3>¡Hablemos!</h3>
                <p>
                    ¿Listo para llevar tu estacionamiento al siguiente nivel? Contáctanos:<br>
                    <a href="mailto:soporte@parqueadero.com">soporte@parqueadero.com</a><br>
                    Teléfono: <span class="highlight">+123 456 7890</span><br>
                    <a>v1.0-alfa</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
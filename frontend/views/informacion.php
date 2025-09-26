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
    <title>Información | SENAParking</title>
    <meta name="author" content="AdsoDeveloperSolutions801">
    <meta name="course" content="ADSO 2873801">
    <!-- Favicon que aparece en la pestaña del navegador -->
    <link rel="icon" type="x-icon" href="../public/images/favicon.ico">
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>

        :root {
            --color-primary: #39A900;
            --color-primary-dark: #007832;
            --color-secondary: #00304D;
            --color-accent: #71277A;
            --color-highlight: #50E5F9;
            --color-warning: #FDC300;
            --color-black: #000000;
        }
        
        body {
            background: linear-gradient(135deg, rgba(0, 48, 77, 0.8) 0%, rgba(113, 39, 122, 0.6) 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            color: white;
            overflow-x: hidden;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        
        .header-section {
            text-align: center;
            padding: 2rem 0;
            position: relative;
        }
        
        .logo-badge {
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
            width: 95px;
            height: 95px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 0 30px rgba(57, 169, 0, 0.4);
            animation: pulse 2s infinite;
        }
        
        .logo-badge i {
            font-size: 2rem;
            color: white;
        }
        
        .version-tag {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-block;
            margin-top: 0.5rem;
        }
        
        .description-text {
            line-height: 1.6;
            font-size: 1.1rem;
            margin: 1.5rem 0;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .developer-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .developer-card:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.05);
            border-color: rgba(80, 229, 249, 0.3);
        }
        
        .developer-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-highlight), var(--color-warning));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            color: var(--color-black);
            font-weight: bold;
        }
        
        .developer-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }
        
        .license-section {
            background: linear-gradient(135deg, rgba(57, 169, 0, 0.2), rgba(0, 120, 50, 0.2));
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .contact-link {
            color: var(--color-highlight);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .contact-link:hover {
            color: var(--color-warning);
            text-decoration: underline;
            transform: translateX(5px);
        }
        
        .floating-element {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 30px rgba(57, 169, 0, 0.4); }
            50% { box-shadow: 0 0 50px rgba(57, 169, 0, 0.6); }
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease forwards;
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stagger-item:nth-child(1) { animation-delay: 0.1s; }
        .stagger-item:nth-child(2) { animation-delay: 0.2s; }
        .stagger-item:nth-child(3) { animation-delay: 0.3s; }
        .stagger-item:nth-child(4) { animation-delay: 0.4s; }
        .stagger-item:nth-child(5) { animation-delay: 0.5s; }
        
        .copyright-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        
        .team-section {
            margin: 3rem 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--color-primary), var(--color-highlight));
            border-radius: 2px;
        }
        
        .mac-style {
            border-radius: 20px;
            overflow: hidden;
        }
        
        .mac-header {
            background: rgba(0, 0, 0, 0.2);
            padding: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .mac-button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .red-button { background: #ff5f57; }
        .yellow-button { background: #ffbd2e; }
        .green-button { background: #28c940; }
        
        .content-wrapper {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="glass-card mac-style fade-in">
            <div class="mac-header">
                <div class="mac-button red-button"></div>
                <div class="mac-button yellow-button"></div>
                <div class="mac-button green-button"></div>
            </div>
            <div class="content-wrapper">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="logo-badge floating-element">
                        <img src="../../frontend/public/images/logo.png" alt="Logo SENAParking"
        style="max-width: 100%; height: auto; display: block; margin: 0 auto; margin-left: 10px;">
                    </div>
                    <h1 class="display-5 fw-bold">SENAParking</h1>
                    <div class="version-tag">v1.1.3-alfa</div>
                    <p class="text-muted">Sistema de Gestión de Parqueaderos del SENA</p>
                </div>

                <!-- Description -->
                <div class="text-center description-text fade-in" style="animation-delay: 0.3s;">
                    SENAParking es una solución desarrollada para optimizar y controlar la gestión del parqueadero del Instituto Educativo SENA. Permite registrar vehículos, gestionar accesos y generar reportes de uso de manera eficiente y segura.
                </div>

                <!-- Team Section -->
                <div class="team-section">
                    <h2 class="section-title">Desarrollado por</h2>
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-lg-4 stagger-item fade-in">
                            <div class="developer-card">
                                <div class="developer-avatar">CR</div>
                                <div class="developer-name">Cristian Ruiz</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 stagger-item fade-in">
                            <div class="developer-card">
                                <div class="developer-avatar">NB</div>
                                <div class="developer-name">Nicol Barragán</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 stagger-item fade-in">
                            <div class="developer-card">
                                <div class="developer-avatar">FC</div>
                                <div class="developer-name">Felipe Colmenares</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 stagger-item fade-in">
                            <div class="developer-card">
                                <div class="developer-avatar">JH</div>
                                <div class="developer-name">Juan Harrington</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 stagger-item fade-in">
                            <div class="developer-card">
                                <div class="developer-avatar">JV</div>
                                <div class="developer-name">José Villaveces</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 stagger-item fade-in">
                            <div class="developer-card">
                                <div class="developer-avatar">
                                    <i class="fas fa-code"></i>
                                </div>
                                <div class="developer-name">DeveloperSOLUTIONS801</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- License Section -->
                <div class="license-section fade-in" style="animation-delay: 0.8s;">
                    <h3 class="text-center mb-3">Licencia</h3>
                    <p class="text-center mb-3">Licencia de Propiedad Privada</p>
                    <p class="text-center mb-3">© 2025 DeveloperSOLUTIONS801. Todos los derechos reservados.</p>
                    <p class="text-center mb-3">
                        Este software, incluyendo su código fuente, documentación y diseño, es propiedad exclusiva de DeveloperSOLUTIONS801. 
                        Su uso está limitado a personas o entidades autorizadas. Cualquier reproducción, modificación o distribución no autorizada está prohibida.
                    </p>
                    <div class="text-center">
                        <a href="../../LICENSE" class="contact-link">
                            <i class="fas fa-file-contract me-2"></i>Ver Licencia Completa
                        </a>
                        <p class="mt-2">
                            o contacta a: 
                            <a href="soporte-info@developersolutions801.com" class="contact-link">
                                soporte-info@developersolutions801.com
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center copyright-text fade-in" style="animation-delay: 1s;">
                    Diseñado con ❤️ para el SENA • Sistema de Gestión de Parqueaderos
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add staggered animation to all fade-in elements
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach((element, index) => {
                if (!element.style.animationDelay) {
                    element.style.animationDelay = (index * 0.1) + 's';
                }
            });
        });
    </script>
</body>
</html>
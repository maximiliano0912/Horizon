<?php require_once __DIR__ . '/config/helpers.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Horizon | Tu próxima aventura </title>

    <!-- Favicon -->
    <link rel="icon" href="img/Logo2.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="header">

        <div class="header-container">

            <!-- LOGO -->
            <a href="index.php" class="logo">

                <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

            </a>


            <!-- NAV -->
            <nav class="nav">

            <a href="index.php" class="active">
            Inicio
            </a>

            <a href="catalogo.php">
            Paquetes
            </a>

            <a href="#nosotros">
                Nosotros
            </a>

            <a href="#contacto">
                Contacto
            </a>

            </nav>


            <!-- ACCIONES -->
            <div class="header-actions">
<!-- Modo -->
                <button
                    class="theme-button"
                    id="themeToggle"
                    aria-label="Cambiar modo">

                    <i class="fa-solid fa-moon"></i>

                </button>


                <!-- Login -->
                <a href="login.php" class="login-button">
                    Iniciar sesión
                </a>


                <!-- Registro -->
                <a href="registro.php" class="register-button">
                    Crear cuenta
                </a>

            </div>

        </div>

    </header>



    <!-- =====================================================
         HERO
    ====================================================== -->

    <main>

        <section class="hero">

            <!-- Imagen de fondo -->
            <img
            src="img/Header.png"
            alt="Paisaje turístico"
            class="hero-background hero-day"
            >

            <img
            src="img/Header Oscuro.jpg"
            alt="Paisaje turístico nocturno"
            class="hero-background hero-night"
            >

<div class="hero-overlay"></div>


            <div class="hero-container">

                <div class="hero-content">

                    <span class="hero-label">
                        DESCUBRÍ EL MUNDO
                    </span>


                    <h1>
                        Paquetes turísticos
                        <br>
                        para vivir
                        <span>experiencias</span>
                        <br>
                        inolvidables
                    </h1>


                    <p>
                        Hoteles, vuelos, traslados y mucho más
                        en un solo lugar.
                        Armá tu viaje, nosotros nos encargamos
                        del resto.
                    </p>


                    <div class="hero-decoration">
                        Más que un viaje,
                        <br>
                        una historia para contar...
                    </div>

                    <div class="hero-actions">
                        <a href="catalogo.php" class="primary-button hero-cta">
                            Ver paquetes
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="#nosotros" class="hero-secondary">
                            Conocenos
                        </a>
                    </div>

                </div> <!-- /hero-content -->

            </div> <!-- /hero-container -->

        </section>






       <!-- =====================================================
     PAQUETES DESTACADOS
====================================================== -->

<section class="destinations section" id="paquetes-destacados">

<div class="section-container">

    <div class="section-header">

        <div>

            <span class="section-label">
                PAQUETES DESTACADOS
            </span>

            <h2>
                Elegí tu próxima aventura
            </h2>

        </div>


        <a href="catalogo.php" class="view-all">

            Ver todos los paquetes

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>


    <!-- PAQUETES -->

    <div class="destination-grid">


        <!-- PAQUETE 1 -->

        <article class="package-card">

            <div class="package-top">

                <span class="package-type">
                    NACIONAL
                </span>

                <span class="package-code">
                    HZN-001
                </span>

            </div>


            <div class="package-content">

                <span class="package-location">
                    <i class="fa-solid fa-location-dot"></i>
                    Río Negro, Argentina
                </span>

                <h3>
                    Bariloche
                </h3>

                <p class="package-description">
                    Naturaleza, montañas y experiencias
                    inolvidables en la Patagonia.
                </p>


                <div class="package-details">

                    <span>
                        <i class="fa-regular fa-calendar"></i>
                        7 días / 6 noches
                    </span>

                    <span>
                        <i class="fa-solid fa-hotel"></i>
                        Hotel 4 estrellas
                    </span>

                </div>


                <div class="package-bottom">

                    <div>

                        <small>
                            Desde
                        </small>

                        <strong>
                            $680.000
                        </strong>

                        <small>
                            por persona
                        </small>

                    </div>


                    <a href="catalogo.php" class="package-button">

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        </article>



        <!-- PAQUETE 2 -->

        <article class="package-card">

            <div class="package-top">

                <span class="package-type">
                    NACIONAL
                </span>

                <span class="package-code">
                    HZN-002
                </span>

            </div>


            <div class="package-content">

                <span class="package-location">
                    <i class="fa-solid fa-location-dot"></i>
                    Mendoza, Argentina
                </span>

                <h3>
                    Mendoza
                </h3>

                <p class="package-description">
                    Vinos, montañas y gastronomía
                    en el corazón de Cuyo.
                </p>


                <div class="package-details">

                    <span>
                        <i class="fa-regular fa-calendar"></i>
                        5 días / 4 noches
                    </span>

                    <span>
                        <i class="fa-solid fa-hotel"></i>
                        Hotel 4 estrellas
                    </span>

                </div>


                <div class="package-bottom">

                    <div>

                        <small>
                            Desde
                        </small>

                        <strong>
                            $520.000
                        </strong>

                        <small>
                            por persona
                        </small>

                    </div>


                    <a href="catalogo.php" class="package-button">

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        </article>



        <!-- PAQUETE 3 -->

        <article class="package-card">

            <div class="package-top">

                <span class="package-type international">
                    INTERNACIONAL
                </span>

                <span class="package-code">
                    HZN-003
                </span>

            </div>


            <div class="package-content">

                <span class="package-location">
                    <i class="fa-solid fa-location-dot"></i>
                    Río de Janeiro, Brasil
                </span>

                <h3>
                    Río de Janeiro
                </h3>

                <p class="package-description">
                    Playas, cultura y paisajes únicos
                    frente al océano Atlántico.
                </p>


                <div class="package-details">

                    <span>
                        <i class="fa-regular fa-calendar"></i>
                        8 días / 7 noches
                    </span>

                    <span>
                        <i class="fa-solid fa-plane"></i>
                        Vuelo incluido
                    </span>

                </div>


                <div class="package-bottom">

                    <div>

                        <small>
                            Desde
                        </small>

                        <strong>
                            USD 1.190
                        </strong>

                        <small>
                            por persona
                        </small>

                    </div>


                    <a href="catalogo.php" class="package-button">

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        </article>



        <!-- PAQUETE 4 -->

        <article class="package-card">

            <div class="package-top">

                <span class="package-type international">
                    INTERNACIONAL
                </span>

                <span class="package-code">
                    HZN-004
                </span>

            </div>


            <div class="package-content">

                <span class="package-location">
                    <i class="fa-solid fa-location-dot"></i>
                    Madrid, España
                </span>

                <h3>
                    Madrid
                </h3>

                <p class="package-description">
                    Historia, cultura y gastronomía
                    en el corazón de España.
                </p>


                <div class="package-details">

                    <span>
                        <i class="fa-regular fa-calendar"></i>
                        10 días / 9 noches
                    </span>

                    <span>
                        <i class="fa-solid fa-plane"></i>
                        Vuelo incluido
                    </span>

                </div>


                <div class="package-bottom">

                    <div>

                        <small>
                            Desde
                        </small>

                        <strong>
                            EUR 1.490
                        </strong>

                        <small>
                            por persona
                        </small>

                    </div>


                    <a href="catalogo.php" class="package-button">

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>

            </div>

        </article>


    </div>

</div>

</section>


        <!-- =====================================================
             OFERTA ESPECIAL
        ====================================================== -->

        <section class="special-offer" id="paquetes">

            <div class="offer-container">

                <div class="offer-image">

                    <img
                        src="img/Avion.jpeg"
                        alt="Avión viajando al atardecer">

                </div>


                <div class="offer-content">

                    <span class="offer-label">
                        OFERTAS ESPECIALES
                    </span>

                    <h2>
                        Hacé realidad
                        tus sueños
                    </h2>

                    <p>
                        Hasta <strong>40% de descuento</strong>
                        en paquetes seleccionados.
                    </p>

                    <a href="#" class="primary-button">

                        Ver ofertas

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>


                <div class="offer-decoration">

                    El momento
                    <br>
                    es ahora.

                    <i class="fa-solid fa-plane"></i>

                </div>

                

            </div>

            <div class="offers-grid">

    <article class="offer-card">
        <span class="offer-badge">-20%</span>
        <h3>Mendoza</h3>
        <p>5 días / 4 noches · Hotel 4 estrellas</p>
        <div class="offer-prices">
            <del>$520.000</del>
            <strong>$416.000</strong>
        </div>
        <a href="catalogo.php" class="offer-link">Ver paquete <i class="fa-solid fa-arrow-right"></i></a>
    </article>

    <article class="offer-card">
        <span class="offer-badge">-15%</span>
        <h3>Bariloche</h3>
        <p>7 días / 6 noches · Hotel 4 estrellas</p>
        <div class="offer-prices">
            <del>$680.000</del>
            <strong>$578.000</strong>
        </div>
        <a href="catalogo.php" class="offer-link">Ver paquete <i class="fa-solid fa-arrow-right"></i></a>
    </article>

    <article class="offer-card">
        <span class="offer-badge">-40%</span>
        <h3>Río de Janeiro</h3>
        <p>8 días / 7 noches · Vuelo incluido</p>
        <div class="offer-prices">
            <del>USD 1.190</del>
            <strong>USD 714</strong>
        </div>
        <a href="catalogo.php" class="offer-link">Ver paquete <i class="fa-solid fa-arrow-right"></i></a>
    </article>

</div>

        </section>



        <!-- =====================================================
             POR QUÉ ELEGIRNOS
        ====================================================== -->

        <section class="about" id="nosotros">
    <div class="about-container">

        <div class="about-intro">
            <span class="section-label">SOBRE NOSOTROS</span>
            <h2>Viajamos con vos, antes, durante y después</h2>
            <p>
                En Horizon somos una agencia de viajes que nació con una idea
                simple: que organizar unas vacaciones sea tan lindo como vivirlas.
                Armamos paquetes nacionales e internacionales con hotel, vuelos
                y traslados, para que vos solo te ocupes de disfrutar.
            </p>
            <p>
                Trabajamos con aerolíneas, hoteles y operadores de confianza, y
                te acompañamos en cada paso: desde elegir el destino hasta que
                volvés a casa con las valijas llenas de historias.
            </p>
        </div>

        <div class="about-stats">
            <div class="about-stat">
                <strong>+5.000</strong>
                <span>viajeros felices</span>
            </div>
            <div class="about-stat">
                <strong>+50</strong>
                <span>destinos disponibles</span>
            </div>
            <div class="about-stat">
                <strong>24/7</strong>
                <span>soporte durante tu viaje</span>
            </div>
        </div>

    </div>

    <div class="benefits-container">
        <div class="benefit">
            <div class="benefit-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <div>
                <h3>Pagos seguros</h3>
                <p>Tus datos siempre protegidos</p>
            </div>
        </div>
        <div class="benefit">
            <div class="benefit-icon"><i class="fa-solid fa-headset"></i></div>
            <div>
                <h3>Atención personalizada</h3>
                <p>Siempre que lo necesites</p>
            </div>
        </div>
        <div class="benefit">
            <div class="benefit-icon"><i class="fa-solid fa-headphones"></i></div>
            <div>
                <h3>Soporte 24/7</h3>
                <p>En todo momento</p>
            </div>
        </div>
        <div class="benefit">
            <div class="benefit-icon"><i class="fa-solid fa-star"></i></div>
            <div>
                <h3>Mejores alianzas</h3>
                <p>Trabajamos con grandes empresas</p>
            </div>
        </div>
    </div>
</section>

    </main>



    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer class="footer" id="contacto">

        <div class="footer-container">


            <!-- Logo -->

            <div class="footer-brand">

                <a href="#" class="logo">

                    <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

                </a>

            </div>



            <!-- Links -->

            <div class="footer-links">

                <a href="#">
                    Inicio
                </a>

                <a href="#destinos">
                    Destinos
                </a>

                <a href="#paquetes">
                    Paquetes
                </a>

                <a href="#nosotros">
                    Nosotros
                </a>

                <a href="#contacto">
                    Contacto
                </a>

            </div>



            <!-- Redes -->

            <div class="social-links">

                <a href="#" aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <a href="#" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>

                <a href="#" aria-label="X">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>

                <a href="#" aria-label="YouTube">
                    <i class="fa-brands fa-youtube"></i>
                </a>

            </div>

        </div>


        <div class="footer-bottom">

            <p>
                © 2026 HORIZON. Todos los derechos reservados.
            </p>

            <div class="footer-legal">

                <a href="#">
                    Términos y condiciones
                </a>

                <a href="#">
                    Política de privacidad
                </a>

            </div>

        </div>

    </footer>



    <!-- =====================================================
         SCRIPT
    ====================================================== -->

    <script src="js/script.js"></script>

    <?php script_usuario(); ?>

    <script src="js/auth.js"></script>
    
</body>

</html>
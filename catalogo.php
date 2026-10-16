<?php require_once __DIR__ . '/config/helpers.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Horizon | Paquetes turísticos</title>


    <!-- Favicon -->

    <link
        rel="icon"
        href="img/Logo2.png"
    >


    <!-- Google Fonts -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >


    <!-- CSS principal -->

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body class="catalog-page">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="header catalog-header">

        <div class="header-container">


            <!-- LOGO -->

            <a href="index.php" class="logo">

                <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

            </a>



            <!-- NAV -->

            <nav class="nav">

                <a href="index.php">
                    Inicio
                </a>

                <a
                    href="catalogo.php"
                    class="active"
                >
                    Paquetes
                </a>

                <a href="index.php#nosotros">
                    Nosotros
                </a>

                <a href="index.php#contacto">
                    Contacto
                </a>

            </nav>



            <!-- ACCIONES -->

           <div class="header-actions">

    <!-- CARRITO -->
    <a
        href="carrito.php"
        class="cart-button"
        aria-label="Ver carrito"
    >

        <i class="fa-solid fa-cart-shopping"></i>

        <span
            id="cartCount"
            class="cart-count"
        >
            0
        </span>

    </a>


    <!-- MODO OSCURO / CLARO -->
    <button
        class="theme-button"
        id="themeToggle"
        aria-label="Cambiar modo"
    >

        <i class="fa-solid fa-moon"></i>

    </button>



    <a href="login.php" class="login-button">Iniciar sesión</a>

    <a href="registro.php" class="register-button">Crear cuenta</a>

</div>

        </div>

    </header>



    <!-- =====================================================
         CONTENIDO
    ====================================================== -->

    <main class="catalog-main">


        <!-- ENCABEZADO -->

        <section class="catalog-hero">

            <div class="catalog-container">

                <span class="section-label">
                    HORIZON TRAVEL
                </span>

                <h1>
                    Encontrá tu próximo
                    <span>destino</span>
                </h1>

                <p>
                    Explorá nuestros paquetes turísticos
                    nacionales e internacionales y elegí
                    la experiencia perfecta para vos.
                </p>

            </div>

        </section>



        <!-- =================================================
             CATÁLOGO
        ================================================== -->

        <section class="catalog-section">

            <div class="catalog-container">


                <!-- FILTROS -->

                <div class="catalog-filters">


                    <!-- BUSCADOR -->

                    <div class="catalog-search">

                        <i class="fa-solid fa-magnifying-glass"></i>

                        <input
                            type="text"
                            id="packageSearch"
                            placeholder="Buscar por destino, país o paquete..."
                        >

                    </div>



                    <!-- TIPO -->

                    <div class="catalog-filter">

                        <label for="packageType">
                            Tipo de viaje
                        </label>

                        <select id="packageType">

                            <option value="todos">
                                Todos los paquetes
                            </option>

                            <option value="nacional">
                                Nacionales
                            </option>

                            <option value="internacional">
                                Internacionales
                            </option>

                        </select>

                    </div>



                    <!-- DURACIÓN -->

                    <div class="catalog-filter">

                        <label for="packageDuration">
                            Duración
                        </label>

                        <select id="packageDuration">

                            <option value="todos">
                                Cualquier duración
                            </option>

                            <option value="corto">
                                Hasta 5 días
                            </option>

                            <option value="medio">
                                6 a 8 días
                            </option>

                            <option value="largo">
                                9 días o más
                            </option>

                        </select>

                    </div>

                </div>



                <!-- RESULTADOS -->

                <div class="catalog-results-header">

                    <div>

                        <span class="section-label">
                            PAQUETES DISPONIBLES
                        </span>

                        <h2>
                            Encontrá tu próxima aventura
                        </h2>

                    </div>


                    <span
                        id="packageCounter"
                        class="package-counter"
                    >
                        12 paquetes
                    </span>

                </div>



                <!-- GRID -->

                <div
                    class="catalog-grid"
                    id="packageGrid"
                >


                    <!-- =================================================
                         PAQUETE 1
                    ================================================== -->

                    <article
                        class="catalog-package"
                        data-type="nacional"
                        data-duration="medio"
                        data-search="bariloche patagonia argentina nieve montañas"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type">
                                NACIONAL
                            </span>

                            <span class="package-code">
                                HZN-001
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Río Negro, Argentina

                            </span>


                            <h3>
                                Bariloche
                            </h3>


                            <p>
                                Naturaleza, montañas y
                                experiencias inolvidables
                                en la Patagonia.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    7 días / 6 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                                <span>
                                    <i class="fa-solid fa-bus"></i>
                                    Traslados incluidos
                                </span>

                            </div>


                            <div class="catalog-package-footer">

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


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 2 -->

                    <article
                        class="catalog-package"
                        data-type="nacional"
                        data-duration="corto"
                        data-search="mendoza argentina vino montaña gastronomia"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type">
                                NACIONAL
                            </span>

                            <span class="package-code">
                                HZN-002
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Mendoza, Argentina

                            </span>


                            <h3>
                                Mendoza
                            </h3>


                            <p>
                                Vinos, montañas y gastronomía
                                en el corazón de Cuyo.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    5 días / 4 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                                <span>
                                    <i class="fa-solid fa-wine-glass"></i>
                                    Degustación incluida
                                </span>

                            </div>


                            <div class="catalog-package-footer">

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


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 3 -->

                    <article
                        class="catalog-package"
                        data-type="nacional"
                        data-duration="corto"
                        data-search="iguazu misiones argentina cataratas naturaleza"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type">
                                NACIONAL
                            </span>

                            <span class="package-code">
                                HZN-003
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Misiones, Argentina

                            </span>


                            <h3>
                                Cataratas del Iguazú
                            </h3>


                            <p>
                                Una experiencia natural única
                                entre selva y cascadas.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    4 días / 3 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 3 estrellas
                                </span>

                                <span>
                                    <i class="fa-solid fa-map"></i>
                                    Excursión incluida
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        $410.000
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 4 -->

                    <article
                        class="catalog-package"
                        data-type="nacional"
                        data-duration="medio"
                        data-search="ushuaia tierra del fuego argentina fin del mundo"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type">
                                NACIONAL
                            </span>

                            <span class="package-code">
                                HZN-004
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Tierra del Fuego, Argentina

                            </span>


                            <h3>
                                Ushuaia
                            </h3>


                            <p>
                                Descubrí la ciudad del fin
                                del mundo y sus paisajes.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    7 días / 6 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                                <span>
                                    <i class="fa-solid fa-mountain"></i>
                                    Excursiones
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        $720.000
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 5 -->

                    <article
                        class="catalog-package"
                        data-type="nacional"
                        data-duration="largo"
                        data-search="salta jujuy argentina norte quebrada humahuaca"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type">
                                NACIONAL
                            </span>

                            <span class="package-code">
                                HZN-005
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Salta, Argentina

                            </span>


                            <h3>
                                Salta y Jujuy
                            </h3>


                            <p>
                                Cultura, paisajes y tradición
                                en el norte argentino.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    10 días / 9 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 3 estrellas
                                </span>

                                <span>
                                    <i class="fa-solid fa-bus"></i>
                                    Traslados
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        $590.000
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 6 -->

                    <article
                        class="catalog-package"
                        data-type="nacional"
                        data-duration="medio"
                        data-search="mar del plata buenos aires argentina playa costa"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type">
                                NACIONAL
                            </span>

                            <span class="package-code">
                                HZN-006
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Buenos Aires, Argentina

                            </span>


                            <h3>
                                Mar del Plata
                            </h3>


                            <p>
                                Playa, gastronomía y descanso
                                en la costa argentina.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    6 días / 5 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                                <span>
                                    <i class="fa-solid fa-umbrella-beach"></i>
                                    Playa
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        $390.000
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 7 -->

                    <article
                        class="catalog-package"
                        data-type="internacional"
                        data-duration="largo"
                        data-search="rio janeiro brasil internacional playa copacabana"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type international">
                                INTERNACIONAL
                            </span>

                            <span class="package-code">
                                HZN-007
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Río de Janeiro, Brasil

                            </span>


                            <h3>
                                Río de Janeiro
                            </h3>


                            <p>
                                Playas, cultura y paisajes únicos
                                frente al océano Atlántico.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    8 días / 7 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-plane"></i>
                                    Vuelo incluido
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                            </div>


                            <div class="catalog-package-footer">

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


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 8 -->

                    <article
                        class="catalog-package"
                        data-type="internacional"
                        data-duration="largo"
                        data-search="madrid españa europa internacional cultura"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type international">
                                INTERNACIONAL
                            </span>

                            <span class="package-code">
                                HZN-008
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Madrid, España

                            </span>


                            <h3>
                                Madrid
                            </h3>


                            <p>
                                Historia, cultura y gastronomía
                                en el corazón de España.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    10 días / 9 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-plane"></i>
                                    Vuelo incluido
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                            </div>


                            <div class="catalog-package-footer">

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


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 9 -->

                    <article
                        class="catalog-package"
                        data-type="internacional"
                        data-duration="medio"
                        data-search="cancun mexico internacional playa caribe"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type international">
                                INTERNACIONAL
                            </span>

                            <span class="package-code">
                                HZN-009
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Quintana Roo, México

                            </span>


                            <h3>
                                Cancún
                            </h3>


                            <p>
                                Playas del Caribe y una experiencia
                                ideal para descansar.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    7 días / 6 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-plane"></i>
                                    Vuelo incluido
                                </span>

                                <span>
                                    <i class="fa-solid fa-umbrella-beach"></i>
                                    All inclusive
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        USD 1.099
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 10 -->

                    <article
                        class="catalog-package"
                        data-type="internacional"
                        data-duration="medio"
                        data-search="paris francia europa internacional torre eiffel"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type international">
                                INTERNACIONAL
                            </span>

                            <span class="package-code">
                                HZN-010
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                París, Francia

                            </span>


                            <h3>
                                París
                            </h3>


                            <p>
                                Arte, historia y gastronomía
                                en la capital francesa.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    7 días / 6 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-plane"></i>
                                    Vuelo incluido
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        EUR 1.390
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 11 -->

                    <article
                        class="catalog-package"
                        data-type="internacional"
                        data-duration="largo"
                        data-search="nueva york estados unidos usa internacional manhattan"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type international">
                                INTERNACIONAL
                            </span>

                            <span class="package-code">
                                HZN-011
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Nueva York, Estados Unidos

                            </span>


                            <h3>
                                Nueva York
                            </h3>


                            <p>
                                Descubrí Manhattan y los lugares
                                más emblemáticos de la ciudad.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    9 días / 8 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-plane"></i>
                                    Vuelo incluido
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 3 estrellas
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        USD 1.249
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>



                    <!-- PAQUETE 12 -->

                    <article
                        class="catalog-package"
                        data-type="internacional"
                        data-duration="largo"
                        data-search="roma italia europa internacional historia coliseo"
                    >

                        <div class="catalog-package-header">

                            <span class="package-type international">
                                INTERNACIONAL
                            </span>

                            <span class="package-code">
                                HZN-012
                            </span>

                        </div>


                        <div class="catalog-package-body">

                            <span class="package-location">

                                <i class="fa-solid fa-location-dot"></i>

                                Roma, Italia

                            </span>


                            <h3>
                                Roma
                            </h3>


                            <p>
                                Historia, arquitectura y cultura
                                en una de las ciudades más famosas.
                            </p>


                            <div class="catalog-package-info">

                                <span>
                                    <i class="fa-regular fa-calendar"></i>
                                    9 días / 8 noches
                                </span>

                                <span>
                                    <i class="fa-solid fa-plane"></i>
                                    Vuelo incluido
                                </span>

                                <span>
                                    <i class="fa-solid fa-hotel"></i>
                                    Hotel 4 estrellas
                                </span>

                            </div>


                            <div class="catalog-package-footer">

                                <div>

                                    <small>
                                        Desde
                                    </small>

                                    <strong>
                                        EUR 1.320
                                    </strong>

                                    <small>
                                        por persona
                                    </small>

                                </div>


                                <button class="catalog-add">

                                    <i class="fa-solid fa-cart-plus"></i>

                                    Agregar

                                </button>

                            </div>

                        </div>

                    </article>


                </div>



                <!-- SIN RESULTADOS -->

                <div
                    id="noResults"
                    class="no-results"
                    style="display: none;"
                >

                    <i class="fa-solid fa-plane-circle-exclamation"></i>

                    <h3>
                        No encontramos paquetes
                    </h3>

                    <p>
                        Probá cambiando el destino o los filtros.
                    </p>

                </div>

            </div>

        </section>

    </main>



    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer
        class="footer"
        id="contacto"
    >

        <div class="footer-container">

            <div class="footer-brand">

                <a
                    href="index.php"
                    class="logo"
                >

                    <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

                </a>

            </div>


            <div class="footer-links">

                <a href="index.php">
                    Inicio
                </a>

                <a href="catalogo.php">
                    Paquetes
                </a>

                <a href="index.php#nosotros">
                    Nosotros
                </a>

                <a href="index.php#contacto">
                    Contacto
                </a>

            </div>


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



    <!-- JS PRINCIPAL -->

    <script src="js/script.js"></script>

    <!-- JS DEL CATÁLOGO -->

    <script src="js/catalogo.js"></script>

    <script src="js/carrito.js"></script>

    <?php script_usuario(); ?>
    <script src="js/auth.js"></script>


</body>

</html>
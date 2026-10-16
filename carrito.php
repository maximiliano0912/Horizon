<?php require __DIR__ . "/config/helpers.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Horizon | Mi carrito</title>


    <!-- GOOGLE FONTS -->

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


    <!-- FONT AWESOME -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    >


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body class="catalog-page cart-page">


    <!-- =====================================================
         HEADER
    ====================================================== -->

    <header class="header catalog-header">

        <div class="header-container">


            <!-- LOGO -->

            <a
                href="index.php"
                class="logo"
            >

                <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

            </a>


            <!-- NAV -->

            <nav class="nav">

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


                <!-- TEMA -->

                <button
                    class="theme-button"
                    id="themeToggle"
                    aria-label="Cambiar modo"
                >

                    <i class="fa-solid fa-moon"></i>

                </button>


                <a
                    href="login.php"
                    class="login-button"
                >
                    Iniciar sesión
                </a>


                <a
                    href="registro.php"
                    class="register-button"
                >
                    Crear cuenta
                </a>

            </div>

        </div>

    </header>



    <!-- =====================================================
         CONTENIDO
    ====================================================== -->

    <main class="cart-main">

        <div class="cart-container">


            <!-- ENCABEZADO -->

            <div class="cart-header">

                <a href="catalogo.php" class="back-button back-button-page">
                    <i class="fa-solid fa-arrow-left"></i> Seguir explorando paquetes
                </a>

                <span class="section-label">
                    HORIZON TRAVEL
                </span>

                <h1>
                    Tu carrito
                </h1>

                <p>
                    Elegí cómo viajás, con cuántas personas y la fecha de salida de cada paquete.
                </p>

            </div>


            <!-- CONTENIDO DINÁMICO -->

            <div id="cartContent">

            </div>


        </div>

    </main>



    <!-- =====================================================
         JS
    ====================================================== -->

    <script src="js/script.js"></script>

    <script src="js/carrito.js"></script>


    <?php script_usuario(); ?>
    <script src="js/auth.js"></script>

    <script>

        const METODOS = <?= json_encode(METODOS_VIAJE) ?>;
        const EXTRAS = <?= json_encode(EXTRAS_VIAJE) ?>;
        const MAX_PERSONAS = <?= (int)MAX_PERSONAS ?>;
        const TASAS = { USD: <?= (int)TASA_USD ?>, EUR: <?= (int)TASA_EUR ?> };


        /* =====================================================
           UTILIDADES
        ====================================================== */

        function esc(texto) {
            const d = document.createElement("div");
            d.textContent = texto == null ? "" : String(texto);
            return d.innerHTML;
        }

        function ars(n) {
            return "$" + Math.round(n).toLocaleString("es-AR");
        }

        function fechaMinima() {
            const d = new Date();
            d.setDate(d.getDate() + 1);
            const mm = String(d.getMonth() + 1).padStart(2, "0");
            const dd = String(d.getDate()).padStart(2, "0");
            return d.getFullYear() + "-" + mm + "-" + dd;
        }

        function esInternacional(p) {
            const t = (p.precioTexto || "").toUpperCase();
            return t.startsWith("USD") || t.startsWith("EUR");
        }

        function precioBaseARS(p) {
            const t = (p.precioTexto || "").toUpperCase();
            if (t.startsWith("USD")) return p.precio * TASAS.USD;
            if (t.startsWith("EUR")) return p.precio * TASAS.EUR;
            return p.precio;
        }

        /* Completa las opciones que le falten a cada paquete */
        function normalizar(p) {
            p.personas = Math.min(MAX_PERSONAS, Math.max(1, parseInt(p.personas) || 1));
            if (!METODOS[p.metodo] || (esInternacional(p) && METODOS[p.metodo].solo_nac)) p.metodo = "avion";
            p.fecha = p.fecha || "";
            p.extras = Array.isArray(p.extras) ? p.extras.filter(e => EXTRAS[e]) : [];
            p.detalles = Array.isArray(p.detalles) ? p.detalles : [];
            return p;
        }

        function precioPersona(p) {
            let v = precioBaseARS(p) * METODOS[p.metodo].mult;
            p.extras.forEach(e => { v += EXTRAS[e].precio; });
            return v;
        }

        function subtotal(p) {
            return precioPersona(p) * p.personas;
        }

        function leerCarrito() {
            return obtenerCarrito().map(normalizar);
        }


        /* =====================================================
           MOSTRAR CARRITO
        ====================================================== */

        function mostrarCarrito() {

            const carrito = leerCarrito();
            const contenedor = document.getElementById("cartContent");

            actualizarContadorCarrito();


            /* CARRITO VACÍO */

            if (carrito.length === 0) {

                contenedor.innerHTML = `
                    <div class="empty-cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <h2>Tu carrito está vacío</h2>
                        <p>Todavía no agregaste ningún paquete.</p>
                        <a href="catalogo.php">
                            <i class="fa-solid fa-compass"></i>
                            Explorar paquetes
                        </a>
                    </div>
                `;

                return;

            }


            const total = carrito.reduce((suma, p) => suma + subtotal(p), 0);
            const personasTotal = carrito.reduce((suma, p) => suma + p.personas, 0);
            const minFecha = fechaMinima();


            /* PAQUETES */

            const productosHTML = carrito.map((p, i) => {

                const metodosHTML = Object.entries(METODOS)
                    .filter(([k, m]) => !(esInternacional(p) && m.solo_nac))
                    .map(([k, m]) => `<option value="${k}" ${p.metodo === k ? "selected" : ""}>${esc(m.nombre)}</option>`)
                    .join("");

                const extrasHTML = Object.entries(EXTRAS).map(([k, e]) => `
                    <label class="cart-extra">
                        <input type="checkbox" ${p.extras.includes(k) ? "checked" : ""}
                               onchange="cambiarExtra(${i}, '${k}', this.checked)">
                        <span>${esc(e.nombre)}</span>
                        <small>+ ${ars(e.precio)} p/p</small>
                    </label>
                `).join("");

                return `
                <article class="cart-item" id="item-${i}">

                    <div class="cart-item-top">
                        <div class="cart-item-title">
                            <span class="cart-item-code">${esc(p.codigo)}</span>
                            <h3>${esc(p.nombre)}</h3>
                            <span class="cart-item-location">
                                <i class="fa-solid fa-location-dot"></i>
                                ${esc(p.ubicacion)}
                            </span>
                        </div>
                    </div>

                    <div class="cart-item-details">
                        ${p.detalles.map(d => `<span><i class="fa-solid fa-check"></i> ${esc(d)}</span>`).join("")}
                    </div>


                    <div class="cart-options">

                        <div class="cart-option">
                            <label>Personas que viajan</label>
                            <div class="cart-stepper">
                                <button type="button" onclick="cambiarPersonas(${i}, -1)" aria-label="Menos personas">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <strong>${p.personas}</strong>
                                <button type="button" onclick="cambiarPersonas(${i}, 1)" aria-label="Más personas">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="cart-option">
                            <label for="metodo-${i}">Método de viaje</label>
                            <select id="metodo-${i}" onchange="cambiarMetodo(${i}, this.value)">
                                ${metodosHTML}
                            </select>
                        </div>

                        <div class="cart-option">
                            <label for="fecha-${i}">Fecha de salida</label>
                            <input type="date" id="fecha-${i}" min="${minFecha}" value="${esc(p.fecha)}"
                                   onchange="cambiarFecha(${i}, this.value)">
                        </div>

                    </div>

                    <div class="cart-extras">
                        <span class="cart-extras-title">Extras opcionales</span>
                        ${extrasHTML}
                    </div>


                    <div class="cart-item-bottom">

                        <div class="cart-item-price">
                            <small>${ars(precioPersona(p))} por persona × ${p.personas}</small>
                            <strong>${ars(subtotal(p))}</strong>
                        </div>

                        <button class="remove-cart-item" onclick="eliminarDelCarrito(${i})">
                            <i class="fa-solid fa-trash"></i>
                            Quitar
                        </button>

                    </div>

                </article>`;

            }).join("");


            /* RESUMEN */

            const resumenFilas = carrito.map(p => `
                <div class="cart-summary-row">
                    <span>${esc(p.nombre)} <small>(${p.personas} pers.)</small></span>
                    <span>${ars(subtotal(p))}</span>
                </div>
            `).join("");

            contenedor.innerHTML = `

                <div class="cart-layout">

                    <div class="cart-items">
                        ${productosHTML}
                    </div>

                    <aside class="cart-summary">

                        <h2>Resumen</h2>

                        ${resumenFilas}

                        <div class="cart-summary-row">
                            <span>Paquetes / personas</span>
                            <span>${carrito.length} / ${personasTotal}</span>
                        </div>

                        <div class="cart-summary-total">
                            <span>Total</span>
                            <strong>${ars(total)}</strong>
                        </div>

                        <p id="cartError" class="cart-error" role="alert"></p>

                        <button class="cart-checkout-button" onclick="continuarCompra()">
                            Continuar compra
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        <a href="catalogo.php" class="cart-back-link">
                            <i class="fa-solid fa-arrow-left"></i> Seguir explorando
                        </a>

                        <button class="clear-cart-button" onclick="vaciarCarrito()">
                            Vaciar carrito
                        </button>

                    </aside>

                </div>
            `;

        }


        /* =====================================================
           CAMBIOS EN CADA PAQUETE
        ====================================================== */

        function modificar(indice, fn, redibujar = true) {
            const carrito = leerCarrito();
            if (!carrito[indice]) return;
            fn(carrito[indice]);
            guardarCarrito(carrito);
            if (redibujar) mostrarCarrito();
        }

        function cambiarPersonas(i, delta) {
            modificar(i, p => { p.personas = Math.min(MAX_PERSONAS, Math.max(1, p.personas + delta)); });
        }

        function cambiarMetodo(i, metodo) {
            modificar(i, p => { p.metodo = metodo; });
        }

        function cambiarFecha(i, fecha) {
            modificar(i, p => { p.fecha = fecha; }, false);
        }

        function cambiarExtra(i, extra, activo) {
            modificar(i, p => {
                p.extras = p.extras.filter(e => e !== extra);
                if (activo) p.extras.push(extra);
            });
        }


        /* =====================================================
           ELIMINAR / VACIAR
        ====================================================== */

        function eliminarDelCarrito(indice) {
            const carrito = obtenerCarrito();
            carrito.splice(indice, 1);
            guardarCarrito(carrito);
            mostrarCarrito();
        }

        function vaciarCarrito() {
            if (!confirm("¿Seguro que querés vaciar el carrito?")) return;
            localStorage.removeItem("horizonCarrito");
            mostrarCarrito();
        }


        /* =====================================================
           CONTINUAR COMPRA
        ====================================================== */

        function errorCarrito(texto, indice) {
            const e = document.getElementById("cartError");
            if (e) e.textContent = texto;
            if (indice != null) {
                const el = document.getElementById("item-" + indice);
                if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }

        async function continuarCompra() {

            const carrito = leerCarrito();
            if (carrito.length === 0) return;

            const sinFecha = carrito.findIndex(p => !p.fecha);
            if (sinFecha !== -1) {
                errorCarrito("Elegí la fecha de salida de «" + carrito[sinFecha].nombre + "».", sinFecha);
                return;
            }

            const items = carrito.map(p => ({
                codigo: p.codigo,
                personas: p.personas,
                metodo: p.metodo,
                fecha: p.fecha,
                extras: p.extras
            }));

            try {
                const r = await fetch("checkout.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF": "<?= csrf_token() ?>" },
                    body: JSON.stringify({ items })
                });
                const d = await r.json();
                if (d.login) { window.location.href = "login.php"; return; }
                if (!d.ok) { errorCarrito(d.error || "No se pudo registrar la compra."); return; }
                localStorage.removeItem("horizonCarrito");
                window.location.href = d.redirect;
            } catch (e) {
                errorCarrito("Error de conexión. Intentá de nuevo.");
            }

        }


        /* =====================================================
           INICIO
        ====================================================== */

        document.addEventListener("DOMContentLoaded", mostrarCarrito);

    </script>


</body>

</html>
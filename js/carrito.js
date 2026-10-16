/* =========================================================
   HORIZON - CARRITO
========================================================= */


/* =========================================================
   OBTENER CARRITO
========================================================= */

function obtenerCarrito() {

    const carrito = localStorage.getItem("horizonCarrito");

    if (!carrito) {

        return [];

    }

    try {

        return JSON.parse(carrito);

    } catch (error) {

        console.error("Error al leer el carrito:", error);

        return [];

    }

}


/* =========================================================
   GUARDAR CARRITO
========================================================= */

function guardarCarrito(carrito) {

    localStorage.setItem(
        "horizonCarrito",
        JSON.stringify(carrito)
    );

}


/* =========================================================
   ACTUALIZAR CONTADOR
========================================================= */

function actualizarContadorCarrito() {

    const carrito = obtenerCarrito();

    const contador = document.getElementById("cartCount");

    if (!contador) return;

    contador.textContent = carrito.length;

}


/* =========================================================
   OBTENER DATOS DE UN PAQUETE
========================================================= */

function obtenerDatosPaquete(card) {

    const codigo =
        card.querySelector(".package-code")?.textContent.trim() || "";

    const nombre =
        card.querySelector("h3")?.textContent.trim() || "";

    const ubicacion =
        card.querySelector(".package-location")?.textContent.trim() || "";

    const descripcion =
        card.querySelector("p")?.textContent.trim() || "";

    const precioTexto =
        card.querySelector(".catalog-package-footer strong")?.textContent.trim() || "";

    const precioNumerico =
        parseFloat(
            precioTexto
                .replace(/[^\d.,]/g, "")
                .replace(/\./g, "")
                .replace(",", ".")
        ) || 0;


    const detalles =
        [...card.querySelectorAll(".catalog-package-info span")]
            .map(elemento => elemento.textContent.trim());


    return {

        codigo: codigo,

        nombre: nombre,

        ubicacion: ubicacion,

        descripcion: descripcion,

        precio: precioNumerico,

        precioTexto: precioTexto,

        detalles: detalles

    };

}


/* =========================================================
   AGREGAR PAQUETE
========================================================= */

function agregarAlCarrito(paquete, boton) {

    const carrito = obtenerCarrito();


    /* COMPROBAR SI YA EXISTE */

    const existe = carrito.some(
        item => item.codigo === paquete.codigo
    );


    if (existe) {

        mostrarMensajeCarrito(
            "Este paquete ya está en tu carrito."
        );

        return;

    }


    carrito.push(paquete);

    guardarCarrito(carrito);

    actualizarContadorCarrito();


    /* CAMBIAR BOTÓN */

    if (boton) {

        const textoOriginal = boton.innerHTML;

        boton.innerHTML =
            '<i class="fa-solid fa-check"></i> Agregado';

        boton.classList.add("added");


        setTimeout(() => {

            boton.innerHTML = textoOriginal;

            boton.classList.remove("added");

        }, 1500);

    }


    mostrarMensajeCarrito(
        "Paquete agregado al carrito."
    );

}


/* =========================================================
   MENSAJE
========================================================= */

function mostrarMensajeCarrito(mensaje) {

    let notificacion =
        document.getElementById("cartNotification");


    if (!notificacion) {

        notificacion =
            document.createElement("div");

        notificacion.id =
            "cartNotification";

        notificacion.className =
            "cart-notification";

        document.body.appendChild(notificacion);

    }


    notificacion.innerHTML = `
        <i class="fa-solid fa-circle-check"></i>
        <span>${mensaje}</span>
    `;


    notificacion.classList.add("show");


    clearTimeout(
        window.cartNotificationTimeout
    );


    window.cartNotificationTimeout =
        setTimeout(() => {

            notificacion.classList.remove("show");

        }, 2200);

}


/* =========================================================
   EVENTOS DEL CATÁLOGO
========================================================= */

function configurarBotonesCatalogo() {

    const botones =
        document.querySelectorAll(".catalog-add");


    botones.forEach(boton => {

        boton.addEventListener("click", () => {

            const card =
                boton.closest(".catalog-package");


            if (!card) return;


            const paquete =
                obtenerDatosPaquete(card);


            agregarAlCarrito(
                paquete,
                boton
            );

        });

    });

}


/* =========================================================
   INICIALIZACIÓN
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    () => {

        actualizarContadorCarrito();

        configurarBotonesCatalogo();

    }
);
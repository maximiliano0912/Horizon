/* =========================================================
   NEXO TRAVEL
   SCRIPT.JS
========================================================= */


/* =========================================================
   ELEMENTOS
========================================================= */

const body = document.body;

const themeToggle = document.getElementById("themeToggle");

const searchButton = document.querySelector(".search-button");

const destinationInput = document.querySelector(
    '.search-info input[placeholder="¿A dónde querés ir?"]'
);

const dateInput = document.querySelector(
    '.search-info input[placeholder="Seleccioná tus fechas"]'
);

const peopleInput = document.querySelector(
    '.search-info input[placeholder="2 adultos"]'
);

const categories = document.querySelectorAll(".category");

const destinationCards = document.querySelectorAll(
    ".destination-card"
);

const arrowButtons = document.querySelectorAll(
    ".arrow-button"
);


/* =========================================================
   MODO AMANECER / ANOCHECER
========================================================= */

function updateThemeIcon() {

    if (!themeToggle) return;

    const icon = themeToggle.querySelector("i");

    if (!icon) return;

    if (body.classList.contains("dark-mode")) {

        icon.classList.remove("fa-moon");

        icon.classList.add("fa-sun");

        themeToggle.setAttribute(
            "aria-label",
            "Activar modo amanecer"
        );

        themeToggle.setAttribute(
            "title",
            "Modo amanecer"
        );

    } else {

        icon.classList.remove("fa-sun");

        icon.classList.add("fa-moon");

        themeToggle.setAttribute(
            "aria-label",
            "Activar modo anochecer"
        );

        themeToggle.setAttribute(
            "title",
            "Modo anochecer"
        );
    }
}


/* =========================================================
   CARGAR TEMA GUARDADO
========================================================= */

const savedTheme = localStorage.getItem("nexoTheme");

if (savedTheme === "dark") {

    body.classList.add("dark-mode");

}

updateThemeIcon();


/* =========================================================
   CAMBIAR TEMA
========================================================= */

if (themeToggle) {

    themeToggle.addEventListener("click", function () {

        body.classList.toggle("dark-mode");

        const isDark =
            body.classList.contains("dark-mode");

        localStorage.setItem(
            "nexoTheme",
            isDark ? "dark" : "light"
        );

        updateThemeIcon();

    });

}


/* =========================================================
   BUSCADOR
========================================================= */

if (searchButton) {

    searchButton.addEventListener("click", function () {

        const destination =
            destinationInput.value.trim();

        const dates =
            dateInput.value.trim();

        const people =
            peopleInput.value.trim();


        if (destination === "") {

            showMessage(
                "Elegí un destino para comenzar tu búsqueda."
            );

            destinationInput.focus();

            return;
        }


        console.log("Búsqueda realizada:");

        console.log({
            destino: destination,
            fechas: dates,
            personas: people
        });


        showMessage(
            `Buscando viajes a ${destination}...`
        );

    });

}


/* =========================================================
   MENSAJE DEL SISTEMA
========================================================= */

function showMessage(message) {

    let notification =
        document.querySelector(".travel-notification");


    if (!notification) {

        notification =
            document.createElement("div");

        notification.className =
            "travel-notification";

        document.body.appendChild(notification);

    }


    notification.textContent = message;

    notification.classList.add("show");


    setTimeout(function () {

        notification.classList.remove("show");

    }, 3000);

}


/* =========================================================
   CATEGORÍAS
========================================================= */

categories.forEach(function (category) {

    category.addEventListener("click", function (event) {

        event.preventDefault();


        categories.forEach(function (item) {

            item.classList.remove("active");

        });


        category.classList.add("active");


        const categoryName =
            category.querySelector("span").textContent;


        showMessage(
            `Explorando ${categoryName.toLowerCase()}`
        );

    });

});


/* =========================================================
   TARJETAS DE DESTINOS
========================================================= */

destinationCards.forEach(function (card) {

    card.addEventListener("click", function () {

        const destination =
            card.querySelector("h3").textContent;


        const country =
            card.querySelector(".country").textContent;


        console.log(
            `Destino seleccionado: ${destination}, ${country}`
        );


        showMessage(
            `Explorando paquetes de ${destination}`
        );

    });

});


/* =========================================================
   BOTONES DE DESTINOS
========================================================= */

arrowButtons.forEach(function (button) {

    button.addEventListener("click", function (event) {

        event.stopPropagation();


        const card =
            button.closest(".destination-card");


        if (!card) return;


        const destination =
            card.querySelector("h3").textContent;


        showMessage(
            `Preparando paquetes para ${destination}...`
        );

    });

});


/* =========================================================
   INPUT DE DESTINO
========================================================= */

if (destinationInput) {

    destinationInput.addEventListener(
        "keydown",
        function (event) {

            if (event.key === "Enter") {

                searchButton.click();

            }

        }
    );

}


/* =========================================================
   ANIMACIÓN AL HACER SCROLL
========================================================= */

const animatedElements = document.querySelectorAll(
    ".destination-card, .benefit, .offer-container"
);


const observer = new IntersectionObserver(
    function (entries) {

        entries.forEach(function (entry) {

            if (entry.isIntersecting) {

                entry.target.classList.add(
                    "visible"
                );

            }

        });

    },
    {
        threshold: 0.15
    }
);


animatedElements.forEach(function (element) {

    observer.observe(element);

});


/* =========================================================
   HEADER AL HACER SCROLL
========================================================= */

const header =
    document.querySelector(".header");


window.addEventListener("scroll", function () {

    if (!header) return;


    if (window.scrollY > 50) {

        header.classList.add("header-scrolled");

    } else {

        header.classList.remove("header-scrolled");

    }

});


/* =========================================================
   LINKS DEL MENÚ
========================================================= */

const navLinks =
    document.querySelectorAll(".nav a");


navLinks.forEach(function (link) {

    link.addEventListener("click", function () {

        navLinks.forEach(function (item) {

            item.classList.remove("active");

        });


        link.classList.add("active");

    });

});


/*Imagen de hero */

const heroBackground =
    document.querySelector(".hero-background");

/* =========================================================
   AÑO AUTOMÁTICO DEL FOOTER
========================================================= */

const footerYear =
    document.querySelector(".footer-bottom p");


if (footerYear) {

    const currentYear =
        new Date().getFullYear();


    footerYear.innerHTML =
        `© ${currentYear} Nexo Travel. Todos los derechos reservados.`;

}


/* =========================================================
   MENSAJE INICIAL EN CONSOLA
========================================================= */

console.log(
    "Nexo Travel cargado correctamente."
);

console.log(
    "Sistema preparado para búsqueda de paquetes turísticos."
);
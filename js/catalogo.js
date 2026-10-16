document.addEventListener("DOMContentLoaded", () => {

    const searchInput =
        document.getElementById("packageSearch");

    const typeFilter =
        document.getElementById("packageType");

    const durationFilter =
        document.getElementById("packageDuration");

    const packages =
        document.querySelectorAll(".catalog-package");

    const counter =
        document.getElementById("packageCounter");

    const noResults =
        document.getElementById("noResults");


    function filterPackages() {

        const search =
            searchInput.value
                .toLowerCase()
                .trim();

        const type =
            typeFilter.value;

        const duration =
            durationFilter.value;


        let visiblePackages = 0;


        packages.forEach(packageCard => {

            const packageType =
                packageCard.dataset.type;

            const packageDuration =
                packageCard.dataset.duration;

            const packageSearch =
                packageCard.dataset.search;


            const matchesSearch =
                packageSearch.includes(search);

            const matchesType =
                type === "todos" ||
                packageType === type;

            const matchesDuration =
                duration === "todos" ||
                packageDuration === duration;


            if (
                matchesSearch &&
                matchesType &&
                matchesDuration
            ) {

                packageCard.style.display = "";

                visiblePackages++;

            } else {

                packageCard.style.display = "none";

            }

        });


        counter.textContent =
            `${visiblePackages} ${
                visiblePackages === 1
                    ? "paquete"
                    : "paquetes"
            }`;


        if (visiblePackages === 0) {

            noResults.style.display = "block";

        } else {

            noResults.style.display = "none";

        }

    }


    searchInput.addEventListener(
        "input",
        filterPackages
    );


    typeFilter.addEventListener(
        "change",
        filterPackages
    );


    durationFilter.addEventListener(
        "change",
        filterPackages
    );


    /*
        BOTONES AGREGAR

        Por ahora solamente muestran
        una confirmación.

        Más adelante se conectarán
        con el carrito y la base de datos.
    */

    const addButtons =
        document.querySelectorAll(".catalog-add");


    addButtons.forEach(button => {

        button.addEventListener("click", () => {

            const originalText =
                button.innerHTML;


            button.innerHTML =
                '<i class="fa-solid fa-check"></i> Agregado';


            button.style.background =
                "#168a55";


            setTimeout(() => {

                button.innerHTML =
                    originalText;

                button.style.background =
                    "";

            }, 1500);

        });

    });

});
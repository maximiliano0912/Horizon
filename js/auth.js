document.addEventListener("DOMContentLoaded", () => {

    actualizarUsuarioHorizon();

});


// =========================================================
// OBTENER USUARIO ACTUAL
// =========================================================

function obtenerUsuarioHorizon() {
    return window.HORIZON_USUARIO || null;
}



// =========================================================
// ACTUALIZAR HEADER
// =========================================================

function actualizarUsuarioHorizon() {

    const usuario =
        obtenerUsuarioHorizon();


    // Buscar botones existentes
    const loginButton =
        document.querySelector(".login-button");

    const registerButton =
        document.querySelector(".register-button");


    // Si no hay botones de header, no hacer nada
    if (!loginButton && !registerButton) {
        return;
    }


    // ==========================================
    // USUARIO LOGUEADO
    // ==========================================

    if (usuario) {


        // Ocultar login
        if (loginButton) {
            loginButton.style.display = "none";
        }


        // Ocultar registro
        if (registerButton) {
            registerButton.style.display = "none";
        }


        // Evitar duplicar usuario
        if (
            document.getElementById(
                "horizonUserMenu"
            )
        ) {
            return;
        }


        // Crear botón de usuario
        const userMenu =
            document.createElement("div");


        userMenu.id =
            "horizonUserMenu";


        userMenu.className =
            "horizon-user-menu";


        userMenu.innerHTML = `

            <button
                type="button"
                class="horizon-user-button"
                id="horizonUserButton">

                <i class="fa-regular fa-circle-user"></i>

                <span>
                    ${escapeHTML(usuario.usuario || usuario.nombre)}
                </span>

                <i class="fa-solid fa-chevron-down"></i>

            </button>


            <div
                class="horizon-user-dropdown"
                id="horizonUserDropdown">

                <div class="horizon-user-info">

                    <strong>
                        ${escapeHTML(usuario.nombre)}
                        ${escapeHTML(usuario.apellido)}
                    </strong>

                    <small>
                        @${escapeHTML(usuario.usuario || usuario.nombre)}
                        ${usuario.email ? "· " + escapeHTML(usuario.email) : ""}
                    </small>

                </div>
                <a href="mis_pedidos.php" style="display:block;padding:10px 14px;">Mis pedidos</a>


                <button
                    type="button"
                    id="horizonLogout">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Cerrar sesión

                </button>

            </div>

        `;


        // Agregar al header
        const headerActions =
            document.querySelector(
                ".header-actions"
            );


        if (headerActions) {

            headerActions.appendChild(
                userMenu
            );

        }


        // Abrir menú
        const userButton =
            document.getElementById(
                "horizonUserButton"
            );


        const dropdown =
            document.getElementById(
                "horizonUserDropdown"
            );


        if (userButton && dropdown) {

            userButton.addEventListener(
                "click",
                () => {

                    dropdown.classList.toggle(
                        "active"
                    );

                }
            );

        }


        // Cerrar sesión
        const logoutButton =
            document.getElementById(
                "horizonLogout"
            );

        if (logoutButton) {

            logoutButton.addEventListener(
                "click",
                () => {

                    window.location.href = "logout.php";

                }
            );

        }

    }

}



// =========================================================
// CERRAR EL MENÚ AL HACER CLIC AFUERA
// =========================================================

document.addEventListener("click", (e) => {

    const menu = document.getElementById("horizonUserMenu");
    const dropdown = document.getElementById("horizonUserDropdown");

    if (menu && dropdown && !menu.contains(e.target)) {
        dropdown.classList.remove("active");
    }

});


// =========================================================
// CERRAR SESIÓN
// =========================================================

function cerrarSesionHorizon() {

    localStorage.removeItem(
        "horizon_usuario"
    );


    window.location.reload();

}



// =========================================================
// PROTEGER HTML
// =========================================================

function escapeHTML(text) {

    const div =
        document.createElement("div");

    div.textContent =
        text || "";

    return div.innerHTML;

}
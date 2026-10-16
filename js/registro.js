document.addEventListener("DOMContentLoaded", () => {

    const steps = document.querySelectorAll(".auth-step");
    const progressSteps = document.querySelectorAll(".progress-item");
    const progressLines = document.querySelectorAll(".progress-line");

    const form = document.getElementById("registroForm");

    let currentStep = 1;


    // ==========================================
    // MOSTRAR PASO
    // ==========================================

    function showStep(stepNumber) {

        currentStep = stepNumber;


        // Ocultar todos
        steps.forEach(step => {
            step.classList.remove("active");
        });


        // Mostrar actual
        const activeStep = document.querySelector(
            `.auth-step[data-step="${stepNumber}"]`
        );

        if (activeStep) {
            activeStep.classList.add("active");
        }


        // Actualizar círculos
        progressSteps.forEach(step => {

            const number = Number(step.dataset.step);

            step.classList.remove(
                "active",
                "completed"
            );


            if (number === stepNumber) {
                step.classList.add("active");
            }


            if (number < stepNumber) {
                step.classList.add("completed");
            }

        });


        // Actualizar líneas
        progressLines.forEach((line, index) => {

            if (index < stepNumber - 1) {

                line.classList.add("completed");

            } else {

                line.classList.remove("completed");

            }

        });


        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });

    }



    // ==========================================
    // VALIDAR PASO
    // ==========================================

    function validateCurrentStep() {

        const current = document.querySelector(
            `.auth-step[data-step="${currentStep}"]`
        );


        if (!current) {
            return false;
        }


        const fields = current.querySelectorAll(
            "input[required], select[required]"
        );


        for (const field of fields) {

            if (!field.checkValidity()) {

                field.reportValidity();

                return false;

            }

        }


        // Validar contraseña
        if (currentStep === 3) {

            const password =
                document.getElementById("password").value;

            const passwordRepeat =
                document.getElementById("passwordRepeat").value;


            if (password.length < 8) {

                alert(
                    "La contraseña debe tener al menos 8 caracteres."
                );

                return false;

            }


            if (password !== passwordRepeat) {

                alert(
                    "Las contraseñas no coinciden."
                );

                document
                    .getElementById("passwordRepeat")
                    .focus();

                return false;

            }

        }


        return true;

    }



    // ==========================================
    // SIGUIENTE
    // ==========================================

    document
        .querySelectorAll(".next-step")
        .forEach(button => {

            button.addEventListener("click", () => {

                if (!validateCurrentStep()) {
                    return;
                }


                if (currentStep < 3) {

                    showStep(currentStep + 1);

                }

            });

        });



    // ==========================================
    // ATRÁS
    // ==========================================

    document
        .querySelectorAll(".prev-step")
        .forEach(button => {

            button.addEventListener("click", () => {

                if (currentStep > 1) {

                    showStep(currentStep - 1);

                }

            });

        });


    // ==========================================
    // CREAR CUENTA (se envía a registro.php)
    // ==========================================

    form.addEventListener("submit", (event) => {

        if (!validateCurrentStep()) {
            event.preventDefault();
            return;
        }

        if (
            !document.getElementById("terminos").checked ||
            !document.getElementById("privacidad").checked
        ) {
            event.preventDefault();
            alert("Tenés que aceptar los términos y la política de privacidad.");
            return;
        }

    });
    

    // ==========================================
    // ESTADO INICIAL
    // ==========================================

    showStep(1);

});



// ==========================================
// MOSTRAR / OCULTAR CONTRASEÑA
// ==========================================

function togglePassword(id, button) {

    const input =
        document.getElementById(id);

    const icon =
        button.querySelector("i");


    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("fa-eye");

        icon.classList.add("fa-eye-slash");

    } else {

        input.type = "password";

        icon.classList.remove("fa-eye-slash");

        icon.classList.add("fa-eye");

    }

}
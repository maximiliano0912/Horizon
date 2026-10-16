<?php
session_start();
require 'config/db.php';

$exito = $_SESSION['mensaje_login'] ?? '';
unset($_SESSION['mensaje_login']);

if (isset($_SESSION['id_cliente'])) {
    header('Location: index.php');
    exit;
}
if (isset($_SESSION['id_usuario'])) {
    header('Location: ventas/panel.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se puede entrar con el nombre de usuario o con el correo
    $email    = trim($_POST['identificador'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errores[] = 'Ingresá tu usuario o email y tu contraseña.';
    } else {
        // 1) ¿Es cliente?
        $s = $pdo->prepare('SELECT id_cliente, nombre, apellido, usuario, email, password_hash FROM clientes WHERE email = ? OR usuario = ?');
        $s->execute([$email, $email]);
        $cliente = $s->fetch();

        if ($cliente && password_verify($password, $cliente['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['id_cliente'] = (int)$cliente['id_cliente'];
            $_SESSION['rol']        = 'cliente';
            $_SESSION['nombre']     = $cliente['nombre'];
            $_SESSION['apellido']   = $cliente['apellido'];
            $_SESSION['usuario']    = $cliente['usuario'];
            $_SESSION['email']      = $cliente['email'];
            header('Location: index.php');
            exit;
        }

        // 2) ¿Es personal interno?
        $s = $pdo->prepare('SELECT id_usuario, nombre, rol, password_hash FROM usuarios_internos WHERE email = ?');
        $s->execute([$email]);
        $interno = $s->fetch();

        if ($interno && password_verify($password, $interno['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['id_usuario'] = (int)$interno['id_usuario'];
            $_SESSION['rol']        = $interno['rol'];
            $_SESSION['nombre']     = $interno['nombre'];
            header('Location: ventas/panel.php');
            exit;
        }

        $errores[] = 'Usuario/email o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | HORIZON</title>

    <link
        rel="icon"
        href="img/Logo2.png">


    <!-- Google Fonts -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet">


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="css/style.css">

    <link
        rel="stylesheet"
        href="css/auth.css">

</head>


<body class="auth-page login-page">


    <!-- HEADER -->

    <header class="auth-header">

        <a
            href="index.php"
            class="logo auth-logo">

            <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

        </a>


        <a href="index.php" class="back-button">
            <i class="fa-solid fa-arrow-left"></i> Volver al inicio
        </a>


        <a
            href="registro.php"
            class="auth-header-link">

            ¿Todavía no tenés una cuenta?

            <strong>
                Crear cuenta
            </strong>

        </a>

    </header>



    <!-- MAIN -->

    <main class="auth-main">

        <div class="auth-background"></div>

        <div class="auth-overlay"></div>



        <!-- LOGIN -->

        <section class="login-card">


            <div class="auth-title">

                <span class="auth-label">
                    BIENVENIDO DE NUEVO
                </span>

                <h1>
                    Iniciá sesión
                </h1>

                <p>
                    Accedé a tu cuenta y seguí
                    planificando tu próxima aventura.
                </p>

            </div>



            <!-- FORM -->

            <form id="loginForm" action="login.php" method="POST">

    <?php if (!empty($errores)): ?>
        <div class="form-error" role="alert"
            style="background:#fdecea;color:#b3261e;border:1px solid #f5c2c0;border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:14px;">
            <?php foreach ($errores as $error): ?>
                <p style="margin:2px 0;"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($exito)): ?>
        <div class="form-success" role="status"
            style="background:#e6f4ea;color:#1e6e3a;border:1px solid #b7dfc4;border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:14px;">
            <?= htmlspecialchars($exito) ?>
        </div>
    <?php endif; ?>

                <!-- EMAIL -->

                <div class="form-group">

                    <label for="loginEmail">
                        Usuario o email
                    </label>

                    <div class="input-wrapper">

                        <i class="fa-regular fa-user"></i>

                        <input
                            type="text"
                            id="loginEmail"
                            name="identificador"
                            placeholder="Tu usuario o tuemail@ejemplo.com" autocomplete="username"
                            required>

                    </div>

                </div>



                <!-- PASSWORD -->

                <div class="form-group">

                    <div class="password-label">

                        <label for="loginPassword">
                            Contraseña
                        </label>

                    </div>


                    <div class="input-wrapper">

                        <i class="fa-solid fa-lock"></i>

                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            placeholder="Ingresá tu contraseña"
                            required>


                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('loginPassword', this)"
                            aria-label="Mostrar contraseña">

                            <i class="fa-regular fa-eye"></i>

                        </button>

                    </div>

                </div>



                <!-- RECORDAR -->

                <label class="remember-me">

                    <input
                        type="checkbox"
                        id="rememberMe">

                    <span class="custom-checkbox"></span>

                    Recordarme en este dispositivo

                </label>



                <!-- BOTÓN -->

                <button
                    type="submit"
                    class="create-account-button">

                    Iniciar sesión

                    <i class="fa-solid fa-arrow-right"></i>

                </button>


            </form>



            <!-- SEPARADOR -->

            <div class="auth-divider">

                <span>
                    o
                </span>

            </div>



            <!-- REGISTRO -->

            <div class="login-register">

                <p>
                    ¿Todavía no tenés una cuenta?
                </p>

                <a href="registro.php">

                    Crear una cuenta

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


        </section>

    </main>



    <script>

        // Mostrar / ocultar contraseña

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


    </script>


    <script src="js/auth.js"></script>

</body>

</html>
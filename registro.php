<?php
session_start();
require 'config/db.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = [];
    foreach (['nombre','apellido','fechaNacimiento','nacionalidad','tipoDocumento','numeroDocumento',
              'paisResidencia','email','telefono','paisContacto','ciudad','usuario'] as $k) {
        $d[$k] = trim($_POST[$k] ?? '');
    }
    $password = $_POST['password'] ?? '';
    $repeat   = $_POST['passwordRepeat'] ?? '';

    foreach (['nombre','apellido','fechaNacimiento','nacionalidad','tipoDocumento','numeroDocumento',
              'paisResidencia','email','telefono','paisContacto','ciudad','usuario'] as $k) {
        if ($d[$k] === '') { $errores[] = 'Completá todos los campos obligatorios.'; break; }
    }
    $f = DateTime::createFromFormat('Y-m-d', $d['fechaNacimiento']);
    if (!$f || $f->format('Y-m-d') !== $d['fechaNacimiento'] || $f > new DateTime('today')) {
        $errores[] = 'La fecha de nacimiento no es válida.';
    }
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido.';
    if (strlen($password) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
    if ($password !== $repeat) $errores[] = 'Las contraseñas no coinciden.';
    if (empty($_POST['terminos']) || empty($_POST['privacidad'])) $errores[] = 'Tenés que aceptar los términos y la política de privacidad.';

    if (!$errores) {
        $s = $pdo->prepare('SELECT 1 FROM clientes WHERE email = ? OR usuario = ?');
        $s->execute([$d['email'], $d['usuario']]);
        if ($s->fetch()) $errores[] = 'Ya existe una cuenta con ese email o nombre de usuario.';
    }

    if (!$errores) {
        $pdo->prepare('INSERT INTO clientes (nombre, apellido, fecha_nacimiento, nacionalidad, tipo_documento,
                numero_documento, pais_residencia, email, telefono, pais, ciudad, usuario, password_hash)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([$d['nombre'], $d['apellido'], $d['fechaNacimiento'], $d['nacionalidad'], $d['tipoDocumento'],
                $d['numeroDocumento'], $d['paisResidencia'], $d['email'], $d['telefono'], $d['paisContacto'],
                $d['ciudad'], $d['usuario'], password_hash($password, PASSWORD_DEFAULT)]);
        $_SESSION['mensaje_login'] = 'Cuenta creada correctamente. Ya podés iniciar sesión.';
        header('Location: login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | HORIZON</title>

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
    <link rel="stylesheet" href="css/auth.css">

</head>


<body class="auth-page">


    <!-- HEADER -->

    <header class="auth-header">

        <a href="index.php" class="logo auth-logo">

            <img src="img/Logo2.png" alt="HORIZON - Tu próxima aventura" class="logo-img">

        </a>


        <a href="index.php" class="back-button">
            <i class="fa-solid fa-arrow-left"></i> Volver al inicio
        </a>


        <a href="login.php" class="auth-header-link">

            ¿Ya tenés una cuenta?

            <strong>
                Iniciar sesión
            </strong>

        </a>

    </header>



    <!-- MAIN -->

    <main class="auth-main">

        <div class="auth-background"></div>

        <div class="auth-overlay"></div>


        <!-- REGISTRO -->

        <section class="register-card">


            <!-- TITULO -->

            <div class="auth-title">

                <span class="auth-label">
                    HORIZON
                </span>

                <h1>
                    Creá tu cuenta
                </h1>

                <p>
                    Registrate y empezá a planificar
                    tu próxima aventura.
                </p>

            </div>



            <!-- PROGRESO -->

            <div class="form-progress">

                <div class="progress-item active" data-step="1">

                    <span>1</span>

                    <small>
                        Datos personales
                    </small>

                </div>


                <div class="progress-line"></div>


                <div class="progress-item" data-step="2">

                    <span>2</span>

                    <small>
                        Contacto
                    </small>

                </div>


                <div class="progress-line"></div>


                <div class="progress-item" data-step="3">

                    <span>3</span>

                    <small>
                        Cuenta
                    </small>

                </div>

            </div>



            <!-- FORMULARIO -->

            <form id="registroForm" action="registro.php" method="POST" >
            
            <?php if (!empty($errores)): ?>
            <div class="form-error" role="alert"
            style="background:#fdecea;color:#b3261e;border:1px solid #f5c2c0;border-radius:10px;padding:12px 16px;margin-bottom:18px;font-size:14px;">
            <?php foreach ($errores as $error): ?>
            <p style="margin:2px 0;"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
                <!-- ================================= -->
                <!-- PASO 1 -->
                <!-- ================================= -->

                <div
                    class="auth-step active"
                    data-step="1">


                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="section-number">
                                01
                            </div>

                            <div>

                                <h2>
                                    Datos personales
                                </h2>

                                <p>
                                    Contanos un poco sobre vos.
                                </p>

                            </div>

                        </div>



                        <div class="form-grid">


                            <!-- NOMBRE -->

                            <div class="form-group">

                                <label for="nombre">
                                    Nombre
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-user"></i>

                                    <input
                                        type="text"
                                        id="nombre"
                                        name="nombre"
                                        placeholder="Ej. Nicolás"
                                        required>

                                </div>

                            </div>



                            <!-- APELLIDO -->

                            <div class="form-group">

                                <label for="apellido">
                                    Apellido
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-user"></i>

                                    <input
                                        type="text"
                                        id="apellido"
                                        name="apellido"
                                        placeholder="Ej. Aspengren"
                                        required>

                                </div>

                            </div>



                            <!-- FECHA -->

                            <div class="form-group">

                                <label for="fechaNacimiento">
                                    Fecha de nacimiento
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-calendar"></i>

                                    <input
                                        type="date"
                                        id="fechaNacimiento"
                                        name="fechaNacimiento"
                                        required>

                                </div>

                            </div>



                            <!-- NACIONALIDAD -->

                            <div class="form-group">

                                <label for="nacionalidad">
                                    Nacionalidad
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-earth-americas"></i>

                                    <select
                                        id="nacionalidad"
                                        name="nacionalidad"
                                        required>

                                        <option value="">
                                            Seleccioná tu nacionalidad
                                        </option>

                                        <option>Argentina</option>
                                        <option>Brasil</option>
                                        <option>Chile</option>
                                        <option>Colombia</option>
                                        <option>España</option>
                                        <option>México</option>
                                        <option>Uruguay</option>
                                        <option>Paraguay</option>
                                        <option>Perú</option>
                                        <option>Otra</option>

                                    </select>

                                </div>

                            </div>



                            <!-- TIPO DOCUMENTO -->

                            <div class="form-group">

                                <label for="tipoDocumento">
                                    Tipo de documento
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-id-card"></i>

                                    <select
                                        id="tipoDocumento"
                                        name="tipoDocumento"
                                        required>

                                        <option value="">
                                            Seleccioná una opción
                                        </option>

                                        <option>DNI</option>
                                        <option>Pasaporte</option>
                                        <option>Cédula</option>
                                        <option>Otro</option>

                                    </select>

                                </div>

                            </div>



                            <!-- NUMERO DOCUMENTO -->

                            <div class="form-group">

                                <label for="numeroDocumento">
                                    Número de documento
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-hashtag"></i>

                                    <input
                                        type="text"
                                        id="numeroDocumento"
                                        name="numeroDocumento"
                                        placeholder="Ingresá tu número"
                                        required>

                                </div>

                            </div>



                            <!-- PAIS -->

                            <div class="form-group full-width">

                                <label for="paisResidencia">
                                    País de residencia
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <select
                                        id="paisResidencia"
                                        name="paisResidencia"
                                        required>

                                        <option value="">
                                            Seleccioná tu país de residencia
                                        </option>

                                        <option>Argentina</option>
                                        <option>Brasil</option>
                                        <option>Chile</option>
                                        <option>Colombia</option>
                                        <option>España</option>
                                        <option>México</option>
                                        <option>Uruguay</option>
                                        <option>Paraguay</option>
                                        <option>Perú</option>
                                        <option>Otro</option>

                                    </select>

                                </div>

                            </div>


                        </div>

                    </div>



                    <!-- BOTON -->

                    <div class="step-buttons">

                        <button
                            type="button"
                            class="create-account-button next-step">

                            Continuar

                            <i class="fa-solid fa-arrow-right"></i>

                        </button>

                    </div>


                </div>



                <!-- ================================= -->
                <!-- PASO 2 -->
                <!-- ================================= -->

                <div
                    class="auth-step"
                    data-step="2">


                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="section-number">
                                02
                            </div>

                            <div>

                                <h2>
                                    Datos de contacto
                                </h2>

                                <p>
                                    Los utilizaremos para confirmar tus reservas.
                                </p>

                            </div>

                        </div>



                        <div class="form-grid">


                            <!-- EMAIL -->

                            <div class="form-group">

                                <label for="email">
                                    Email
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-envelope"></i>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder="tuemail@ejemplo.com"
                                        required>

                                </div>

                            </div>



                            <!-- TELEFONO -->

                            <div class="form-group">

                                <label for="telefono">
                                    Teléfono / WhatsApp
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-brands fa-whatsapp"></i>

                                    <input
                                        type="tel"
                                        id="telefono"
                                        name="telefono"
                                        placeholder="+54 9 11 1234 5678"
                                        required>

                                </div>

                            </div>



                            <!-- PAIS -->

                            <div class="form-group">

                                <label for="paisContacto">
                                    País
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-globe"></i>

                                    <select
                                        id="paisContacto"
                                        name="paisContacto"
                                        required>

                                        <option value="">
                                            Seleccioná tu país
                                        </option>

                                        <option>Argentina</option>
                                        <option>Brasil</option>
                                        <option>Chile</option>
                                        <option>Colombia</option>
                                        <option>España</option>
                                        <option>México</option>
                                        <option>Uruguay</option>
                                        <option>Paraguay</option>
                                        <option>Perú</option>
                                        <option>Otro</option>

                                    </select>

                                </div>

                            </div>



                            <!-- CIUDAD -->

                            <div class="form-group">

                                <label for="ciudad">
                                    Ciudad
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-city"></i>

                                    <input
                                        type="text"
                                        id="ciudad"
                                        name="ciudad"
                                        placeholder="Ej. Caleta Olivia"
                                        required>

                                </div>

                            </div>


                        </div>

                    </div>



                    <!-- BOTONES -->

                    <div class="step-buttons">

                        <button
                            type="button"
                            class="secondary-step-button prev-step">

                            <i class="fa-solid fa-arrow-left"></i>

                            Atrás

                        </button>


                        <button
                            type="button"
                            class="create-account-button next-step">

                            Continuar

                            <i class="fa-solid fa-arrow-right"></i>

                        </button>

                    </div>


                </div>



                <!-- ================================= -->
                <!-- PASO 3 -->
                <!-- ================================= -->

                <div
                    class="auth-step"
                    data-step="3">


                    <div class="form-section">

                        <div class="form-section-title">

                            <div class="section-number">
                                03
                            </div>

                            <div>

                                <h2>
                                    Creá tu cuenta
                                </h2>

                                <p>
                                    Elegí tus datos para ingresar a HORIZON.
                                </p>

                            </div>

                        </div>



                        <div class="form-grid">


                            <!-- USUARIO -->

                            <div class="form-group full-width">

                                <label for="usuario">

                                    Nombre de usuario

                                    <span>
                                        Opcional
                                    </span>

                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-regular fa-circle-user"></i>

                                    <input
                                        type="text"
                                        id="usuario"
                                        name="usuario"
                                        placeholder="Ej. nicolas_travel">

                                </div>

                            </div>



                            <!-- CONTRASEÑA -->

                            <div class="form-group">

                                <label for="password">
                                    Contraseña
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-lock"></i>

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Mínimo 8 caracteres"
                                        minlength="8"
                                        required>

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password', this)"
                                        aria-label="Mostrar contraseña">

                                        <i class="fa-regular fa-eye"></i>

                                    </button>

                                </div>

                            </div>



                            <!-- REPETIR -->

                            <div class="form-group">

                                <label for="passwordRepeat">
                                    Repetir contraseña
                                </label>

                                <div class="input-wrapper">

                                    <i class="fa-solid fa-lock"></i>

                                    <input
                                        type="password"
                                        id="passwordRepeat"
                                        name="passwordRepeat"
                                        placeholder="Repetí tu contraseña"
                                        minlength="8"
                                        required>

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('passwordRepeat', this)"
                                        aria-label="Mostrar contraseña">

                                        <i class="fa-regular fa-eye"></i>

                                    </button>

                                </div>

                            </div>


                        </div>



                        <!-- TERMINOS -->
<div class="terms-box">

<label class="checkbox-item">
    <input
        type="checkbox"
        id="terminos"
        name="terminos"
        value="1"
        required>
    <span class="custom-checkbox"></span>
    <span>
        Acepto los
        <a href="#">
            términos y condiciones
        </a>
    </span>
</label>

<label class="checkbox-item">
    <input
        type="checkbox"
        id="privacidad"
        name="privacidad"
        value="1"
        required>
    <span class="custom-checkbox"></span>
    <span>
        Acepto la
        <a href="#">
            política de privacidad
        </a>
    </span>
</label>

</div>


                    </div>



                    <!-- BOTONES -->

                    <div class="step-buttons">

                        <button
                            type="button"
                            class="secondary-step-button prev-step">

                            <i class="fa-solid fa-arrow-left"></i>

                            Atrás

                        </button>


                        <button
                            type="submit"
                            class="create-account-button">

                            Crear mi cuenta

                            <i class="fa-solid fa-arrow-right"></i>

                        </button>

                    </div>


                </div>


            </form>



            <!-- LOGIN -->

            <p class="login-text">

                ¿Ya tenés una cuenta?

                <a href="login.php">
                    Iniciar sesión
                </a>

            </p>


        </section>

    </main>


    <!-- JS -->

    <script src="js/registro.js"></script>
    <script src="js/auth.js"></script>

</body>

</html>
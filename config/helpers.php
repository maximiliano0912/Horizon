<?php
// Utilidades compartidas de HORIZON
require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// ---- Configuración (CAMBIAR) ----
const TASA_USD = 1200;   // 1 USD = X ARS (valor de ejemplo)
const TASA_EUR = 1300;   // 1 EUR = X ARS (valor de ejemplo)
// Access Token de Mercado Pago. Pegalo entre las comillas de MP_ACCESS_TOKEN (empieza con APP_USR-).
// Dónde sale: mercadopago.com.ar/developers > Tus integraciones > tu aplicación >
//             Datos de la integración > Credenciales de prueba > Access Token.
// Si queda vacío, se usa una pasarela simulada para poder demostrar el flujo.
const MP_ACCESS_TOKEN = 'TEST-935599128040286-100120-c39bf2228b52ba899ad5855f65c148cd-1704527589';

// ---- Opciones del carrito (valores de ejemplo, editalos a gusto) ----
// Método de viaje: multiplicador sobre el precio base del paquete (por persona).
// 'solo_nac' = true => solo disponible para paquetes nacionales (en ARS).
const METODOS_VIAJE = [
    'avion'  => ['nombre' => 'Avión',                   'mult' => 1.00, 'solo_nac' => false],
    'micro'  => ['nombre' => 'Micro semicama',          'mult' => 0.90, 'solo_nac' => true],
    'propio' => ['nombre' => 'Auto propio (sin pasajes)', 'mult' => 0.85, 'solo_nac' => true],
];
// Extras opcionales: precio en ARS por persona.
const EXTRAS_VIAJE = [
    'seguro'    => ['nombre' => 'Seguro de viaje',            'precio' => 25000],
    'traslados' => ['nombre' => 'Traslados aeropuerto-hotel', 'precio' => 18000],
    'excursion' => ['nombre' => 'Excursiones guiadas',        'precio' => 35000],
    'equipaje'  => ['nombre' => 'Equipaje extra (23 kg)',     'precio' => 22000],
];
const MAX_PERSONAS = 20;

// ---- Envío de mails por API (Brevo) ---- OPCIONAL (dejalo vacío si usás Gmail SMTP, más abajo)
// 1) Creá una cuenta gratis en https://www.brevo.com
// 2) En "SMTP y API" > "Claves API" generá una clave y pegala en MAIL_API_KEY.
// 3) En "Remitentes" agregá y verificá el correo desde el que van a salir los mails
//    (puede ser un Gmail: te llega un código de confirmación) y ponelo en MAIL_REMITENTE.
// Si MAIL_API_KEY está vacío, se usa el SMTP de Gmail (abajo) y, si tampoco está configurado,
// el modo demostración (guarda los mails en mails_simulados/).
const MAIL_API_KEY   = '';     // ej: 'xkeysib-...'
const MAIL_REMITENTE = '';     // ej: 'horizon.viajes@gmail.com' (verificado en Brevo)

// ---- Envío de mails con tu Gmail (SMTP) <- LO MÁS SIMPLE ----
// Para enviar de verdad: activá la verificación en 2 pasos en la cuenta de Gmail y creá una
// "contraseña de aplicación" (myaccount.google.com/apppasswords). Si MAIL_USER queda vacío,
// los mails NO se envían: se guardan como .html en la carpeta mails_simulados/ para demostrar.
const MAIL_USER = '';          // ej: 'horizon.viajes@gmail.com'
const MAIL_PASS = '';          // contraseña de aplicación de 16 letras
const MAIL_NOMBRE = 'HORIZON Viajes';
const MAIL_VERIFICAR_SSL = true;   // si en XAMPP falla el certificado, probá con false

function es_internacional(string $moneda): bool { return $moneda !== 'ARS'; }

// Precio por persona en ARS, con método de viaje y extras. Lo usa el servidor (checkout) para cobrar.
function precio_persona_ars($precioBase, string $moneda, string $metodo, array $extras): float {
    $base = a_ars($precioBase, $moneda) * (METODOS_VIAJE[$metodo]['mult'] ?? 1);
    foreach ($extras as $e) $base += EXTRAS_VIAJE[$e]['precio'] ?? 0;
    return round($base, 2);
}

// Datos del usuario para el header (js/auth.js). Imprimir en la página antes de auth.js.
function script_usuario(): void {
    $u = null;
    if (isset($_SESSION['id_cliente'])) {
        $u = ['nombre' => $_SESSION['nombre'] ?? '', 'apellido' => $_SESSION['apellido'] ?? '',
              'usuario' => $_SESSION['usuario'] ?? ($_SESSION['nombre'] ?? ''), 'email' => $_SESSION['email'] ?? ''];
    }
    echo '<script>window.HORIZON_USUARIO = ' . json_encode($u, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';</script>';
}

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function a_ars($precio, string $moneda): float {
    $precio = (float)$precio;
    if ($moneda === 'USD') return round($precio * TASA_USD, 2);
    if ($moneda === 'EUR') return round($precio * TASA_EUR, 2);
    return round($precio, 2);
}

function money($n): string { return '$' . number_format((float)$n, 2, ',', '.'); }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_ok(?string $t): bool {
    return !empty($_SESSION['csrf']) && is_string($t) && hash_equals($_SESSION['csrf'], $t);
}

function requiere_cliente(string $login = 'login.php'): void {
    if (!isset($_SESSION['id_cliente'])) { header('Location: ' . $login); exit; }
}
function requiere_staff(string $login = '../login.php'): void {
    if (!isset($_SESSION['id_usuario']) || !in_array($_SESSION['rol'] ?? '', ['jefe_ventas', 'ventas'], true)) {
        header('Location: ' . $login); exit;
    }
}

require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/layout.php';

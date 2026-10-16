<?php
// Capa servidor: recibe el carrito, recalcula todo con los precios de la BD,
// crea el pedido (pendiente), su detalle y el registro en ventas.
// Por cada paquete llegan: personas, método de viaje, fecha de salida y extras.
require __DIR__ . '/config/helpers.php';
header('Content-Type: application/json; charset=utf-8');

function salir(array $d, int $code = 200): void { http_response_code($code); echo json_encode($d); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') salir(['ok' => false, 'error' => 'Método no permitido.'], 405);
if (!isset($_SESSION['id_cliente'])) salir(['ok' => false, 'login' => true, 'error' => 'Iniciá sesión para comprar.'], 401);
if (!csrf_ok($_SERVER['HTTP_X_CSRF'] ?? null)) salir(['ok' => false, 'error' => 'Sesión inválida, recargá la página.'], 403);

$in = json_decode(file_get_contents('php://input'), true);
$items = is_array($in['items'] ?? null) ? $in['items'] : [];

// Normalizar: un renglón por paquete
$sel = [];
foreach ($items as $it) {
    $cod = trim((string)($it['codigo'] ?? ''));
    if ($cod === '' || isset($sel[$cod])) continue;
    $sel[$cod] = [
        'personas' => (int)($it['personas'] ?? 1),
        'metodo'   => (string)($it['metodo'] ?? 'avion'),
        'fecha'    => trim((string)($it['fecha'] ?? '')),
        'extras'   => is_array($it['extras'] ?? null) ? array_values(array_unique(array_map('strval', $it['extras']))) : [],
    ];
}
if (!$sel) salir(['ok' => false, 'error' => 'El carrito está vacío.'], 422);

$in_ph = implode(',', array_fill(0, count($sel), '?'));
$st = $pdo->prepare("SELECT cod_producto, descripcion, precio_unitario, moneda FROM productos WHERE cod_producto IN ($in_ph)");
$st->execute(array_keys($sel));
$prods = [];
foreach ($st->fetchAll() as $p) $prods[$p['cod_producto']] = $p;

$manana = new DateTime('tomorrow');
foreach ($sel as $cod => $o) {
    if (!isset($prods[$cod])) salir(['ok' => false, 'error' => "El producto $cod no existe en el sistema."], 422);
    $nom = $prods[$cod]['descripcion'];
    if ($o['personas'] < 1 || $o['personas'] > MAX_PERSONAS) salir(['ok' => false, 'error' => "Elegí entre 1 y " . MAX_PERSONAS . " personas en «$nom»."], 422);
    if (!isset(METODOS_VIAJE[$o['metodo']])) salir(['ok' => false, 'error' => "Elegí un método de viaje en «$nom»."], 422);
    if (METODOS_VIAJE[$o['metodo']]['solo_nac'] && es_internacional($prods[$cod]['moneda']))
        salir(['ok' => false, 'error' => "«$nom» es un destino internacional: solo se viaja en avión."], 422);
    $f = DateTime::createFromFormat('Y-m-d', $o['fecha']);
    if (!$f || $f->format('Y-m-d') !== $o['fecha'] || $f < $manana)
        salir(['ok' => false, 'error' => "Elegí una fecha de salida válida (a partir de mañana) en «$nom»."], 422);
    foreach ($o['extras'] as $e) if (!isset(EXTRAS_VIAJE[$e])) salir(['ok' => false, 'error' => 'Extra no válido.'], 422);
}

try {
    $pdo->beginTransaction();
    $total = 0;
    $lineas = [];
    foreach ($sel as $cod => $o) {
        $precio = precio_persona_ars($prods[$cod]['precio_unitario'], $prods[$cod]['moneda'], $o['metodo'], $o['extras']);
        $total += $precio * $o['personas'];
        $nombresExtras = implode(', ', array_map(fn($e) => EXTRAS_VIAJE[$e]['nombre'], $o['extras']));
        $lineas[] = [$cod, $o['personas'], $precio, $o['metodo'], $o['fecha'], $nombresExtras !== '' ? $nombresExtras : null];
    }
    $pdo->prepare('INSERT INTO pedidos (id_cliente, estado, total) VALUES (?, "pendiente", ?)')
        ->execute([$_SESSION['id_cliente'], $total]);
    $nro = (int)$pdo->lastInsertId();

    $ins = $pdo->prepare('INSERT INTO detalle_pedido (nro_pedido, cod_producto, cantidad, precio, metodo_viaje, fecha_viaje, extras) VALUES (?,?,?,?,?,?,?)');
    foreach ($lineas as [$cod, $q, $precio, $metodo, $fecha, $extras]) $ins->execute([$nro, $cod, $q, $precio, $metodo, $fecha, $extras]);

    $pdo->prepare('INSERT INTO ventas (nro_pedido, id_cliente, total, estado_cobro) VALUES (?,?,?, "pendiente")')
        ->execute([$nro, $_SESSION['id_cliente'], $total]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    salir(['ok' => false, 'error' => 'No se pudo registrar el pedido. ¿Ejecutaste sql/migracion2.sql?'], 500);
}

salir(['ok' => true, 'nro_pedido' => $nro, 'redirect' => 'pagar.php?pedido=' . $nro]);

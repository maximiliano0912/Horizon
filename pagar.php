<?php
// Cobro del pedido con herramienta de terceros (Mercado Pago Checkout Pro).
// Sin token configurado usa una pasarela simulada para la demostración.
require __DIR__ . '/config/helpers.php';
requiere_cliente();

$nro = (int)($_GET['pedido'] ?? $_POST['pedido'] ?? 0);
$st = $pdo->prepare('SELECT p.nro_pedido, p.total, p.estado, v.id_venta, v.estado_cobro
                     FROM pedidos p JOIN ventas v ON v.nro_pedido = p.nro_pedido
                     WHERE p.nro_pedido = ? AND p.id_cliente = ?');
$st->execute([$nro, $_SESSION['id_cliente']]);
$ped = $st->fetch();
if (!$ped || $ped['estado'] !== 'pendiente') { header('Location: mis_pedidos.php'); exit; }

$mensaje = ''; $error = '';
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

// Email de la cuenta del cliente: ahí llega la confirmación
$cu = $pdo->prepare('SELECT email FROM clientes WHERE id_cliente = ?');
$cu->execute([$_SESSION['id_cliente']]);
$emailCuenta = (string)$cu->fetchColumn();

// Marca la venta como cobrada y manda el mail de confirmación al correo de la cuenta
function marcar_cobrado(PDO $pdo, int $idVenta, string $idPago, int $nro): string {
    $pdo->prepare('UPDATE ventas SET estado_cobro="cobrado", id_pago_ext=? WHERE id_venta=?')->execute([$idPago, $idVenta]);
    $res = enviar_confirmacion_pago($pdo, $nro);
    $msg = '¡Pago aprobado! Tu pedido quedó pendiente de entrega.';
    if ($res === 'enviado')  return $msg . ' Te enviamos la confirmación por correo.';
    if ($res === 'simulado') return $msg . ' (Modo demostración: el correo no se envió, falta configurar el envío en config/helpers.php.)';
    return $msg . ' No pudimos enviar el correo de confirmación.';
}

// Llamada a la API de Mercado Pago. Devuelve [datos|null, mensaje de error|null]
function mp_request(string $method, string $url, ?array $body = null): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20, CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => MAIL_VERIFICAR_SSL, CURLOPT_SSL_VERIFYHOST => MAIL_VERIFICAR_SSL ? 2 : 0,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . MP_ACCESS_TOKEN, 'Content-Type: application/json'],
    ]);
    if ($body !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    $r = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $cerr = curl_error($ch);
    curl_close($ch);
    if ($r === false) { error_log('HORIZON MP: ' . $cerr); return [null, 'No se pudo conectar con Mercado Pago (' . $cerr . ').']; }
    $j = json_decode($r, true);
    if ($code >= 200 && $code < 300 && is_array($j)) return [$j, null];
    error_log('HORIZON MP: HTTP ' . $code . ' ' . $r);
    return [null, 'Mercado Pago respondió con un error' . (!empty($j['message']) ? ': ' . $j['message'] : ' (HTTP ' . $code . ')') . '.'];
}

if ($ped['estado_cobro'] === 'cobrado') {
    $mensaje = 'Este pedido ya está pagado.';
} elseif (MP_ACCESS_TOKEN !== '') {

    // 1) Regreso desde Mercado Pago: verificar el pago contra su API (no confiar en lo que dice la URL)
    if (!empty($_GET['payment_id'])) {
        [$pago, $e] = mp_request('GET', 'https://api.mercadopago.com/v1/payments/' . urlencode((string)$_GET['payment_id']));
        if ($pago && ($pago['status'] ?? '') === 'approved'
            && (string)($pago['external_reference'] ?? '') === (string)$nro
            && abs((float)$pago['transaction_amount'] - (float)$ped['total']) < 0.01) {
            $mensaje = marcar_cobrado($pdo, (int)$ped['id_venta'], (string)$pago['id'], $nro);
        } elseif ($pago && in_array($pago['status'] ?? '', ['pending', 'in_process'], true)) {
            $error = 'Tu pago está pendiente de acreditación. Cuando se apruebe, volvé a esta página.';
        } else {
            $error = $e ?: 'El pago no fue aprobado. Podés intentarlo de nuevo.';
        }
    } elseif (($_GET['status'] ?? '') === 'failure' || ($_GET['collection_status'] ?? '') === 'rejected') {
        $error = 'El pago fue rechazado o cancelado. Podés intentarlo de nuevo.';
    }

    // 2) Crear la preferencia de pago y mandar al cliente a Mercado Pago
    if (!$mensaje && $_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok($_POST['csrf'] ?? null)) {
        $volver = "$base/pagar.php?pedido=$nro";
        $pref_body = [
            'items' => [[
                'title' => 'Pedido HORIZON #' . $nro, 'description' => 'Paquetes turísticos HORIZON',
                'quantity' => 1, 'currency_id' => 'ARS', 'unit_price' => round((float)$ped['total'], 2),
            ]],
            'external_reference' => (string)$nro,
            'statement_descriptor' => 'HORIZON',
            'back_urls' => ['success' => $volver, 'failure' => $volver . '&status=failure', 'pending' => $volver],
        ];
        // auto_return exige una URL pública (https); en localhost se omite y el cliente vuelve con el botón de MP
        if (!preg_match('/^(localhost|127\.|192\.168\.|10\.)/', $_SERVER['HTTP_HOST']) && isset($_SERVER['HTTPS'])) {
            $pref_body['auto_return'] = 'approved';
        }
        [$pref, $e] = mp_request('POST', 'https://api.mercadopago.com/checkout/preferences', $pref_body);
        // Con credenciales de prueba (TEST-) Mercado Pago ofrece también el enlace sandbox
        $destino = (strncmp(MP_ACCESS_TOKEN, 'TEST-', 5) === 0 && !empty($pref['sandbox_init_point'])) ? $pref['sandbox_init_point'] : ($pref['init_point'] ?? '');
        if ($pref && $destino !== '') { header('Location: ' . $destino); exit; }
        $error = $e ?: 'No se pudo iniciar el pago con Mercado Pago.';
    }

} else {
    // Pasarela simulada (sin token de Mercado Pago configurado)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok($_POST['csrf'] ?? null)) {
        if (($_POST['resultado'] ?? '') === 'aprobar') {
            $mensaje = marcar_cobrado($pdo, (int)$ped['id_venta'], 'SIM-' . strtoupper(bin2hex(random_bytes(4))), $nro);
        } else {
            $error = 'Pago rechazado. Podés intentarlo de nuevo.';
        }
    }
}

// Detalle del pedido para mostrar antes de pagar
$det = $pdo->prepare('SELECT d.cantidad, d.precio, d.metodo_viaje, d.fecha_viaje, d.extras, pr.descripcion
                      FROM detalle_pedido d JOIN productos pr ON pr.cod_producto = d.cod_producto WHERE d.nro_pedido = ?');
$det->execute([$nro]);
$lineas = $det->fetchAll();
?>
<?php panel_head('Pago'); ?>
<?php panel_top_cliente(); ?>
<main class="box">
<a class="back-link" href="mis_pedidos.php"><i class="fa-solid fa-arrow-left"></i> Volver a mis pedidos</a>
<h1>Pago del pedido #<?= (int)$nro ?></h1>

<table><tr><th>Paquete</th><th>Personas</th><th>Subtotal</th></tr>
<?php foreach ($lineas as $l):
  $op = [];
  if (!empty($l['metodo_viaje']) && isset(METODOS_VIAJE[$l['metodo_viaje']])) $op[] = METODOS_VIAJE[$l['metodo_viaje']]['nombre'];
  if (!empty($l['fecha_viaje'])) $op[] = 'Salida: ' . date('d/m/Y', strtotime($l['fecha_viaje']));
  if (!empty($l['extras'])) $op[] = 'Extras: ' . $l['extras']; ?>
<tr><td><?= h($l['descripcion']) ?><div class="opciones"><?= h(implode(' · ', $op)) ?></div></td>
<td><?= (int)$l['cantidad'] ?></td><td><?= money($l['cantidad'] * $l['precio']) ?></td></tr>
<?php endforeach; ?></table>

<p class="total">Total: <strong><?= money($ped['total']) ?></strong></p>
<?php if ($mensaje): ?><p class="ok"><?= h($mensaje) ?></p><p><a class="btn" href="mis_pedidos.php">Ver mis pedidos</a> <a class="btn sec" href="index.php">Volver al inicio</a></p>
<?php else: ?>
  <?php if ($error): ?><p class="err"><?= h($error) ?></p><?php endif; ?>
  <form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="pedido" value="<?= (int)$nro ?>">
  <p class="muted"><i class="fa-regular fa-envelope"></i> Al pagar te enviamos la confirmación a <strong><?= h($emailCuenta) ?></strong></p>
  <?php if (MP_ACCESS_TOKEN !== ''): ?>
    <button class="btn" type="submit"><i class="fa-solid fa-credit-card"></i> Pagar con Mercado Pago</button>
    <p class="muted">Vas a pasar a Mercado Pago para completar el pago de forma segura.</p>
  <?php else: ?>
    <p class="muted">Pasarela de pago simulada (modo demostración).</p>
    <button class="btn green" name="resultado" value="aprobar" type="submit">Simular pago aprobado</button>
    <button class="btn red" name="resultado" value="rechazar" type="submit">Simular pago rechazado</button>
  <?php endif; ?>
  </form>
  <p><a href="mis_pedidos.php">Pagar más tarde</a></p>
<?php endif; ?>
</main></body></html>

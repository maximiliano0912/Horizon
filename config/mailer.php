<?php
// Envío de mails sin librerías externas: SMTP de Gmail (puerto 587, STARTTLS).
// Sin MAIL_USER configurado, guarda el mail en mails_simulados/ para poder demostrar el flujo.

function smtp_leer($fp): string {
    $resp = '';
    while (($linea = fgets($fp, 515)) !== false) {
        $resp .= $linea;
        if (strlen($linea) < 4 || $linea[3] === ' ') break;   // última línea de la respuesta
    }
    return $resp;
}
function smtp_cmd($fp, string $cmd, string $espera): bool {
    fwrite($fp, $cmd . "\r\n");
    return strpos(smtp_leer($fp), $espera) === 0;
}

// Envío por API HTTP (Brevo): POST https://api.brevo.com/v3/smtp/email
function enviar_por_api(string $para, string $nombrePara, string $asunto, string $html): bool {
    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_SSL_VERIFYPEER => MAIL_VERIFICAR_SSL,
        CURLOPT_SSL_VERIFYHOST => MAIL_VERIFICAR_SSL ? 2 : 0,
        CURLOPT_HTTPHEADER     => ['api-key: ' . MAIL_API_KEY, 'Content-Type: application/json', 'Accept: application/json'],
        CURLOPT_POSTFIELDS     => json_encode([
            'sender'      => ['name' => MAIL_NOMBRE, 'email' => MAIL_REMITENTE],
            'to'          => [['email' => $para, 'name' => $nombrePara !== '' ? $nombrePara : $para]],
            'subject'     => $asunto,
            'htmlContent' => $html,
        ]),
    ]);
    $r = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    if ($code === 201 || $code === 200) return true;
    error_log('HORIZON mail API: HTTP ' . $code . ' ' . $err . ' ' . (is_string($r) ? $r : ''));
    return false;
}

// Devuelve 'enviado', 'simulado' (modo demostración, no sale ningún mail) o 'error'.
function enviar_mail(string $para, string $asunto, string $html, string $nombrePara = ''): string {
    if (!filter_var($para, FILTER_VALIDATE_EMAIL)) return 'error';

    // 1) API (Brevo)
    if (MAIL_API_KEY !== '' && MAIL_REMITENTE !== '') {
        return enviar_por_api($para, $nombrePara, $asunto, $html) ? 'enviado' : 'error';
    }

    // 2) Modo demostración (sin API ni SMTP configurados)
    if (MAIL_USER === '' || MAIL_PASS === '') {
        $dir = __DIR__ . '/../mails_simulados';
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        $nombre = date('Ymd-His') . '-' . preg_replace('/[^a-z0-9]+/i', '_', $para) . '.html';
        return @file_put_contents("$dir/$nombre", "<!-- Para: $para | Asunto: $asunto -->\n" . $html) !== false ? 'simulado' : 'error';
    }

    $ctx = stream_context_create(['ssl' => ['verify_peer' => MAIL_VERIFICAR_SSL, 'verify_peer_name' => MAIL_VERIFICAR_SSL]]);
    $fp = @stream_socket_client('tcp://smtp.gmail.com:587', $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) return 'error';
    stream_set_timeout($fp, 15);

    $ok = strpos(smtp_leer($fp), '220') === 0
        && smtp_cmd($fp, 'EHLO localhost', '250')
        && smtp_cmd($fp, 'STARTTLS', '220')
        && stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)
        && smtp_cmd($fp, 'EHLO localhost', '250')
        && smtp_cmd($fp, 'AUTH LOGIN', '334')
        && smtp_cmd($fp, base64_encode(MAIL_USER), '334')
        && smtp_cmd($fp, base64_encode(MAIL_PASS), '235')
        && smtp_cmd($fp, 'MAIL FROM:<' . MAIL_USER . '>', '250')
        && smtp_cmd($fp, 'RCPT TO:<' . $para . '>', '250')
        && smtp_cmd($fp, 'DATA', '354');

    if ($ok) {
        $cab  = 'From: =?UTF-8?B?' . base64_encode(MAIL_NOMBRE) . '?= <' . MAIL_USER . ">\r\n";
        $cab .= 'To: <' . $para . ">\r\n";
        $cab .= 'Subject: =?UTF-8?B?' . base64_encode($asunto) . "?=\r\n";
        $cab .= 'Date: ' . date('r') . "\r\n";
        $cab .= "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: base64\r\n\r\n";
        $cuerpo = chunk_split(base64_encode($html));
        fwrite($fp, $cab . $cuerpo . "\r\n.\r\n");
        $ok = strpos(smtp_leer($fp), '250') === 0;
    }
    @fwrite($fp, "QUIT\r\n");
    fclose($fp);
    return $ok ? 'enviado' : 'error';
}

// Mail de confirmación de pago al email con el que se registró el cliente.
function enviar_confirmacion_pago(PDO $pdo, int $nro, ?string $destino = null): string {
    $s = $pdo->prepare('SELECT p.nro_pedido, p.total, p.fecha, c.nombre, c.email
                        FROM pedidos p JOIN clientes c ON c.id_cliente = p.id_cliente WHERE p.nro_pedido = ?');
    $s->execute([$nro]);
    $ped = $s->fetch();
    if (!$ped) return 'error';

    $d = $pdo->prepare('SELECT d.cantidad, d.precio, d.metodo_viaje, d.fecha_viaje, d.extras, pr.descripcion
                        FROM detalle_pedido d JOIN productos pr ON pr.cod_producto = d.cod_producto WHERE d.nro_pedido = ?');
    $d->execute([$nro]);

    $filas = '';
    foreach ($d->fetchAll() as $l) {
        $extra = [];
        if (!empty($l['metodo_viaje']) && isset(METODOS_VIAJE[$l['metodo_viaje']])) $extra[] = METODOS_VIAJE[$l['metodo_viaje']]['nombre'];
        if (!empty($l['fecha_viaje'])) $extra[] = 'Salida: ' . date('d/m/Y', strtotime($l['fecha_viaje']));
        if (!empty($l['extras'])) $extra[] = 'Extras: ' . $l['extras'];
        $filas .= '<tr><td style="padding:10px;border-bottom:1px solid #e3e8ef">' . h($l['descripcion'])
            . '<br><small style="color:#6b7785">' . h(implode(' · ', $extra)) . '</small></td>'
            . '<td style="padding:10px;border-bottom:1px solid #e3e8ef;text-align:center">' . (int)$l['cantidad'] . ' pers.</td>'
            . '<td style="padding:10px;border-bottom:1px solid #e3e8ef;text-align:right">' . h(money($l['cantidad'] * $l['precio'])) . '</td></tr>';
    }

    $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:auto;color:#102d55">'
        . '<div style="background:#0b2545;color:#fff;padding:22px;border-radius:12px 12px 0 0"><h2 style="margin:0">HORIZON</h2>'
        . '<small>Tu próxima aventura</small></div>'
        . '<div style="padding:22px;border:1px solid #e3e8ef;border-top:0;border-radius:0 0 12px 12px">'
        . '<h3>¡Gracias por tu compra, ' . h($ped['nombre']) . '!</h3>'
        . '<p>Recibimos el pago de tu pedido <strong>#' . (int)$ped['nro_pedido'] . '</strong>. Estos son los datos:</p>'
        . '<table style="width:100%;border-collapse:collapse;font-size:14px">' . $filas . '</table>'
        . '<p style="font-size:18px;text-align:right"><strong>Total pagado: ' . h(money($ped['total'])) . '</strong></p>'
        . '<p style="color:#6b7785;font-size:13px">Nuestro equipo de ventas se va a poner en contacto con vos para coordinar los detalles del viaje.</p>'
        . '</div></div>';

    // Si el cliente escribió otro correo al pagar, va ahí; si no, al de su cuenta.
    $para = ($destino !== null && filter_var($destino, FILTER_VALIDATE_EMAIL)) ? $destino : $ped['email'];
    return enviar_mail($para, 'Confirmación de tu pedido #' . (int)$ped['nro_pedido'] . ' - HORIZON', $html, $ped['nombre']);
}

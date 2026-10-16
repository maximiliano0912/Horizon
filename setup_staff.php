<?php
// EJECUTAR UNA SOLA VEZ en el navegador (http://localhost/olimpiadas-ipp/setup_staff.php) y BORRAR este archivo.
require __DIR__ . '/config/db.php';
$password = 'Horizon2026!';   // cambiala antes de ejecutar
$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo->prepare('UPDATE usuarios_internos SET password_hash=? WHERE email="jefe@horizon.test"')->execute([$hash]);
$pdo->prepare('INSERT IGNORE INTO usuarios_internos (nombre,email,password_hash,rol) VALUES ("Vendedor","ventas@horizon.test",?,"ventas")')->execute([$hash]);
echo 'Listo. Usuarios: jefe@horizon.test y ventas@horizon.test con la contraseña configurada. BORRÁ setup_staff.php.';

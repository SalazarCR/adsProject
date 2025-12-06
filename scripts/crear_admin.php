<?php
require_once __DIR__ . '/../config/conexion.php'; // ajusta ruta si es necesario

$username = 'admin';
$password_plain = 'Admin123!'; // cambia inmediatamente
$nombre = 'Administrador';
$rol = 'admin';
$email = 'admin@example.com';

$hash = password_hash($password_plain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuarios (username, password, nombre, rol, email) VALUES (?, ?, ?, ?, ?)");
try {
    $stmt->execute([$username, $hash, $nombre, $rol, $email]);
    echo "Usuario admin creado.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

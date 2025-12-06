<?php
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

$msg = new PantallaMensajeSistema();

// VALIDACIÓN DE BOTÓN
if (!isset($_POST['btnRecuperar'])) {
    $msg->mensajeSistemaShow(
        3,
        "Acceso no permitido.",
        "/index.php"
    );
    exit();
}

// Si pasa la validación → enviar al controlador principal
$dato = trim($_POST['dato'] ?? '');

header("Location: /controllers/RecuperarController.php?op=procesar&dato=$dato");
exit;

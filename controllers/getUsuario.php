<?php
// controllers/getUsuario.php

require_once __DIR__ . '/AuthController.php'; 
// CORRECCIÓN: Salir de 'controllers' (..), entrar a 'views', luego 'shared'
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

function validarBoton($boton) {
    return isset($boton);
}

function validarTexto($u, $p) {
    return (strlen($u) > 0 && strlen($p) > 0);
}

$boton = $_POST['btnIngresar'] ?? null;
$user = $_POST['usuario'] ?? '';
$pass = $_POST['password'] ?? '';

if (validarBoton($boton)) {
    if (validarTexto($user, $pass)) {
        $objControl = new AuthController();
        $objControl->validarUsuario($user, $pass);
    } else {
        $objMsg = new PantallaMensajeSistema();
        // Nota: El enlace de retorno desde controllers debe subir un nivel (../index.php)
        $objMsg->mensajeSistemaShow(2, "Datos incompletos", "../index.php");
    }
} else {
    $objMsg = new PantallaMensajeSistema();
    $objMsg->mensajeSistemaShow(3, "Acceso no permitido", "../index.php");
}
?>
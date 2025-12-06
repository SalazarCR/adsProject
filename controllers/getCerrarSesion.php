<?php
// controllers/getCerrarSesion.php

// 1. Incluir el Controlador de Autenticación (está en la misma carpeta)
require_once __DIR__ . '/AuthController.php';

// 2. Incluir la Pantalla de Mensajes (CORRECCIÓN DE RUTA)
// Salimos de 'controllers' (..), entramos a 'views', luego a 'shared'
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php'; 

// VALIDACIÓN DE BOTÓN (Cumpliendo teoría)
function validarBoton($boton) {
    return isset($boton);
}

$boton = $_POST['btnCerrarSesion'] ?? null;

if (validarBoton($boton)) {
    // Lógica Correcta: Llamar al controlador para destruir sesión
    $objControl = new AuthController();
    $objControl->logout(); 
} else {
    // Flujo Alternativo: Intento de acceso directo sin botón
    $objMsg = new PantallaMensajeSistema();
    // Nota: El enlace de retorno sube un nivel (../index.php)
    $objMsg->mensajeSistemaShow(3, "Acceso no permitido", "../index.php");
}
?>
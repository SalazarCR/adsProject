<?php
// index.php
// Objetivo: Únicamente iniciar el caso de uso "Autenticar Usuario"

// 1. Incluimos la clase de la Vista (Boundary)
require_once __DIR__ . '/views/auth/FormAutenticarUsuario.php';

// 2. Instanciamos el objeto
$objFormLogin = new FormAutenticarUsuario();

// 3. Llamamos al método que pinta la pantalla
$objFormLogin->formAutenticarUsuarioShow();

?>
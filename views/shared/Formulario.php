<?php
// views/shared/Formulario.php

class Formulario {
    
    protected function cabeceraShow() {
        // Al estar en la misma carpeta, usamos __DIR__ directo
        include_once __DIR__ . '/header.php'; 
    }

    protected function menuShow() {
        include_once __DIR__ . '/menu.php';
    }

    protected function piePaginaShow() {
        include_once __DIR__ . '/footer.php';
    }
}
?>
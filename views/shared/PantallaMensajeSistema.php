<?php
// views/shared/PantallaMensajeSistema.php

// Al estar en la misma carpeta, solo requerimos el archivo por su nombre
require_once __DIR__ . '/Formulario.php';

class PantallaMensajeSistema extends Formulario {

    public function mensajeSistemaShow($tipo, $mensaje, $enlace) {
        $this->cabeceraShow();
        
        $color = ($tipo == 3) ? 'red' : 'orange';
        
        echo "<div style='width: 300px; margin: 50px auto; padding: 20px; border: 1px solid $color; text-align: center; background: white;'>";
        echo "<h3 style='color: $color;'>MENSAJE DEL SISTEMA</h3>";
        echo "<p>$mensaje</p>";
        echo "<br/>";
        echo "<a href='$enlace' class='btn'>Regresar</a>";
        echo "</div>";

        $this->piePaginaShow();
    }
}
?>
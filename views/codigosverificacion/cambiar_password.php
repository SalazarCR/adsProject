<?php
require_once __DIR__ . '/../shared/Formulario.php';
require_once __DIR__ . '/../shared/PantallaMensajeSistema.php';
require_once __DIR__ . '/../../core/Session.php';
include __DIR__ . '/../shared/header.php';

Session::start();

class FormCambiarPassword extends Formulario {

    public function formularioShow() {
        $this->cabeceraShow("Cambiar contraseña");
        ?>

        <form action="/controllers/RecuperarController.php?op=cambiar" method="POST">
            <label>Nueva contraseña:</label>
            <input type="password" name="password1" required>
            <label>Repetir contraseña:</label>
            <input type="password" name="password2" required>
            <button type="submit" name="btnCambiar">Cambiar contraseña</button>
        </form>

        <p><a href="/index.php">Volver</a></p>

        <?php
        $this->piePaginaShow();
    }
}

$form = new FormCambiarPassword();
$form->formularioShow();


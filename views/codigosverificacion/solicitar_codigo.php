<?php
include __DIR__ . '/../shared/header.php';
require_once __DIR__ . '/../shared/Formulario.php';

class FormSolicitarCodigo extends Formulario {

    public function formularioShow() {
        $this->cabeceraShow("Recuperar contraseña");
        ?>

        <form action="/controllers/RecuperarController.php?op=solicitar" method="POST">
            <label>Correo o Teléfono:</label>
            <input type="text" name="dato" required>
            <button type="submit" name="btnSolicitar">Enviar código</button>
        </form>


        <p><a href="/index.php">Volver</a></p>

        <?php
        $this->piePaginaShow();
    }
}

$form = new FormSolicitarCodigo();
$form->formularioShow();

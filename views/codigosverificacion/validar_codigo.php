<?php
// --- Iniciar sesión solo si no está iniciada ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../shared/Formulario.php';
include __DIR__ . '/../shared/header.php';

class FormValidarCodigo extends Formulario {

    public function formularioShow($usuario_id) {
        $this->cabeceraShow("Validar código");
        ?>

        <?php $uid = $_GET['uid'] ?? ''; ?>

        <form action="/controllers/RecuperarController.php?op=validar" method="POST">
            <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($uid) ?>">

            <label>Código recibido:</label>
            <input type="text" name="code" required>

            <button type="submit" name="btnValidar">Validar</button>
        </form>

        <p><a href="/index.php">Cancelar</a></p>

        <?php
        $this->piePaginaShow();
    }
}

$form = new FormValidarCodigo();
$form->formularioShow($_GET['uid']);

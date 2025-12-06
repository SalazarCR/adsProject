<?php
require_once __DIR__ . '/../shared/Formulario.php';

class FormActualizarPassword extends Formulario {

    public function formActualizarPasswordShow($datos) {
        $this->cabeceraShow("Actualizar contraseña");
        ?>

        <form action="/controllers/getUsuarios.php" method="POST" class="form-pass">

    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($datos['id']) ?>">

    <a href="../../views/home/dashboard.php" style="margin-left: 15px; text-decoration: underline; color: #333;">Retroceder</a>

    <div class="form-group">
        <label>Contraseña actual:</label>
        <input type="password" name="actual" required>
    </div>

    <div class="form-group">
        <label>Nueva contraseña:</label>
        <input type="password" name="nueva" required>
    </div>

    <div class="form-group">
        <label>Repetir nueva contraseña:</label>
        <input type="password" name="repetir" required>
    </div>

    <button type="submit" name="btnActualizarPassword" class="btn-primary btn-pass">
        Actualizar contraseña
    </button>

</form>


        <?php
        $this->piePaginaShow();
    }
}

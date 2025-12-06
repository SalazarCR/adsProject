<?php
// views/auth/FormAutenticarUsuario.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormAutenticarUsuario extends Formulario {

    public function formAutenticarUsuarioShow() {

        $this->cabeceraShow("Iniciar Sesión");
        ?>

        <center>

        <!-- FORMULARIO DE LOGIN -->
        <form action="/controllers/AuthController.php" method="POST" style="max-width:380px;">

            <label><b>Usuario</b></label><br>
            <input type="text" name="username" required class="input-text"><br><br>

            <label><b>Contraseña</b></label><br>
            <input type="password" name="password" required class="input-text"><br><br>

            <button type="submit" name="btnIniciar" class="btn-primary">
                Ingresar
            </button>

        </form>

        <br>

        <!-- SOLO UN ENLACE, COMO ANTES -->
        <a href="/controllers/RecuperarController.php?op=mostrarFormulario"
           class="link-recuperar">
            ¿Olvidaste tu contraseña?
        </a>

        </center>

        <?php
        $this->piePaginaShow();
    }
}
?>

<?php
// controllers/UsuarioController.php
require_once __DIR__ . '/../core/Usuario.php';
require_once __DIR__ . '/../views/usuarios/FormUsuarios.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';
require_once __DIR__ . '/../core/Session.php';

class UsuarioController {

    public function __construct() {
        Session::verificarSesion();
    }

    public function gestionar() {
        $objUsuario = new Usuario();

        // Leer parámetro GET de búsqueda
        $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

        if ($buscar != '') {
            $lista = $objUsuario->obtenerFiltrados($buscar);
        } else {
            $lista = $objUsuario->obtenerTodos();
        }

        $objForm = new FormUsuarios();
        $objForm->formListarUsuariosShow($lista);
    }


    public function mostrarCrear() {
        $objForm = new FormUsuarios();
        $objForm->formCrearUsuarioShow();
    }

    // [MODIFICADO] Recibe email y telefono
    public function registrar($nombre, $user, $pass, $rol, $estado, $email, $telefono) {
        $objUsuario = new Usuario();
        
        if ($objUsuario->verificarLogin($user)) {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(2, "El usuario ya existe", "getUsuarios.php?op=crear");
        } else {
            $objUsuario->crear($nombre, $user, $pass, $rol, $estado, $email, $telefono);
            header("Location: getUsuarios.php?op=listar");
        }
    }

    public function mostrarEditar($id) {
        $objUsuario = new Usuario();
        $datos = $objUsuario->obtenerPorId($id);

        if ($datos) {
            $objForm = new FormUsuarios();
            $objForm->formEditarUsuarioShow($datos);
        } else {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(3, "Usuario no encontrado", "getUsuarios.php?op=listar");
        }
    }

    // [MODIFICADO] Recibe email y telefono
    public function actualizar($id, $nombre, $user, $rol, $estado, $email, $telefono) {
        $objUsuario = new Usuario();
        
        if ($objUsuario->existeOtroUsuario($id, $user)) {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(2, "El nombre de usuario ya está en uso por otra persona", "getUsuarios.php?op=listar");
        } 
        else {
            $objUsuario->actualizar($id, $nombre, $user, $rol, $estado, $email, $telefono);
            header("Location: getUsuarios.php?op=listar");
        }
    }

    public function eliminar($id) {
        $objUsuario = new Usuario();
        $objUsuario->eliminar($id); 
        header("Location: getUsuarios.php?op=listar");
    }

    public function mostrarFormActualizarPassword($id) {
    $objUsuario = new Usuario();
    $datos = $objUsuario->obtenerPorId($id);

    if ($datos) {
        require_once __DIR__ . '/../views/usuarios/FormActualizarPassword.php';
        $form = new FormActualizarPassword();
        $form->formActualizarPasswordShow($datos);
    } else {
        $msg = new PantallaMensajeSistema();
        $msg->mensajeSistemaShow(3, "Usuario no encontrado", "getUsuarios.php?op=listar");
    }
}

    // ===============================================
// 7. ACTUALIZAR CONTRASEÑA DEL USUARIO LOGUEADO
// ===============================================
public function actualizarPasswordUsuario($id, $actual, $nueva, $repetir) {

    require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';
    $objMsg = new PantallaMensajeSistema();
    $objUsuario = new Usuario();

    // 1. Traer datos del usuario
    $datos = $objUsuario->obtenerPorId($id);

    if (!$datos) {
        $objMsg->mensajeSistemaShow(3, "Usuario no encontrado", "getUsuarios.php?op=listar");
        return;
    }

    // 2. Verificar contraseña actual
    if (!password_verify($actual, $datos['password'])) {
        $objMsg->mensajeSistemaShow(2, "La contraseña actual es incorrecta", "getUsuarios.php?op=actualizar_pass&id=$id");
        return;
    }

    // 3. Validar coincidencia nueva contraseña
    if ($nueva !== $repetir) {
        $objMsg->mensajeSistemaShow(2, "La nueva contraseña no coincide", "getUsuarios.php?op=actualizar_pass&id=$id");
        return;
    }

    // 4. Actualizar en BD
    $objUsuario->actualizarPassword($id, $nueva);

    // 5. Confirmación
    $objMsg->mensajeSistemaShow(1, "Contraseña actualizada correctamente", "../views/home/dashboard.php");
}


}
?>
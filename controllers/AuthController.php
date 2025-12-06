<?php
// controllers/AuthController.php
require_once __DIR__ . '/../core/Usuario.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';
require_once __DIR__ . '/../core/Session.php';

class AuthController {

    // Caso de uso: Autenticar Usuario
    public function validarUsuario($username, $password) {

        $objUsuario = new Usuario();
        $msg = new PantallaMensajeSistema();

        // 1. Verificar que el usuario exista
        if (!$objUsuario->verificarLogin($username)) {
            $msg->mensajeSistemaShow(
                3,
                "Usuario no existe",
                "/index.php"
            );
            exit;
        }

        // 2. Verificar password
        if (!$objUsuario->verificarPassword($username, $password)) {
            $msg->mensajeSistemaShow(
                3,
                "Password incorrecto",
                "/index.php"
            );
            exit;
        }

        // 3. Obtener datos y validar estado
        $datos = $objUsuario->obtenerDatos($username);

        if (!isset($datos['estado_tmp']) || $datos['estado_tmp'] !== 'activo') {
            $msg->mensajeSistemaShow(
                3,
                "Usuario inhabilitado",
                "/index.php"
            );
            exit;
        }

        // 4. Login exitoso → crear sesión
        Session::start();
        Session::set('usuario_id', $datos['id']);
        Session::set('nombre',     $datos['nombre']);
        Session::set('rol',        $datos['rol']);

        // Redirigir al Dashboard (ruta absoluta desde raíz web)
        header("Location: /views/home/dashboard.php");
        exit;
    }

    // Caso de uso: Cerrar Sesión
    public function logout() {
        Session::destroy();
        header("Location: /index.php");
        exit;
    }
}

// =======================================================
// DISPATCHER: aquí se ejecuta el caso de uso según el POST
// =======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnIniciar'])) {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new AuthController();
    $auth->validarUsuario($username, $password);
    exit;
}

// Si quisieras manejar logout vía URL, algo así:
// if (isset($_GET['op']) && $_GET['op'] === 'logout') {
//     $auth = new AuthController();
//     $auth->logout();
//     exit;
// }

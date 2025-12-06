<?php
// core/Session.php

class Session {

    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public static function exists($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    // --- AQUÍ ESTABA EL ERROR, CORREGIDO: ---
    public static function verificarSesion() {
        self::start();

        // 1. Si no hay ID en memoria, fuera.
        if (!self::exists('usuario_id')) {
            header("Location: ../../index.php"); 
            exit();
        }

        // 2. Validar contra BD usando OBJETO (La forma correcta)
        require_once __DIR__ . '/Usuario.php';
        
        $objUsuario = new Usuario(); // Instanciamos la entidad
        $user = $objUsuario->obtenerPorId(self::get('usuario_id'));

        // 3. Verificar si el usuario existe y sigue activo
        if (!$user || $user['estado_tmp'] !== 'activo') {
            self::destroy(); // Destruimos sesión por seguridad
            header("Location: ../../index.php?msg=sesion_invalidada");
            exit();
        }
    }
    // ----------------------------------------

    public static function rol() {
        return $_SESSION['rol'] ?? null;
    }

    public static function esAdmin() {
        return (self::rol() === 'admin');
    }

    public static function esAuxiliar() {
        return (self::rol() === 'auxiliar');
    }

    public static function esAnalista() {
        return (self::rol() === 'analista');
    }
}
?>
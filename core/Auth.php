<?php
require_once __DIR__ . '/Session.php';

class Auth {
    public static function check() {
        Session::start();
        return Session::get('usuario_id') !== null;
    }

    public static function user() {
        Session::start();
        return [
            'id' => Session::get('usuario_id'),
            'username' => Session::get('usuario'),
            'rol' => Session::get('rol'),
            'nombre' => Session::get('nombre')
        ];
    }

    public static function requireLogin() {
        Session::start();
        if (!Session::get('usuario_id')) {
        header('Location: ../../index.php');
        exit;
        }
    }


    public static function requireRol(array $roles = []) {
        self::requireLogin();
        $rol = Session::get('rol');
        if (!in_array($rol, $roles)) {
            $_SESSION['mensaje'] = "Acceso denegado: no tiene permiso.";
            header('Location: /views/home/dashboard.php');
            exit;
        }
    }
}

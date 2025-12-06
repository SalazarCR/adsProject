<?php
// controllers/RecuperarController.php

require_once __DIR__ . '/../core/Usuario.php';
require_once __DIR__ . '/../core/CodigoVerificacion.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

$msg          = new PantallaMensajeSistema();
$usuarioModel = new Usuario();
$codigoModel  = new CodigoVerificacion();

$op = $_GET['op'] ?? '';   // acción enviada por URL

switch ($op) {

    // ======================================================
    // 0. SOLO MOSTRAR FORMULARIO DE RECUPERACIÓN
    //    (desde el link en login)
    // ======================================================
    case 'mostrarFormulario':
        require_once __DIR__ . '/../views/codigosverificacion/solicitar_codigo.php';
        exit;


    // ======================================================
    // 1. SOLICITAR CÓDIGO (usuario ya escribió email/teléfono)
    // ======================================================
    case 'solicitar':

        // Validar botón del formulario
        if (!isset($_POST['btnSolicitar'])) {
            $msg->mensajeSistemaShow(
                3,
                "Acceso no permitido.",
                "/index.php"
            );
            exit;
        }

        $dato = trim($_POST['dato'] ?? '');

        if ($dato === '') {
            $msg->mensajeSistemaShow(
                3,
                "Debe ingresar un correo o número telefónico.",
                "/controllers/RecuperarController.php?op=mostrarFormulario"
            );
            exit;
        }

        // Buscar por correo o teléfono
        $user = filter_var($dato, FILTER_VALIDATE_EMAIL)
                ? $usuarioModel->obtenerPorEmail($dato)
                : $usuarioModel->obtenerPorTelefono($dato);

        if (!$user) {
            $msg->mensajeSistemaShow(
                3,
                "No se encontró ningún usuario con esos datos.",
                "/controllers/RecuperarController.php?op=mostrarFormulario"
            );
            exit;
        }

        // Generar código de 6 dígitos
        $codigo = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Limpiar códigos anteriores
        $codigoModel->eliminarCodigosUsuario($user['id']);

        // Insertar el nuevo código (10 min)
        $codigoModel->insertarCodigo($user['id'], $codigo, 10);

        // Mensaje (para la práctica mostramos el código)
        $msg->mensajeSistemaShow(
            1,
            "Se generó un código de verificación y fue enviado.<br><br>"
            . "Código para pruebas: <b>$codigo</b><br><br>"
            . "Ahora ingrese el código de verificación.",
            "/views/codigosverificacion/validar_codigo.php?uid=" . $user['id']
        );
        exit;


    // ======================================================
    // 2. VALIDAR CÓDIGO
    // ======================================================
    case 'validar':

        if (!isset($_POST['btnValidar'])) {
            $msg->mensajeSistemaShow(
                3,
                "Acceso no permitido.",
                "/index.php"
            );
            exit;
        }

        $usuario_id = $_POST['usuario_id'] ?? null;
        $code       = trim($_POST['code'] ?? '');

        $registro = $codigoModel->obtenerCodigoValido($usuario_id, $code);

        if (!$registro) {
            $msg->mensajeSistemaShow(
                3,
                "Código inválido o vencido.",
                "/views/codigosverificacion/validar_codigo.php?uid=$usuario_id"
            );
            exit;
        }

        // Marcar código como usado
        $codigoModel->marcarComoUsado($registro['id']);

        // Guardar permiso temporal en sesión
        session_start();
        $_SESSION['recuperar_usuario_id'] = $usuario_id;

        header("Location: /views/codigosverificacion/cambiar_password.php");
        exit;


    // ======================================================
    // 3. CAMBIAR CONTRASEÑA
    // ======================================================
    case 'cambiar':

        if (!isset($_POST['btnCambiar'])) {
            $msg->mensajeSistemaShow(
                3,
                "Acceso no permitido.",
                "/index.php"
            );
            exit;
        }

        session_start();
        $usuario_id = $_SESSION['recuperar_usuario_id'] ?? null;

        if (!$usuario_id) {
            $msg->mensajeSistemaShow(
                3,
                "Sesión de recuperación expirada.",
                "/index.php"
            );
            exit;
        }

        $p1 = $_POST['password1'] ?? '';
        $p2 = $_POST['password2'] ?? '';

        if ($p1 === '' || $p2 === '') {
            $msg->mensajeSistemaShow(
                3,
                "Debe completar ambos campos.",
                "/views/codigosverificacion/cambiar_password.php"
            );
            exit;
        }

        if ($p1 !== $p2) {
            $msg->mensajeSistemaShow(
                3,
                "Las contraseñas no coinciden.",
                "/views/codigosverificacion/cambiar_password.php"
            );
            exit;
        }

        $usuarioModel->actualizarPassword($usuario_id, $p1);
        unset($_SESSION['recuperar_usuario_id']);

        $msg->mensajeSistemaShow(
            1,
            "Contraseña actualizada correctamente.",
            "/index.php"
        );
        exit;


    // ======================================================
    // Cualquier otra cosa → acceso no permitido
    // ======================================================
    default:
        $msg->mensajeSistemaShow(
            3,
            "Acceso no permitido.",
            "/index.php"
        );
        exit;
}
?>

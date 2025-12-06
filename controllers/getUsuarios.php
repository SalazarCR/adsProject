<?php
// controllers/getUsuarios.php
require_once __DIR__ . '/UsuarioController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

function validarTexto($texto) {
    return (trim($texto) !== '');
}

$objControl = new UsuarioController();
$objMsg = new PantallaMensajeSistema();

/* =======================================================
   ZONA GET — AQUÍ CORREGIMOS EL PROBLEMA
   ======================================================= */

// Si no viene "op", o si solo viene "buscar", asumir listar
$op = $_GET['op'] ?? 'listar';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'listar':
            // Puede venir con ?buscar=...
            $objControl->gestionar();
            exit;

        case 'crear':
            $objControl->mostrarCrear();
            exit;

        case 'editar':
            if (isset($_GET['id'])) {
                $objControl->mostrarEditar($_GET['id']);
            } else {
                $objMsg->mensajeSistemaShow(3, "ID no válido", "getUsuarios.php?op=listar");
            }
            exit;

        case 'actualizar_pass':
            $id = $_GET['id'] ?? null;
        if ($id) {
            $objControl->mostrarFormActualizarPassword($id);
            exit;
        } else {
            $objMsg->mensajeSistemaShow(3, "ID inválido", "getUsuarios.php?op=listar");
            exit;
        }
        break;


        default:
            // Si op trae un valor desconocido
            $objMsg->mensajeSistemaShow(3, "Acceso no permitido", "../views/home/dashboard.php");
            exit;
    }
}

/* =======================================================
   ZONA POST
   ======================================================= */

if (validarBoton('btnIrCrear')) {

    $objControl->mostrarCrear();

} 
else if (validarBoton('btnIrEditar')) {

    $id = $_POST['id_usuario'];
    $objControl->mostrarEditar($id);

} 
else if (validarBoton('btnRegistrar')) {

    if (validarTexto($_POST['nombre']) && validarTexto($_POST['username']) && validarTexto($_POST['password'])) {

        $objControl->registrar(
            $_POST['nombre'], 
            $_POST['username'], 
            $_POST['password'], 
            $_POST['rol'], 
            $_POST['estado_tmp'],
            $_POST['email'],
            $_POST['telefono']
        );

    } else {
        $objMsg->mensajeSistemaShow(2, "Datos incompletos", "getUsuarios.php?op=crear");
    }

} 
else if (validarBoton('btnActualizar')) {

    if (validarTexto($_POST['nombre']) && validarTexto($_POST['username'])) {

        $objControl->actualizar(
            $_POST['id_usuario'], 
            $_POST['nombre'], 
            $_POST['username'], 
            $_POST['rol'], 
            $_POST['estado_tmp'],
            $_POST['email'],
            $_POST['telefono']
        );

    } else {
        $objMsg->mensajeSistemaShow(2, "Datos incompletos", "getUsuarios.php?op=listar");
    }

} 
else if (validarBoton('btnEliminar')) {

    $id = $_POST['id_usuario'];
    $objControl->eliminar($id);

} 
/* =======================================================
   ACTUALIZAR CONTRASEÑA DEL USUARIO DESDE EL PANEL
   ======================================================= */
else if (validarBoton('btnActualizarPassword')) {

    $id = $_POST['id_usuario'];
    $passActual = $_POST['actual'];
    $nuevaPass = $_POST['nueva'];
    $confirmPass = $_POST['repetir'];

    $objControl->actualizarPasswordUsuario(
        $id,
        $passActual,
        $nuevaPass,
        $confirmPass
    );
}

else {

    $objMsg->mensajeSistemaShow(3, "Acceso no permitido", "../views/home/dashboard.php");

}
?>

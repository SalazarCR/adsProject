<?php
// controllers/getReportes.php
require_once __DIR__ . '/ReporteController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

// Funciones de validación
function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

function validarTexto($texto) {
    return (trim($texto) !== '');
}

$objControl = new ReporteController();
$objMsg = new PantallaMensajeSistema();

// ============================================
// ZONA GET (Operaciones de lectura)
// ============================================
$op = $_GET['op'] ?? 'panel';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'panel':
        case 'menu':
            // Mostrar interfaz unificada de reportes
            $objControl->gestionar();
            exit;

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no permitida", "../views/home/dashboard.php");
            exit;
    }
}

// ============================================
// ZONA POST (Botones de formularios)
// ============================================

// 1. Botón: Buscar
if (validarBoton('btnBuscar')) {
    $nombre = $_POST['txtBuscar'] ?? '';

    // Validación según documento: "Campo vacío"
    if (!validarTexto($nombre)) {
        $objMsg->mensajeSistemaShow(2, "Campo vacío", "getReportes.php?op=panel");
        exit;
    }

    $objControl->gestionar($nombre);

// 2. Botón: Regresar
} else if (validarBoton('btnRegresar')) {
    header("Location: ../views/home/dashboard.php");
    exit;

// 3. Acceso denegado (Seguridad)
} else {
    $objMsg->mensajeSistemaShow(3, "Acceso denegado", "../views/home/dashboard.php");
}
?>

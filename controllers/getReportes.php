<?php
// controllers/getReportes.php
require_once __DIR__ . '/ReporteController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

$objControl = new ReporteController();
$objMsg = new PantallaMensajeSistema();

// --- ZONA GET ---
$op = $_GET['op'] ?? 'panel';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'panel':
            // Si hay búsqueda, la maneja el controlador automáticamente
            $objControl->mostrarReporte('todos');
            exit;

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no permitida", "../views/home/dashboard.php");
            exit;
    }
}


// --- ZONA POST (Botones de Filtro) ---

// 1. Filtrar Entradas
if (validarBoton('btnFiltrarEntradas')) {
    $ini = $_POST['fecha_inicio'] ?? '';
    $fin = $_POST['fecha_fin'] ?? '';
    $objControl->mostrarReporte('entrada', $ini, $fin);

// 2. Filtrar Salidas
} else if (validarBoton('btnFiltrarSalidas')) {
    $ini = $_POST['fecha_inicio'] ?? '';
    $fin = $_POST['fecha_fin'] ?? '';
    $objControl->mostrarReporte('salida', $ini, $fin);

// 3. Ver Todo (Reset)
} else if (validarBoton('btnVerTodo')) {
    $objControl->mostrarReporte('todos');

} else {
    $objMsg->mensajeSistemaShow(3, "Acceso denegado", "../views/home/dashboard.php");
}
?>
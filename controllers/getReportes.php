<?php
// controllers/getReportes.php
require_once __DIR__ . '/ReporteController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

// Funciones de validación
function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

$objControl = new ReporteController();
$objMsg = new PantallaMensajeSistema();

// ============================================
// ZONA GET (Operaciones de lectura)
// Similar a getMovimientos.php
// ============================================
$op = $_GET['op'] ?? 'listar';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'listar':
        case 'panel':
            // Vista principal de selección con historial
            // 'panel' es usado por el menú del analista, 'listar' es el valor por defecto
            $buscar = $_GET['buscar'] ?? '';
            $objControl->gestionar($buscar);
            exit;

        case 'entrada':
            // Búsqueda en Reporte de Entrada (GET)
            $buscar = $_GET['buscar'] ?? '';
            $pagina = (int)($_GET['paginaEntrada'] ?? 1);
            $objControl->mostrarEntrada('', '', $pagina, $buscar);
            exit;

        case 'salida':
            // Búsqueda en Reporte de Salida (GET)
            $buscar = $_GET['buscar'] ?? '';
            $pagina = (int)($_GET['paginaSalida'] ?? 1);
            $objControl->mostrarSalida('', '', $pagina, $buscar);
            exit;

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no permitida", "../views/home/dashboard.php");
            exit;
    }
}

// ============================================
// ZONA POST (Botones de formularios)
// Similar a getMovimientos.php
// ============================================

// 1. Botón: Ir a Generar Reporte de Entrada
if (validarBoton('btnIrEntrada')) {
    $objControl->mostrarEntrada('', '', 1, '');
    exit;
}

// 2. Botón: Ir a Generar Reporte de Salida
if (validarBoton('btnIrSalida')) {
    $objControl->mostrarSalida('', '', 1, '');
    exit;
}

// 3. Botón: Generar Reporte Entrada (con búsqueda)
if (validarBoton('btnGenerarEntrada')) {
    $buscar = $_POST['buscar'] ?? $_GET['buscar'] ?? '';
    $pagina = (int)($_POST['paginaEntrada'] ?? $_GET['paginaEntrada'] ?? 1);
    
    $objControl->mostrarEntrada('', '', $pagina, $buscar);
    exit;
}

// 4. Botón: Generar Reporte Salida (con búsqueda)
if (validarBoton('btnGenerarSalida')) {
    $buscar = $_POST['buscar'] ?? $_GET['buscar'] ?? '';
    $pagina = (int)($_POST['paginaSalida'] ?? $_GET['paginaSalida'] ?? 1);
    
    $objControl->mostrarSalida('', '', $pagina, $buscar);
    exit;
}

// 7. Navegación de páginas (GET)
if (isset($_GET['paginaEntrada'])) {
    $buscar = $_GET['buscar'] ?? '';
    $pagina = (int)($_GET['paginaEntrada'] ?? 1);
    $objControl->mostrarEntrada('', '', $pagina, $buscar);
    exit;
}

if (isset($_GET['paginaSalida'])) {
    $buscar = $_GET['buscar'] ?? '';
    $pagina = (int)($_GET['paginaSalida'] ?? 1);
    $objControl->mostrarSalida('', '', $pagina, $buscar);
    exit;
}

// 6. Acceso denegado (Seguridad)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $objMsg->mensajeSistemaShow(3, "Acceso denegado", "../views/home/dashboard.php");
    exit;
}
?>

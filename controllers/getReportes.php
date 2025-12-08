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
    $objControl->mostrarEntrada();
    exit;
}

// 2. Botón: Ir a Generar Reporte de Salida
if (validarBoton('btnIrSalida')) {
    $objControl->mostrarSalida();
    exit;
}

// 3. Botón: Generar Reporte Entrada (con filtros)
if (validarBoton('btnGenerarEntrada')) {
    $fechaInicio = $_POST['fechaInicioEntrada'] ?? '';
    $fechaFin = $_POST['fechaFinEntrada'] ?? '';
    $pagina = (int)($_POST['paginaEntrada'] ?? 1);
    
    $objControl->mostrarEntrada($fechaInicio, $fechaFin, $pagina);
    exit;
}

// 4. Botón: Generar Reporte Salida (con filtros)
if (validarBoton('btnGenerarSalida')) {
    $fechaInicio = $_POST['fechaInicioSalida'] ?? '';
    $fechaFin = $_POST['fechaFinSalida'] ?? '';
    $pagina = (int)($_POST['paginaSalida'] ?? 1);
    
    $objControl->mostrarSalida($fechaInicio, $fechaFin, $pagina);
    exit;
}

// 5. Navegación de páginas (GET)
if (isset($_GET['paginaEntrada'])) {
    $fechaInicio = $_GET['fechaInicioEntrada'] ?? '';
    $fechaFin = $_GET['fechaFinEntrada'] ?? '';
    $pagina = (int)($_GET['paginaEntrada'] ?? 1);
    
    $objControl->mostrarEntrada($fechaInicio, $fechaFin, $pagina);
    exit;
}

if (isset($_GET['paginaSalida'])) {
    $fechaInicio = $_GET['fechaInicioSalida'] ?? '';
    $fechaFin = $_GET['fechaFinSalida'] ?? '';
    $pagina = (int)($_GET['paginaSalida'] ?? 1);
    
    $objControl->mostrarSalida($fechaInicio, $fechaFin, $pagina);
    exit;
}

// 6. Acceso denegado (Seguridad)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $objMsg->mensajeSistemaShow(3, "Acceso denegado", "../views/home/dashboard.php");
    exit;
}
?>

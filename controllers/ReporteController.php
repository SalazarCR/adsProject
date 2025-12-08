<?php
// controllers/ReporteController.php
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../views/reportes/FormReportes.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

class ReporteController {

    public function __construct() {
        Session::verificarSesion();

        // Validar que solo el rol "admin" o "analista" puede acceder a reportes
        $rol = $_SESSION['rol'] ?? '';
        if ($rol !== 'admin' && $rol !== 'analista') {
            $objMsg = new PantallaMensajeSistema();
            $objMsg->mensajeSistemaShow(3, "Acceso denegado. Solo el administrador o analista puede generar reportes.", "../views/home/dashboard.php");
            exit;
        }
    }

    // ==========================================
    // 1. GESTIONAR - VISTA PRINCIPAL (LISTAR)
    // Similar a MovimientoController::gestionar()
    // ==========================================
    public function gestionar() {
        $objForm = new FormReportes();
        $objForm->formListarReportesShow();
    }

    // ==========================================
    // 2. MOSTRAR REPORTE DE ENTRADA
    // Similar a MovimientoController::mostrarEntrada()
    // ==========================================
    public function mostrarEntrada($fechaInicio = '', $fechaFin = '', $pagina = 1) {
        $objMov = new Movimiento();
        $porPagina = 10; // RF38: Registros paginados amigablemente

        // Obtener datos de entradas con paginación y fechas (RF37, RF38)
        $totalRegistros = $objMov->contarPorTipo('entrada', $fechaInicio, $fechaFin);
        $totalPaginas = ceil($totalRegistros / $porPagina);
        $entradas = $objMov->obtenerPorTipoPaginado('entrada', $fechaInicio, $fechaFin, $pagina, $porPagina);

        $objForm = new FormReportes();
        $objForm->formReporteEntradaShow(
            $entradas,
            $fechaInicio,
            $fechaFin,
            $pagina,
            $totalPaginas,
            $totalRegistros
        );
    }

    // ==========================================
    // 3. MOSTRAR REPORTE DE SALIDA
    // Similar a MovimientoController::mostrarSalida()
    // ==========================================
    public function mostrarSalida($fechaInicio = '', $fechaFin = '', $pagina = 1) {
        $objMov = new Movimiento();
        $porPagina = 10; // RF42: Registros paginados amigablemente

        // Obtener datos de salidas con paginación y fechas (RF42, RF43)
        $totalRegistros = $objMov->contarPorTipo('salida', $fechaInicio, $fechaFin);
        $totalPaginas = ceil($totalRegistros / $porPagina);
        $salidas = $objMov->obtenerPorTipoPaginado('salida', $fechaInicio, $fechaFin, $pagina, $porPagina);

        $objForm = new FormReportes();
        $objForm->formReporteSalidaShow(
            $salidas,
            $fechaInicio,
            $fechaFin,
            $pagina,
            $totalPaginas,
            $totalRegistros
        );
    }
}
?>

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
    // 1. GESTIONAR REPORTES DE INVENTARIO
    // Caso de Uso: Gestionar Reportes
    // Muestra historial de movimientos y opciones para generar reportes específicos
    // Incluye Kardex con saldo acumulado
    // ==========================================
    public function gestionar($buscar = '') {
        $objMov = new Movimiento();
        
        $movimientos = [];
        $kardex = [];
        
        // Si hay búsqueda, solo mostrar resultados de búsqueda
        if ($buscar !== '') {
            $movimientos = $objMov->obtenerFiltrados($buscar);
        } else {
            // Si no hay búsqueda, mostrar Kardex completo con saldo acumulado
            $kardex = $objMov->obtenerKardex();
            $movimientos = $objMov->obtenerTodos();
        }
        
        $objForm = new FormReportes();
        $objForm->formListarReportesShow($movimientos, $kardex);
    }

    // ==========================================
    // 2. MOSTRAR REPORTE DE ENTRADA DE INVENTARIO
    // Caso de Uso: Generar Reporte de Entrada
    // Obtiene movimientos de entrada con búsqueda general y paginación
    // ==========================================
    public function mostrarEntrada($fechaInicio = '', $fechaFin = '', $pagina = 1, $buscar = '') {
        $objMov = new Movimiento();
        $porPagina = 10;

        // Si hay búsqueda, filtrar por búsqueda general
        if ($buscar !== '') {
            $todasEntradas = $objMov->obtenerFiltrados($buscar);
            $entradas = array_filter($todasEntradas, function($m) {
                return $m['tipo'] == 'entrada';
            });
            $entradas = array_values($entradas);
            $totalRegistros = count($entradas);
            $totalPaginas = ceil($totalRegistros / $porPagina);
            
            // Aplicar paginación manual
            $offset = ($pagina - 1) * $porPagina;
            $entradas = array_slice($entradas, $offset, $porPagina);
        } else {
            // Sin búsqueda, usar método normal
            $totalRegistros = $objMov->contarPorTipo('entrada', $fechaInicio, $fechaFin);
            $totalPaginas = ceil($totalRegistros / $porPagina);
            $entradas = $objMov->obtenerPorTipoPaginado('entrada', $fechaInicio, $fechaFin, $pagina, $porPagina);
        }

        $objForm = new FormReportes();
        $objForm->formReporteEntradaShow(
            $entradas,
            $fechaInicio,
            $fechaFin,
            $pagina,
            $totalPaginas,
            $totalRegistros,
            $buscar
        );
    }

    // ==========================================
    // 3. MOSTRAR REPORTE DE SALIDA DE INVENTARIO
    // Caso de Uso: Generar Reporte de Salida
    // Obtiene movimientos de salida con búsqueda general y paginación
    // ==========================================
    public function mostrarSalida($fechaInicio = '', $fechaFin = '', $pagina = 1, $buscar = '') {
        $objMov = new Movimiento();
        $porPagina = 10;

        // Si hay búsqueda, filtrar por búsqueda general
        if ($buscar !== '') {
            $todasSalidas = $objMov->obtenerFiltrados($buscar);
            $salidas = array_filter($todasSalidas, function($m) {
                return $m['tipo'] == 'salida';
            });
            $salidas = array_values($salidas);
            $totalRegistros = count($salidas);
            $totalPaginas = ceil($totalRegistros / $porPagina);
            
            // Aplicar paginación manual
            $offset = ($pagina - 1) * $porPagina;
            $salidas = array_slice($salidas, $offset, $porPagina);
        } else {
            // Sin búsqueda, usar método normal
            $totalRegistros = $objMov->contarPorTipo('salida', $fechaInicio, $fechaFin);
            $totalPaginas = ceil($totalRegistros / $porPagina);
            $salidas = $objMov->obtenerPorTipoPaginado('salida', $fechaInicio, $fechaFin, $pagina, $porPagina);
        }

        $objForm = new FormReportes();
        $objForm->formReporteSalidaShow(
            $salidas,
            $fechaInicio,
            $fechaFin,
            $pagina,
            $totalPaginas,
            $totalRegistros,
            $buscar
        );
    }
}
?>

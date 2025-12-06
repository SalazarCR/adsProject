<?php
// controllers/ReporteController.php
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../views/reportes/FormReportes.php';

class ReporteController {

    public function __construct() {
        Session::verificarSesion();
    }

    // Método Único Inteligente
    public function mostrarReporte($tipo = 'todos', $ini = '', $fin = '') {
    $objMov = new Movimiento();

    // NUEVO: Si viene búsqueda, priorizamos búsqueda
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

    if ($buscar !== '') {
        // Búsqueda general
        $lista = $objMov->obtenerFiltradosReporte($buscar);
    } 
    else if ($tipo == 'todos') {
        $lista = $objMov->obtenerTodos();
    } 
    else {
        // Entrada o salida con fechas
        $lista = $objMov->obtenerPorTipo($tipo, $ini, $fin);
    }

    $objForm = new FormReportes();
    $objForm->formReporteShow($lista, $tipo, $ini, $fin);
}

    
}
?>
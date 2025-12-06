<?php
// controllers/ReporteController.php
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../views/reportes/FormReportes.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

class ReporteController {

    public function __construct() {
        Session::verificarSesion();

        // Validar que solo el rol "admin" puede acceder a reportes
        $rol = $_SESSION['rol'] ?? '';
        if ($rol !== 'admin') {
            $objMsg = new PantallaMensajeSistema();
            $objMsg->mensajeSistemaShow(3, "Acceso denegado. Solo el administrador puede generar reportes.", "../views/home/dashboard.php");
            exit;
        }
    }

    // ==========================================
    // GESTIONAR - MOSTRAR INTERFAZ UNIFICADA
    // ==========================================
    public function gestionar($buscar = '') {
        $objMov = new Movimiento();

        // Validación adicional de sesión según documento
        if (!isset($_SESSION['usuario_id'])) {
            $objMsg = new PantallaMensajeSistema();
            $objMsg->mensajeSistemaShow(3, "Surgió un error. Vuelva a iniciar sesión", "../../index.php");
            return;
        }

        // Obtener datos de entradas y salidas (siempre orden DESC = más reciente primero)
        $entradas = $objMov->obtenerPorTipoOrdenado('entrada', 'DESC', $buscar);
        $salidas = $objMov->obtenerPorTipoOrdenado('salida', 'DESC', $buscar);

        $objForm = new FormReportes();
        $objForm->formReporteUnificadoShow($entradas, $salidas, $buscar);
    }
}
?>

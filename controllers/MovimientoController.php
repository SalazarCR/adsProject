<?php
// controllers/MovimientoController.php
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Lote.php';
require_once __DIR__ . '/../core/Producto.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../views/movimientos/FormMovimientos.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

class MovimientoController {

    public function __construct() {
        Session::verificarSesion(); // Seguridad
    }

    // 1. GESTIONAR (LISTAR HISTORIAL)
    public function gestionar() {
    $objMov = new Movimiento();

    // Capturar búsqueda
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

    if ($buscar !== '') {
        $lista = $objMov->obtenerFiltrados($buscar);
    } else {
        $lista = $objMov->obtenerTodos();
    }

    $objForm = new FormMovimientos();
    $objForm->formListarMovimientosShow($lista);
}


    // 2. MOSTRAR FORMULARIO ENTRADA
    public function mostrarEntrada() {
        $objProd = new Producto();
        // Solo mostramos productos activos para comprar
        $productos = $objProd->obtenerTodosActivos(); 
        
        $objForm = new FormMovimientos();
        $objForm->formEntradaShow($productos);
    }

    // 3. MOSTRAR FORMULARIO SALIDA (Corregido: Sin duplicados)
    public function mostrarSalida($id_producto_seleccionado = null) {
        $objProd = new Producto();
        // Solo mostramos productos activos para vender
        $productos = $objProd->obtenerTodosActivos();
        
        $lotes = [];
        // Si ya seleccionó un producto, buscamos sus lotes con stock
        if ($id_producto_seleccionado) {
            $objLote = new Lote();
            $lotes = $objLote->obtenerLotesConStock($id_producto_seleccionado);
        }

        $objForm = new FormMovimientos();
        $objForm->formSalidaShow($productos, $lotes, $id_producto_seleccionado);
    }

    // 4. PROCESAR ENTRADA (COMPRA)
    public function registrarEntrada($prod_id, $cod_lote, $fecha_venc, $cant, $costo, $motivo) {
        $objLote = new Lote();
        $objMov = new Movimiento();
        $user_id = Session::get('usuario_id');

        try {
            // A. Crear el Lote (Stock Nuevo)
            $id_nuevo_lote = $objLote->crearEntrada($prod_id, $cod_lote, $fecha_venc, $cant, $costo);
            
            // B. Registrar en Historial
            $objMov->registrar($id_nuevo_lote, 'entrada', $cant, $motivo, $user_id);

            header("Location: getMovimientos.php?op=listar");
            exit;

        } catch (Exception $e) {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(3, "Error al registrar entrada: " . $e->getMessage(), "getMovimientos.php?op=listar");
        }
    }

    // 5. PROCESAR SALIDA (VENTA)
    public function registrarSalida($lote_id, $cant, $motivo) {
        $objLote = new Lote();
        $objMov = new Movimiento();
        $user_id = Session::get('usuario_id');

        try {
            // A. Descontar Stock (Valida si hay suficiente)
            $exito = $objLote->descontarStock($lote_id, $cant);

            if ($exito) {
                // B. Registrar en Historial
                $objMov->registrar($lote_id, 'salida', $cant, $motivo, $user_id);
                header("Location: getMovimientos.php?op=listar");
                exit;
            } else {
                // Flujo Alternativo: Stock insuficiente
                $msg = new PantallaMensajeSistema();
                $msg->mensajeSistemaShow(2, "Stock insuficiente en el lote seleccionado.", "getMovimientos.php?op=listar");
            }

        } catch (Exception $e) {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(3, "Error al registrar salida.", "getMovimientos.php?op=listar");
        }
    }
}
?>
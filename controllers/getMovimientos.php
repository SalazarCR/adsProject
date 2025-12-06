<?php
// controllers/getMovimientos.php
require_once __DIR__ . '/MovimientoController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

// --- VALIDACIONES DE TEORÍA ---
function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

// Inicialización
$objControl = new MovimientoController();
$objMsg = new PantallaMensajeSistema();

// --- ZONA GET ---
$op = $_GET['op'] ?? 'listar'; // valor por defecto

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'listar':
            // puede venir con ?buscar=
            $objControl->gestionar();
            exit;

        case 'cargar_lotes':
            $id_prod = $_GET['id_producto'];
            $objControl->mostrarSalida($id_prod);
            exit;

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no permitida", "../views/home/dashboard.php");
            exit;
    }
}


// --- ZONA POST (Botones) ---

// 1. Botón: Ir a Registrar Entrada
if (validarBoton('btnIrEntrada')) {
    $objControl->mostrarEntrada();

// 2. Botón: Ir a Registrar Salida
} else if (validarBoton('btnIrSalida')) {
    // Si seleccionó un producto para cargar sus lotes
    $id_prod = $_POST['id_producto_seleccionado'] ?? null;
    $objControl->mostrarSalida($id_prod);

// 3. Botón: GUARDAR ENTRADA
} else if (validarBoton('btnGuardarEntrada')) {
    $objControl->registrarEntrada(
        $_POST['producto_id'],
        $_POST['codigo_lote'],
        $_POST['fecha_vencimiento'],
        $_POST['cantidad'],
        $_POST['costo'],
        $_POST['motivo']
    );

// 4. Botón: GUARDAR SALIDA
} else if (validarBoton('btnGuardarSalida')) {
    $objControl->registrarSalida(
        $_POST['lote_id'],
        $_POST['cantidad'],
        $_POST['motivo']
    );

} else {
    // REGLA: Mensaje exacto si falla la validación de botón
    $objMsg->mensajeSistemaShow(3, "Acceso denegado", "../views/home/dashboard.php");
}
?>
<?php
// controllers/getProductos.php
require_once __DIR__ . '/ProductoController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

// --- VALIDACIONES DE TEORÍA ---
function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

function validarTexto($texto) {
    return (trim($texto) !== '');
}

$objControl = new ProductoController();
$objMsg = new PantallaMensajeSistema(); // Instancia única

// --- ZONA GET ---
// --- ZONA GET ---
$op = $_GET['op'] ?? 'listar'; // <-- Valor por defecto

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'listar':
            // Puede venir con ?buscar=...
            $objControl->gestionar();
            exit;

        case 'crear':
            $objControl->mostrarCrear();
            exit;

        case 'editar':
            $id = $_GET['id_producto'] ?? null;
            if ($id) {
                $objControl->mostrarEditar($id);
            } else {
                $objMsg->mensajeSistemaShow(3, "ID inválido", "getProductos.php?op=listar");
            }
            exit;

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no válida", "../views/home/dashboard.php");
            exit;
    }
}

// --- ZONA POST ---

// 1. Ir a Crear
if (validarBoton('btnIrCrear')) {
    $objControl->mostrarCrear();

// 2. Ir a Editar
} else if (validarBoton('btnIrEditar')) {
    $id = $_POST['id_producto'] ?? null;
    if ($id) {
        header("Location: getProductos.php?op=editar&id_producto=" . urlencode($id));
        exit;
    } else {
        $objMsg->mensajeSistemaShow(2, "Seleccione un producto válido", "getProductos.php?op=listar");
    }

// 3. Registrar (Crear)
} else if (validarBoton('btnRegistrar')) {
    if (validarTexto($_POST['nombre']) && validarTexto($_POST['codigo_interno'])) {
        
        // Convertir vacíos a NULL para evitar error de FK
        $cat_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
        $prov_id = !empty($_POST['proveedor_id']) ? $_POST['proveedor_id'] : null;

        $objControl->registrar(
            $_POST['nombre'], 
            $_POST['codigo_interno'], 
            $_POST['descripcion'], 
            $_POST['precio_venta'], 
            $cat_id, 
            $prov_id,
            $_POST['estado']
        );
    } else {
        $objMsg->mensajeSistemaShow(2, "Datos incompletos: Nombre y Código obligatorios", "getProductos.php?op=crear");
    }

// 4. Actualizar (Guardar Edición)
} else if (validarBoton('btnActualizar')) {
    if (validarTexto($_POST['nombre']) && validarTexto($_POST['codigo_interno'])) {
        
        $cat_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;
        $prov_id = !empty($_POST['proveedor_id']) ? $_POST['proveedor_id'] : null;

        $objControl->actualizar(
            $_POST['id_producto'],
            $_POST['nombre'], 
            $_POST['codigo_interno'], 
            $_POST['descripcion'], 
            $_POST['precio_venta'], 
            $cat_id, 
            $prov_id,
            $_POST['estado']
        );
    } else {
        $objMsg->mensajeSistemaShow(2, "Datos incompletos al actualizar", "getProductos.php?op=listar");
    }

} else {
    $objMsg->mensajeSistemaShow(3, "ACCESO DENEGADO: Use los botones del sistema", "../views/home/dashboard.php");
}
?>
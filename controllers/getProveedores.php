<?php
// controllers/getProveedores.php
require_once __DIR__ . '/ProveedorController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

// --- VALIDACIONES DE TEORÍA ---
function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

function validarTexto($texto) {
    return (trim($texto) !== '');
}

$objControl = new ProveedorController();
$objMsg = new PantallaMensajeSistema();

// --- ZONA GET ---
$op = $_GET['op'] ?? 'listar'; // Valor por defecto

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'listar':
            // Permite búsqueda con ?buscar=
            $objControl->gestionar();
            exit;

        case 'crear':
            $objControl->mostrarCrear();
            exit;

        case 'editar':
            if (isset($_GET['id_proveedor'])) {
                $objControl->mostrarEditar($_GET['id_proveedor']);
                exit;
            } else {
                $objMsg->mensajeSistemaShow(3, "ID no válido", "getProveedores.php?op=listar");
                exit;
            }

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no válida", "../views/home/dashboard.php");
            exit;
    }
}


// --- ZONA POST (Botones) ---

// 1. CASO: BOTÓN "IR A CREAR"
if (validarBoton('btnIrCrear')) {
    $objControl->mostrarCrear();

// 2. CASO: BOTÓN "IR A EDITAR"
} else if (validarBoton('btnIrEditar')) {
    $id = $_POST['id_proveedor'];
    $objControl->mostrarEditar($id);

// 3. CASO: BOTÓN "REGISTRAR" (Guardar nuevo)
} else if (validarBoton('btnRegistrar')) {
    if (validarTexto($_POST['nombre'])) { // Solo validamos nombre como crítico
        $objControl->registrar(
            $_POST['nombre'], $_POST['contacto'], $_POST['telefono'], $_POST['direccion'], $_POST['estado'] ?? 'activo'
        );
    } else {
        $objMsg->mensajeSistemaShow(2, "El nombre del proveedor es obligatorio.", "getProveedores.php?op=crear");
    }

// 4. CASO: BOTÓN "ACTUALIZAR"
} else if (validarBoton('btnActualizar')) {
    if (validarTexto($_POST['nombre'])) {
        $objControl->actualizar(
            $_POST['id_proveedor'], $_POST['nombre'], $_POST['contacto'], $_POST['telefono'], $_POST['direccion'], $_POST['estado']
        );
    } else {
        $objMsg->mensajeSistemaShow(2, "El nombre del proveedor es obligatorio.", "getProveedores.php?op=listar");
    }


} 
?>
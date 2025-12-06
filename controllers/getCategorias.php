<?php
// controllers/getCategorias.php
require_once __DIR__ . '/CategoriaController.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

// --- VALIDACIONES DE TEORÍA ---
function validarBoton($nombreBoton) {
    return isset($_POST[$nombreBoton]);
}

function validarTexto($texto) {
    return (trim($texto) !== '');
}

$objControl = new CategoriaController();
$objMsg = new PantallaMensajeSistema();

// --- ZONA GET ---
$op = $_GET['op'] ?? 'listar';  // Valor por defecto

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($op) {

        case 'listar':
            // Puede venir con ?buscar=
            $objControl->gestionar();
            exit;

        case 'crear':
            $objControl->mostrarCrear();
            exit;

        case 'editar':
            if (isset($_GET['id_categoria'])) {
                $objControl->mostrarEditar($_GET['id_categoria']);
                exit;
            } else {
                $objMsg->mensajeSistemaShow(3, "ID no válido", "getCategorias.php?op=listar");
                exit;
            }

        default:
            $objMsg->mensajeSistemaShow(3, "Operación no permitida", "../views/home/dashboard.php");
            exit;
    }
}


// --- ZONA POST ---

// 1. Botón Ir a Crear
if (validarBoton('btnIrCrear')) {
    $objControl->mostrarCrear();

// 2. Botón Ir a Editar
} else if (validarBoton('btnIrEditar')) {
    $id = $_POST['id_categoria'] ?? null;
    if ($id) {
        $objControl->mostrarEditar($id);
    } else {
        $objMsg->mensajeSistemaShow(2, "Seleccione una categoría válida", "getCategorias.php?op=listar");
    }

// 3. Botón Registrar (Guardar)
} else if (validarBoton('btnRegistrar')) {
    if (validarTexto($_POST['nombre'])) {
        $objControl->registrar(
            $_POST['nombre'], 
            $_POST['descripcion'], 
            $_POST['estado']
        );
    } else {
        $objMsg->mensajeSistemaShow(2, "El nombre de la categoría es obligatorio", "getCategorias.php?op=crear");
    }

// 4. Botón Actualizar
} else if (validarBoton('btnActualizar')) {
    if (validarTexto($_POST['nombre'])) {
        $objControl->actualizar(
            $_POST['id_categoria'],
            $_POST['nombre'], 
            $_POST['descripcion'], 
            $_POST['estado']
        );
    } else {
        $objMsg->mensajeSistemaShow(2, "El nombre de la categoría es obligatorio", "getCategorias.php?op=listar");
    }

} else {
    // Seguridad: Acceso sin botón
    $objMsg->mensajeSistemaShow(3, "ACCESO DENEGADO", "../views/home/dashboard.php");
}
?>
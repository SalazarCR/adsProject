<?php
// controllers/ProveedorController.php
require_once __DIR__ . '/../core/Proveedor.php';
require_once __DIR__ . '/../views/proveedores/FormProveedores.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';
require_once __DIR__ . '/../core/Session.php';

class ProveedorController {

    public function __construct() {
        Session::verificarSesion();
        // Nota: Añadir aquí verificación de rol Admin si es necesario (Session::esAdmin())
    }

    // 1. Muestra la lista
    public function gestionar() {
    $objProveedor = new Proveedor();

    // Capturar filtro
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

    if ($buscar !== '') {
        $lista = $objProveedor->obtenerFiltrados($buscar);
    } else {
        $lista = $objProveedor->obtenerTodos();
    }

    $objForm = new FormProveedores();
    $objForm->formListarProveedoresShow($lista);
}


    // 2. Muestra el formulario vacío
    public function mostrarCrear() {
        $objForm = new FormProveedores();
        $objForm->formCrearProveedorShow();
    }

    // 3. Lógica para guardar en BD
    public function registrar($nombre, $contacto, $telefono, $direccion, $estado='activo') {
        $objProveedor = new Proveedor();
        
        // **REGLA DE NEGOCIO:** Lógica para evitar nombres duplicados
        // Simplificación: Asumimos que la BD maneja el UNIQUE, si falla, mostramos error genérico.
        
        try {
            $objProveedor->crear($nombre, $contacto, $telefono, $direccion, $estado);
            header("Location: getProveedores.php?op=listar");
        } catch (PDOException $e) {
            $msg = new PantallaMensajeSistema();
            // Error 23000 es la violación de integridad (DUPLICADO)
            $msg->mensajeSistemaShow(2, "Error: Proveedor ya existe o datos incompletos.", "getProveedores.php?op=crear");
        }
    }

    // 4. Muestra el formulario con datos cargados
    public function mostrarEditar($id) {
        $objProveedor = new Proveedor();
        $datos = $objProveedor->obtenerPorId($id);

        if ($datos) {
            $objForm = new FormProveedores();
            $objForm->formEditarProveedorShow($datos);
        } else {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(3, "Proveedor no encontrado", "getProveedores.php?op=listar");
        }
    }

    // 5. Lógica para actualizar en BD
    public function actualizar($id, $nombre, $contacto, $telefono, $direccion, $estado) {
        $objProveedor = new Proveedor();
        
        try {
            $objProveedor->actualizar($id, $nombre, $contacto, $telefono, $direccion, $estado);
            header("Location: getProveedores.php?op=listar");
        } catch (PDOException $e) {
             $msg = new PantallaMensajeSistema();
             $msg->mensajeSistemaShow(2, "Error: El nombre del proveedor ya existe.", "getProveedores.php?op=listar");
        }
    }

 
}
?>
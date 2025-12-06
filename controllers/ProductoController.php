<?php
// controllers/ProductoController.php

// 1. IMPORTACIONES OBLIGATORIAS (Respetando arquitectura)
require_once __DIR__ . '/../core/Producto.php';
require_once __DIR__ . '/../core/Proveedor.php';
require_once __DIR__ . '/../core/Categoria.php'; // [NUEVO] Importamos el modelo real
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../views/productos/FormProductos.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php'; 

class ProductoController {

    // CONSTRUCTOR: Validación de Sesión (Teoría: Seguridad primero)
    public function __construct() {
        Session::verificarSesion();
    }

    // ==========================================
    // 1. GESTIONAR (LISTAR)
    // ==========================================
    public function gestionar() {
    $objProducto = new Producto();

    // Buscar si viene ?buscar=
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

    if ($buscar != '') {
        $lista = $objProducto->obtenerFiltrados($buscar);
    } else {
        $lista = $objProducto->obtenerTodos();
    }

    $objForm = new FormProductos();
    $objForm->formListarProductosShow($lista);
}

    // ==========================================
    // 2. MOSTRAR FORMULARIO DE CREACIÓN (CORREGIDO PASO 2)
    // ==========================================
    public function mostrarCrear() {
        // A. Obtener Proveedores Reales
        $objProveedor = new Proveedor();
        $proveedores = $objProveedor->obtenerTodosActivos(); 
        
        // B. Obtener Categorías REALES de la BD (Adiós datos falsos)
        $objCategoria = new Categoria();
        $categorias = $objCategoria->obtenerTodos(); 
        
        // Si la lista de categorías está vacía, el <select> saldrá vacío, 
        // pero ya no dará error de "Foreign Key" si se seleccionan datos válidos.
        
        $objForm = new FormProductos();
        $objForm->formCrearProductoShow($categorias, $proveedores);
    }

    // ==========================================
    // 3. REGISTRAR (GUARDAR EN BD)
    // ==========================================
    public function registrar($nombre, $codigo_interno, $descripcion, $precio_venta, $categoria_id, $proveedor_id, $estado) {
        $objProducto = new Producto();
        $objMsg = new PantallaMensajeSistema(); // Instancia para Mensaje Único

        try {
            // A. Validación Lógica: Verificar duplicados antes de intentar insertar
            if ($objProducto->existeProducto($nombre, $codigo_interno)) {
                // Mensaje Estándar: Tipo 2 (Advertencia).
                $objMsg->mensajeSistemaShow(2, "Error: Ya existe un producto con ese Nombre o Código Interno.", "getProductos.php?op=crear");
                return; 
            }

            // B. Inserción: Procedemos a crear pasando todos los datos + estado
            $objProducto->crear($nombre, $codigo_interno, $descripcion, $precio_venta, $categoria_id, $proveedor_id, $estado);
            
            // C. Éxito: Redirección limpia
            header("Location: getProductos.php?op=listar"); 
            exit; 

        } catch (PDOException $e) {
            // D. Manejo de Errores de BD (Backup de seguridad)
            if ($e->getCode() == 23000) { 
                $objMsg->mensajeSistemaShow(2, "Error de Base de Datos: Dato duplicado o referencia inválida.", "getProductos.php?op=crear");
            } else {
                $objMsg->mensajeSistemaShow(3, "Error crítico al registrar producto. Verifique Categorías y Proveedores.", "getProductos.php?op=listar");
            }
        }
    }

    // ==========================================
    // 4. MOSTRAR FORMULARIO DE EDICIÓN (CORREGIDO PASO 3)
    // ==========================================
    public function mostrarEditar($id) {
        $objProducto = new Producto();
        $producto = $objProducto->obtenerPorId($id);

        if ($producto) {
            // A. Obtener Proveedores Reales
            $objProveedor = new Proveedor();
            $proveedores = $objProveedor->obtenerTodosActivos(); 
            
            // B. Obtener Categorías REALES de la BD (Igual que en crear)
            $objCategoria = new Categoria();
            $categorias = $objCategoria->obtenerTodos(); 

            $objForm = new FormProductos();
            $objForm->formEditarProductoShow($producto, $categorias, $proveedores);
        } else {
            // Mensaje Estándar si manipulan el ID
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(3, "Producto no encontrado en el sistema.", "getProductos.php?op=listar");
        }
    }
    
    // ==========================================
    // 5. ACTUALIZAR (GUARDAR CAMBIOS)
    // ==========================================
    public function actualizar($id, $nombre, $codigo, $desc, $precio, $cat_id, $prov_id, $estado) {
        $objProducto = new Producto();
        $objMsg = new PantallaMensajeSistema();
        
        try {
            $objProducto->actualizar($id, $nombre, $codigo, $desc, $precio, $cat_id, $prov_id, $estado);
            header("Location: getProductos.php?op=listar");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $objMsg->mensajeSistemaShow(2, "Error: El código o nombre ya está en uso.", "getProductos.php?op=listar");
            } else {
                $objMsg->mensajeSistemaShow(3, "Error al actualizar los datos.", "getProductos.php?op=listar");
            }
        }
    }
    
    // ==========================================
    // 6. ELIMINAR (SOFT DELETE)
    // ==========================================
    public function eliminar($id) {
        $objProducto = new Producto();
        // Llamada a eliminación lógica (UPDATE estado)
        $objProducto->inhabilitar($id); 
        header("Location: getProductos.php?op=listar");
        exit;
    }
}
?>
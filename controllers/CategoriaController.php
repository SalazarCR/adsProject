<?php
// controllers/CategoriaController.php
require_once __DIR__ . '/../core/Categoria.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../views/categorias/FormCategorias.php';
require_once __DIR__ . '/../views/shared/PantallaMensajeSistema.php';

class CategoriaController {

    public function __construct() {
        Session::verificarSesion();
    }

    // 1. GESTIONAR
    public function gestionar() {
    $objCat = new Categoria();

    // Leer búsqueda
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

    if ($buscar !== '') {
        $lista = $objCat->obtenerFiltrados($buscar);
    } else {
        $lista = $objCat->obtenerTodos();
    }

    $objForm = new FormCategorias();
    $objForm->formListarCategoriasShow($lista);
}


    // 2. MOSTRAR CREAR
    public function mostrarCrear() {
        $objForm = new FormCategorias();
        $objForm->formCrearCategoriaShow();
    }

    // 3. REGISTRAR
    public function registrar($nombre, $descripcion, $estado) {
        $objCat = new Categoria();
        $objMsg = new PantallaMensajeSistema();

        try {
            // Verificar duplicado
            if ($objCat->existeCategoria($nombre)) {
                $objMsg->mensajeSistemaShow(2, "La categoría '$nombre' ya existe.", "getCategorias.php?op=crear");
                return;
            }

            $objCat->crear($nombre, $descripcion, $estado);
            header("Location: getCategorias.php?op=listar");
            exit;

        } catch (PDOException $e) {
            $objMsg->mensajeSistemaShow(3, "Error de BD al crear categoría.", "getCategorias.php?op=listar");
        }
    }

    // 4. MOSTRAR EDITAR
    public function mostrarEditar($id) {
        $objCat = new Categoria();
        $datos = $objCat->obtenerPorId($id);

        if ($datos) {
            $objForm = new FormCategorias();
            $objForm->formEditarCategoriaShow($datos);
        } else {
            $msg = new PantallaMensajeSistema();
            $msg->mensajeSistemaShow(3, "Categoría no encontrada", "getCategorias.php?op=listar");
        }
    }

    // 5. ACTUALIZAR
    public function actualizar($id, $nombre, $descripcion, $estado) {
        $objCat = new Categoria();
        $objMsg = new PantallaMensajeSistema();

        try {
            // Verificar duplicado excluyendo el ID actual
            if ($objCat->existeCategoria($nombre, $id)) {
                $objMsg->mensajeSistemaShow(2, "Ya existe otra categoría con el nombre '$nombre'.", "getCategorias.php?op=listar");
                return;
            }

            $objCat->actualizar($id, $nombre, $descripcion, $estado);
            header("Location: getCategorias.php?op=listar");
            exit;

        } catch (PDOException $e) {
            $objMsg->mensajeSistemaShow(3, "Error al actualizar categoría.", "getCategorias.php?op=listar");
        }
    }

    // 6. ELIMINAR (Soft Delete)
    public function eliminar($id) {
        $objCat = new Categoria();
        $objCat->inhabilitar($id);
        header("Location: getCategorias.php?op=listar");
        exit;
    }
}
?>
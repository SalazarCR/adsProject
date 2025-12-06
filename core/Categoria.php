<?php
// core/Categoria.php
require_once __DIR__ . '/../config/conexion.php';

class Categoria extends Conexion {

    // 1. LISTAR (Para gestión interna, trae todo)
    public function obtenerTodos() {
        $this->conectar();
        $sql = "SELECT * FROM categorias ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerFiltrados($buscar) {
    $this->conectar();

    $sql = "SELECT *
            FROM categorias
            WHERE nombre LIKE ?
               OR descripcion LIKE ?
               OR estado LIKE ?
            ORDER BY id DESC";

    $like = "%$buscar%";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$like, $like, $like]);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->desconectar();
    return $res;
}


    // 1.1 LISTAR ACTIVOS (Para llenar <select> en otros módulos)
    public function obtenerActivos() {
        $this->conectar();
        $sql = "SELECT * FROM categorias WHERE estado = 'activo' ORDER BY nombre ASC";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 2. CREAR
    public function crear($nombre, $descripcion, $estado) {
        $this->conectar();
        $sql = "INSERT INTO categorias(nombre, descripcion, estado) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nombre, $descripcion, $estado]);
        $this->desconectar();
        return $res;
    }

    // 3. OBTENER POR ID
    public function obtenerPorId($id) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 4. ACTUALIZAR
    public function actualizar($id, $nombre, $descripcion, $estado) {
        $this->conectar();
        $sql = "UPDATE categorias SET nombre=?, descripcion=?, estado=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nombre, $descripcion, $estado, $id]);
        $this->desconectar();
        return $res;
    }

    // 5. INHABILITAR (Soft Delete)
    public function inhabilitar($id) {
        $this->conectar();
        $sql = "UPDATE categorias SET estado = 'inactivo' WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$id]);
        $this->desconectar();
        return $res;
    }

    // 6. VALIDAR EXISTENCIA (Evitar nombres duplicados)
    public function existeCategoria($nombre, $id_excluir = null) {
        $this->conectar();
        if ($id_excluir) {
            // Caso Editar: Buscar duplicado que NO sea yo mismo
            $sql = "SELECT COUNT(*) FROM categorias WHERE nombre = ? AND id != ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nombre, $id_excluir]);
        } else {
            // Caso Crear
            $sql = "SELECT COUNT(*) FROM categorias WHERE nombre = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nombre]);
        }
        $count = $stmt->fetchColumn();
        $this->desconectar();
        return ($count > 0);
    }
}
?>
<?php
// core/Proveedor.php
require_once __DIR__ . '/../config/conexion.php';

// Debe heredar de Conexion
class Proveedor extends Conexion {

    // ==========================================
    // MÉTODOS DE GESTIÓN (CRUD)
    // ==========================================

    // 1. LISTAR (SELECT)
    public function obtenerTodos() {
        $this->conectar();
        $stmt = $this->db->query("SELECT * FROM proveedores ORDER BY id DESC");
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerFiltrados($buscar) {
    $this->conectar();

    $sql = "SELECT *
            FROM proveedores
            WHERE nombre LIKE ?
               OR contacto LIKE ?
               OR telefono LIKE ?
               OR direccion LIKE ?
               OR estado LIKE ?
            ORDER BY id DESC";

    $like = "%$buscar%";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$like, $like, $like, $like, $like]);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->desconectar();
    return $res;
}

    // 2. CREAR (INSERT)
    public function crear($nombre, $contacto, $telefono, $direccion, $estado) {
        $this->conectar();
        $sql = "INSERT INTO proveedores(nombre, contacto, telefono, direccion, estado) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nombre, $contacto, $telefono, $direccion, $estado]);
        $this->desconectar();
        return $res;
    }

    // 3. OBTENER POR ID
    public function obtenerPorId($id) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM proveedores WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 4. ACTUALIZAR (UPDATE)
    public function actualizar($id, $nombre, $contacto, $telefono, $direccion, $estado) {
        $this->conectar();
        $sql = "UPDATE proveedores 
                SET nombre=?, contacto=?, telefono=?, direccion=?, estado=?
                WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nombre, $contacto, $telefono, $direccion, $estado, $id]);
        $this->desconectar();
        return $res;
    }

    // 5. ELIMINAR LÓGICO (UPDATE a Inactivo) - CUMPLE TEORÍA
    // El profesor pide soft delete
    public function eliminarLogico($id) {
        $this->conectar();
        $sql = "UPDATE proveedores SET estado = 'inactivo' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$id]);
        $this->desconectar();
        return $res;
    }

    // Nuevo método para listar solo proveedores activos
    public function obtenerTodosActivos() {
        $this->conectar();
        // Filtra explícitamente por estado activo
        $stmt = $this->db->query("SELECT * FROM proveedores WHERE estado = 'activo' ORDER BY id DESC");
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }
}
?>
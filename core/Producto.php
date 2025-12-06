<?php
// core/Producto.php
require_once __DIR__ . '/../config/conexion.php';

class Producto extends Conexion {

    // 1. LISTAR (CON CÁLCULO DE STOCK REAL)
    public function obtenerTodos() {
        $this->conectar();
        
        // CORRECCIÓN CLAVE:
        // Usamos una SUB-CONSULTA (SELECT SUM...) para sumar el stock de la tabla 'lotes'.
        // COALESCE(..., 0) sirve para que si no hay lotes, muestre 0 en vez de vacío.
        
        $sql = "SELECT p.*, 
                       c.nombre AS categoria_nombre, 
                       pr.nombre AS proveedor_nombre,
                       COALESCE((SELECT SUM(stock_actual) FROM lotes WHERE producto_id = p.id AND estado != 'agotado'), 0) as stock
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
                ORDER BY p.id DESC";
                
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 2. CREAR
    public function crear($nombre, $codigo, $descripcion, $precio, $categoria_id, $proveedor_id, $estado) {
        $this->conectar();
        $sql = "INSERT INTO productos(nombre, codigo_interno, descripcion, precio_venta, categoria_id, proveedor_id, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nombre, $codigo, $descripcion, $precio, $categoria_id, $proveedor_id, $estado]);
        $this->desconectar();
        return $res;
    }

    // 3. OBTENER POR ID
    public function obtenerPorId($id) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 4. ACTUALIZAR
    public function actualizar($id, $nombre, $codigo, $descripcion, $precio, $categoria_id, $proveedor_id, $estado) {
        $this->conectar();
        $sql = "UPDATE productos 
                SET nombre=?, codigo_interno=?, descripcion=?, precio_venta=?, categoria_id=?, proveedor_id=?, estado=?
                WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nombre, $codigo, $descripcion, $precio, $categoria_id, $proveedor_id, $estado, $id]);
        $this->desconectar();
        return $res;
    }

    // 5. INHABILITAR (Soft Delete)
    public function inhabilitar($id) {
        $this->conectar();
        $sql = "UPDATE productos SET estado = 'inactivo' WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$id]);
        $this->desconectar();
        return $res;
    }

    // 6. VERIFICAR EXISTENCIA (Para validación en controlador)
    public function existeProducto($nombre, $codigo_interno) {
        $this->conectar(); 
        $sql = "SELECT COUNT(*) FROM productos WHERE nombre = ? OR codigo_interno = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre, $codigo_interno]);
        $cantidad = $stmt->fetchColumn(); 
        $this->desconectar(); 
        return ($cantidad > 0);


    
    }

// Método para llenar COMBOBOXES (Solo activos)
    public function obtenerTodosActivos() {
        $this->conectar();
        // Misma lógica de stock, pero filtrando WHERE p.estado = 'activo'
        $sql = "SELECT p.*, 
                       c.nombre AS categoria_nombre, 
                       pr.nombre AS proveedor_nombre,
                       COALESCE((SELECT SUM(stock_actual) FROM lotes WHERE producto_id = p.id AND estado != 'agotado'), 0) as stock
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
                WHERE p.estado = 'activo'  -- FILTRO CLAVE
                ORDER BY p.nombre ASC";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerFiltrados($buscar) {
    $this->conectar();

    $sql = "SELECT p.*, 
                   c.nombre AS categoria_nombre, 
                   pr.nombre AS proveedor_nombre,
                   COALESCE((SELECT SUM(stock_actual) 
                             FROM lotes 
                             WHERE producto_id = p.id 
                               AND estado != 'agotado'), 0) as stock
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN proveedores pr ON p.proveedor_id = pr.id
            WHERE p.nombre LIKE ?
               OR p.codigo_interno LIKE ?
               OR c.nombre LIKE ?
               OR pr.nombre LIKE ?
            ORDER BY p.id DESC";

    $like = "%$buscar%";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$like, $like, $like, $like]);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->desconectar();
    return $res;
}

}
?>
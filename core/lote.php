<?php
// core/Lote.php
require_once __DIR__ . '/../config/conexion.php';

class Lote extends Conexion {

    // 1. REGISTRAR UN NUEVO LOTE (ENTRADA)
    public function crearEntrada($producto_id, $codigo_lote, $fecha_venc, $cantidad, $costo) {
        $this->conectar();
        // Insertamos el lote con su stock inicial
        $sql = "INSERT INTO lotes (producto_id, codigo_lote, fecha_vencimiento, stock_actual, costo_unitario, estado) 
                VALUES (?, ?, ?, ?, ?, 'activo')";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$producto_id, $codigo_lote, $fecha_venc, $cantidad, $costo]);
        
        // Obtenemos el ID del lote recién creado para registrar el movimiento luego
        $id_lote = $this->db->lastInsertId();
        
        $this->desconectar();
        return $id_lote;
    }

    // 2. BUSCAR LOTES DISPONIBLES (Para el formulario de SALIDA)
    public function obtenerLotesConStock($producto_id) {
        $this->conectar();
        // Solo traemos lotes activos y con stock > 0
        $sql = "SELECT * FROM lotes 
                WHERE producto_id = ? AND estado = 'activo' AND stock_actual > 0 
                ORDER BY fecha_vencimiento ASC"; // FIFO: Primero vence, primero sale
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$producto_id]);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 3. DESCONTAR STOCK (SALIDA)
    public function descontarStock($id_lote, $cantidad) {
        $this->conectar();
        
        // A. Verificar stock actual antes de restar
        $stmtCheck = $this->db->prepare("SELECT stock_actual FROM lotes WHERE id = ?");
        $stmtCheck->execute([$id_lote]);
        $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if ($row['stock_actual'] < $cantidad) {
            $this->desconectar();
            return false; // Error: No hay suficiente stock en este lote
        }

        // B. Restar
        $nuevo_stock = $row['stock_actual'] - $cantidad;
        $estado = ($nuevo_stock == 0) ? 'agotado' : 'activo';

        $sql = "UPDATE lotes SET stock_actual = ?, estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$nuevo_stock, $estado, $id_lote]);
        
        $this->desconectar();
        return $res;
    }
}
?>
<?php
// core/Movimiento.php
require_once __DIR__ . '/../config/conexion.php';

class Movimiento extends Conexion {

    // 1. LISTAR TODOS (Ya lo tenías, lo dejamos igual)
    public function obtenerTodos() {
        $this->conectar();
        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.nombre as producto, l.codigo_lote, u.username as usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                ORDER BY m.fecha DESC";
        $stmt = $this->db->query($sql);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerFiltrados($buscar) {
    $this->conectar();

    $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                   p.nombre AS producto, 
                   l.codigo_lote, 
                   u.username AS usuario
            FROM movimientos m
            INNER JOIN lotes l ON m.lote_id = l.id
            INNER JOIN productos p ON l.producto_id = p.id
            LEFT JOIN usuarios u ON m.usuario_id = u.id
            WHERE p.nombre LIKE ?
               OR l.codigo_lote LIKE ?
               OR u.username LIKE ?
               OR m.tipo LIKE ?
               OR m.motivo LIKE ?
            ORDER BY m.fecha DESC";

    $like = "%$buscar%";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$like, $like, $like, $like, $like]);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->desconectar();
    return $res;
}


    // 2. REGISTRAR (Ya lo tenías, lo dejamos igual)
    public function registrar($lote_id, $tipo, $cantidad, $motivo, $usuario_id) {
        $this->conectar();
        $sql = "INSERT INTO movimientos (lote_id, tipo, cantidad, motivo, usuario_id, fecha) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $res = $stmt->execute([$lote_id, $tipo, $cantidad, $motivo, $usuario_id]);
        $this->desconectar();
        return $res;
    }

    // [NUEVO] 3. FILTRAR POR TIPO (Para Reportes de Entradas/Salidas)
public function obtenerPorTipo($tipo, $fechaInicio = null, $fechaFin = null) {
        $this->conectar();
        
        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.nombre as producto, l.codigo_lote, u.username as usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.tipo = ?";

        // Lógica de fechas
        if ($fechaInicio && $fechaFin) {
            // Aseguramos incluir todo el día final (23:59:59)
            $sql .= " AND m.fecha BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59'";
        }

        $sql .= " ORDER BY m.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tipo]);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerFiltradosReporte($buscar) {
    $this->conectar();

    $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                   p.nombre AS producto, 
                   l.codigo_lote, 
                   u.username AS usuario
            FROM movimientos m
            INNER JOIN lotes l ON m.lote_id = l.id
            INNER JOIN productos p ON l.producto_id = p.id
            LEFT JOIN usuarios u ON m.usuario_id = u.id
            WHERE p.nombre LIKE ?
               OR l.codigo_lote LIKE ?
               OR u.username LIKE ?
               OR m.tipo LIKE ?
               OR m.motivo LIKE ?
               OR m.fecha LIKE ?
            ORDER BY m.fecha DESC";

    $like = "%$buscar%";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$like, $like, $like, $like, $like, $like]);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->desconectar();
    return $res;
}

    // [NUEVO] 4. OBTENER POR TIPO CON ORDENAMIENTO Y BÚSQUEDA (Para reportes avanzados)
    public function obtenerPorTipoOrdenado($tipo, $orden = 'DESC', $buscar = '') {
        $this->conectar();

        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.nombre as producto, l.codigo_lote, u.username as usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.tipo = ?";

        $params = [$tipo];

        // Si hay búsqueda, agregar filtro por nombre de producto
        if ($buscar !== '') {
            $sql .= " AND p.nombre LIKE ?";
            $params[] = "%$buscar%";
        }

        // Validar y aplicar ordenamiento (seguridad: solo ASC o DESC)
        $ordenValido = ($orden === 'ASC') ? 'ASC' : 'DESC';
        $sql .= " ORDER BY m.fecha $ordenValido";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

}
?>
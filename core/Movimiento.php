<?php
// core/Movimiento.php
require_once __DIR__ . '/../config/conexion.php';

class Movimiento extends Conexion {

    // 1. LISTAR TODOS (Ya lo tenías, lo dejamos igual)
    public function obtenerTodos() {
        $this->conectar();
        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.nombre as producto, l.codigo_lote, u.username as usuario, u.nombre as nombre_usuario
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
                       u.username AS usuario,
                       u.nombre AS nombre_usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE p.nombre LIKE ?
                   OR l.codigo_lote LIKE ?
                   OR u.username LIKE ?
                   OR u.nombre LIKE ?
                   OR m.tipo LIKE ?
                   OR m.motivo LIKE ?
                   OR DATE_FORMAT(m.fecha, '%d/%m/%Y') LIKE ?
                   OR DATE_FORMAT(m.fecha, '%Y-%m-%d') LIKE ?
                   OR CAST(m.cantidad AS CHAR) LIKE ?
                ORDER BY m.fecha DESC";

        $like = "%$buscar%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$like, $like, $like, $like, $like, $like, $like, $like, $like]);

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

    // 3. OBTENER MOVIMIENTOS POR TIPO DE INVENTARIO
    // Utilizado para consultar entradas o salidas de productos con filtro de fechas
    public function obtenerPorTipo($tipo, $fechaInicio = null, $fechaFin = null) {
        $this->conectar();
        
        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.nombre as producto, l.codigo_lote, u.username as usuario, u.nombre as nombre_usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.tipo = ?";

        $params = [$tipo];

        // Filtro por rango de fechas para reportes de inventario
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(m.fecha) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        } elseif ($fechaInicio) {
            $sql .= " AND DATE(m.fecha) >= ?";
            $params[] = $fechaInicio;
        } elseif ($fechaFin) {
            $sql .= " AND DATE(m.fecha) <= ?";
            $params[] = $fechaFin;
        }

        $sql .= " ORDER BY m.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 5. OBTENER MOVIMIENTOS POR TIPO CON PAGINACIÓN
    // Utilizado para reportes de entrada y salida con navegación de páginas
    public function obtenerPorTipoPaginado($tipo, $fechaInicio = null, $fechaFin = null, $pagina = 1, $porPagina = 10) {
        $this->conectar();
        
        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.nombre as producto, l.codigo_lote, u.username as usuario, u.nombre as nombre_usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.tipo = ?";

        $params = [$tipo];

        // Filtro por rango de fechas para reportes de inventario
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(m.fecha) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        } elseif ($fechaInicio) {
            $sql .= " AND DATE(m.fecha) >= ?";
            $params[] = $fechaInicio;
        } elseif ($fechaFin) {
            $sql .= " AND DATE(m.fecha) <= ?";
            $params[] = $fechaFin;
        }

        $sql .= " ORDER BY m.fecha DESC";

        // Aplicar paginación para mostrar resultados por páginas
        $offset = (int)(($pagina - 1) * $porPagina);
        $porPagina = (int)$porPagina;
        $sql .= " LIMIT $porPagina OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // 6. CONTAR MOVIMIENTOS POR TIPO DE INVENTARIO
    // Utilizado para calcular el total de registros y determinar la cantidad de páginas
    public function contarPorTipo($tipo, $fechaInicio = null, $fechaFin = null) {
        $this->conectar();
        
        $sql = "SELECT COUNT(*) as total
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                WHERE m.tipo = ?";

        $params = [$tipo];

        // Lógica de fechas
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(m.fecha) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        } elseif ($fechaInicio) {
            $sql .= " AND DATE(m.fecha) >= ?";
            $params[] = $fechaInicio;
        } elseif ($fechaFin) {
            $sql .= " AND DATE(m.fecha) <= ?";
            $params[] = $fechaFin;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return (int)$res['total'];
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
                       p.nombre as producto, l.codigo_lote, u.username as usuario, u.nombre as nombre_usuario
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

    // 7. OBTENER KARDEX CON SALDO ACUMULADO
    // Utilizado para mostrar el Kardex completo con entradas, salidas y saldo después de cada movimiento
    public function obtenerKardex($producto_id = null, $fechaInicio = null, $fechaFin = null) {
        $this->conectar();
        
        $sql = "SELECT m.id, m.tipo, m.cantidad, m.fecha, m.motivo,
                       p.id as producto_id,
                       p.nombre as producto, 
                       l.codigo_lote, 
                       u.username as usuario, 
                       u.nombre as nombre_usuario
                FROM movimientos m
                INNER JOIN lotes l ON m.lote_id = l.id
                INNER JOIN productos p ON l.producto_id = p.id
                LEFT JOIN usuarios u ON m.usuario_id = u.id
                WHERE 1=1";

        $params = [];

        // Filtro por producto si se especifica
        if ($producto_id !== null) {
            $sql .= " AND p.id = ?";
            $params[] = $producto_id;
        }

        // Filtro por rango de fechas
        if ($fechaInicio && $fechaFin) {
            $sql .= " AND DATE(m.fecha) BETWEEN ? AND ?";
            $params[] = $fechaInicio;
            $params[] = $fechaFin;
        } elseif ($fechaInicio) {
            $sql .= " AND DATE(m.fecha) >= ?";
            $params[] = $fechaInicio;
        } elseif ($fechaFin) {
            $sql .= " AND DATE(m.fecha) <= ?";
            $params[] = $fechaFin;
        }

        $sql .= " ORDER BY m.fecha ASC, m.id ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calcular saldo acumulado por producto
        $kardex = [];
        $saldos = []; // Array para almacenar saldo por producto_id
        
        foreach ($res as $movimiento) {
            $prod_id = $movimiento['producto_id'];
            
            // Inicializar saldo si es la primera vez que vemos este producto
            if (!isset($saldos[$prod_id])) {
                $saldos[$prod_id] = 0;
            }
            
            // Calcular saldo según el tipo de movimiento
            if ($movimiento['tipo'] == 'entrada') {
                $saldos[$prod_id] += (int)$movimiento['cantidad'];
            } else if ($movimiento['tipo'] == 'salida') {
                $saldos[$prod_id] -= (int)$movimiento['cantidad'];
            }
            
            // Agregar el saldo al movimiento
            $movimiento['saldo'] = $saldos[$prod_id];
            $kardex[] = $movimiento;
        }
        
        $this->desconectar();
        return $kardex;
    }

}
?>
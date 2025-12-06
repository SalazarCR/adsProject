<?php
// core/CodigoVerificacion.php
require_once __DIR__ . '/../config/conexion.php';

class CodigoVerificacion extends Conexion {

    // Insertar un nuevo código válido por X minutos
    public function insertarCodigo($usuario_id, $code, $minutos = 10) {

        $this->conectar(); // usa el método heredado

        $expira = date('Y-m-d H:i:s', time() + ($minutos * 60));

        $sql = "INSERT INTO codigos_verificacion (usuario_id, code, expires_at)
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $res  = $stmt->execute([$usuario_id, $code, $expira]);

        $this->desconectar();
        return $res;
    }

    // Obtener un código válido (no usado y no vencido)
    public function obtenerCodigoValido($usuario_id, $code) {

        $this->conectar();

        $sql = "SELECT *
                FROM codigos_verificacion
                WHERE usuario_id = ?
                  AND code = ?
                  AND is_used = 0
                  AND expires_at >= NOW()
                ORDER BY id DESC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $code]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->desconectar();
        return $res;
    }

    // Marcar como usado
    public function marcarComoUsado($id) {

        $this->conectar();

        $stmt = $this->db->prepare("UPDATE codigos_verificacion SET is_used = 1 WHERE id=?");
        $res  = $stmt->execute([$id]);

        $this->desconectar();
        return $res;
    }

    // Borrar códigos previos
    public function eliminarCodigosUsuario($usuario_id) {

        $this->conectar();

        $stmt = $this->db->prepare("DELETE FROM codigos_verificacion WHERE usuario_id=?");
        $res  = $stmt->execute([$usuario_id]);

        $this->desconectar();
        return $res;
    }
}

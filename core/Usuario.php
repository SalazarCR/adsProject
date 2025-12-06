<?php
// core/Usuario.php
require_once __DIR__ . '/../config/conexion.php';

class Usuario extends Conexion {

    // ==========================================
    //  MÉTODOS DE LOGIN
    // ==========================================

    public function verificarLogin($user) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$user]);
        $res = $stmt->fetch();
        $this->desconectar();
        return ($res) ? true : false;
    }

    public function verificarPassword($user, $passInput) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT password FROM usuarios WHERE username = ?");
        $stmt->execute([$user]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();

        if ($row) {
            return password_verify($passInput, $row['password']);
        }
        return false;
    }

    public function obtenerDatos($user) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = ?");
        $stmt->execute([$user]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerPorId($id) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    // ==========================================
    //  CRUD
    // ==========================================

    public function obtenerTodos() {
        $this->conectar();
        $stmt = $this->db->query("SELECT * FROM usuarios ORDER BY id DESC");
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerFiltrados($buscar) {
    $this->conectar();

    $sql = "SELECT *
            FROM usuarios
            WHERE nombre LIKE ?
               OR username LIKE ?
               OR email LIKE ?
               OR telefono LIKE ?
               OR rol LIKE ?
               OR estado_tmp LIKE ?
            ORDER BY id DESC";

    $like = "%$buscar%";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$like, $like, $like, $like, $like, $like]);

    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $this->desconectar();
        return $res;
    }


    public function crear($nombre, $username, $password, $rol, $estado, $email, $telefono) {
        $this->conectar();
        $sql = "INSERT INTO usuarios(nombre, username, password, rol, estado_tmp, email, telefono)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $passHash = password_hash($password, PASSWORD_DEFAULT);

        $res = $stmt->execute([
            $nombre,
            $username,
            $passHash,
            $rol,
            $estado,
            $email,
            $telefono
        ]);

        $this->desconectar();
        return $res;
    }

    public function actualizar($id, $nombre, $username, $rol, $estado, $email, $telefono) {
        $this->conectar();
        $sql = "UPDATE usuarios
                SET nombre = ?, username = ?, rol = ?, estado_tmp = ?, email = ?, telefono = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        $res = $stmt->execute([
            $nombre,
            $username,
            $rol,
            $estado,
            $email,
            $telefono,
            $id
        ]);

        $this->desconectar();
        return $res;
    }

    public function eliminar($id) {
        $this->conectar();
        $stmt = $this->db->prepare("UPDATE usuarios SET estado_tmp = 'inactivo' WHERE id = ?");
        $res = $stmt->execute([$id]);
        $this->desconectar();
        return $res;
    }

    public function existeOtroUsuario($id, $username) {
        $this->conectar();
        $sql = "SELECT id FROM usuarios WHERE username = ? AND id != ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $id]);
        $res = $stmt->fetch();
        $this->desconectar();
        return ($res) ? true : false;
    }

    // ==========================================
    //  MÉTODOS RECUPERAR CONTRASEÑA
    // ==========================================

    public function obtenerPorEmail($email) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function obtenerPorTelefono($telefono) {
        $this->conectar();
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE telefono = ? LIMIT 1");
        $stmt->execute([$telefono]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->desconectar();
        return $res;
    }

    public function actualizarPassword($id, $newPassword) {
        $this->conectar();
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $res = $stmt->execute([$hash, $id]);
        $this->desconectar();
        return $res;
    }
}
?>

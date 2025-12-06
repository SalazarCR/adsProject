<?php
require_once __DIR__ . '/config/conexion.php';

class ResetPassword extends Conexion {
    public function resetUserPassword($username, $newPassword) {
        $this->conectar();

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE username = ?");
        $result = $stmt->execute([$hashedPassword, $username]);

        $this->desconectar();

        return $result;
    }
}

$reset = new ResetPassword();

// Resetear contraseña del usuario 'jesus' a '123456'
if ($reset->resetUserPassword('jesus', '123456')) {
    echo "Contraseña actualizada exitosamente para el usuario 'jesus'\n";
    echo "Usuario: jesus\n";
    echo "Contraseña: 123456\n";
} else {
    echo "Error al actualizar la contraseña\n";
}
?>

<?php
// config/conexion.php
class Conexion {
    protected $db; // protected para que lo usen los hijos (Usuario)

    public function conectar() {
        // Tu lógica de conexión actual, pero asignando a $this->db
        try {
            // Usar 'db' como host cuando se ejecuta en Docker, '127.0.0.1' en desarrollo local
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $dbname = getenv('DB_NAME') ?: 'inventario_db';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';

            $this->db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error BD: " . $e->getMessage());
        }
    }

    public function desconectar() {
        $this->db = null;
    }
}
?>
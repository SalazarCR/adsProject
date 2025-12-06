<?php
// semilla.php
require_once __DIR__ . '/config/conexion.php';

class Semilla extends Conexion {
    
    public function insertarAdmin() {
        $this->conectar(); 
        
        $user = 'admin';
        $pass = '123'; 
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        
        try {
            // --- CORRECCIÓN AQUÍ ---
            // Desactivamos la revisión de llaves foráneas temporalmente
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
            
            // Ahora sí nos deja limpiar la tabla
            $this->db->exec("TRUNCATE TABLE usuarios");
            
            // Reactivamos la seguridad
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
            // -----------------------
            
            $sql = "INSERT INTO usuarios (username, password, nombre, rol, email, estado_tmp) 
                    VALUES (:u, :p, 'Administrador', 'admin', 'admin@test.com', 'activo')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':u' => $user, ':p' => $hash]);
            
            echo "<div style='color:green; font-size:20px; text-align:center; margin-top:50px;'>";
            echo "✅ USUARIO CREADO CON ÉXITO<br>";
            echo "Usuario: <b>admin</b><br>";
            echo "Password: <b>123</b><br>";
            echo "<br><a href='index.php'>IR AL LOGIN</a>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<h1 style='color:red'>ERROR: " . $e->getMessage() . "</h1>";
        }
        
        $this->desconectar();
    }
}

$obj = new Semilla();
$obj->insertarAdmin();
?>
<?php
// views/proveedores/FormProveedores.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormProveedores extends Formulario {

    // 1. PANTALLA LISTAR PROVEEDORES
    public function formListarProveedoresShow($proveedores) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Administrar Proveedores</h2>
                
                <div style="margin-bottom: 20px;">
                    <form action="../../controllers/getProveedores.php" method="POST" style="display:inline;">
                        <button type="submit" name="btnIrCrear" class="btn">Crear Nuevo Proveedor</button>
                    </form>
                    <a href="../../views/home/dashboard.php" style="margin-left: 15px; text-decoration: underline; color: #333;">Retroceder</a>
                </div>

                <form method="GET" style="margin-bottom: 15px;">
                    <input type="text" name="buscar" placeholder="Buscar..." 
                    value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button type="submit">Buscar</button>
                </form>


                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #ddd;">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach ($proveedores as $p): ?>
                        <?php 
                            $color = ($p['estado'] === 'activo') ? 'green' : 'red';
                        ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= htmlspecialchars($p['contacto']) ?></td>
                            <td><?= htmlspecialchars($p['telefono']) ?></td>
                            <td style="color: <?= $color ?>; font-weight:bold;"><?= ucfirst($p['estado']) ?></td>
           <td style="text-align: center;">
    <form action="../../controllers/getProveedores.php" method="POST" style="display:inline;">
        <input type="hidden" name="id_proveedor" value="<?= $p['id'] ?>">
        <button type="submit" name="btnIrEditar" style="cursor:pointer;">Editar</button>
    </form>
</td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // 2. PANTALLA CREAR PROVEEDOR
    public function formCrearProveedorShow() {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Crear Proveedor</h2>
            <form action="../../controllers/getProveedores.php" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Contacto:</label>
                <input type="text" name="contacto">

                <label>Teléfono:</label>
                <input type="text" name="telefono">
                
                <label>Dirección:</label>
                <textarea name="direccion"></textarea>

                <br><br>
                <button type="submit" name="btnRegistrar">Guardar Proveedor</button>
                <br><br>
                <a href="../../controllers/getProveedores.php?op=listar" style="text-decoration: underline; color: #333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }

    // 3. PANTALLA EDITAR PROVEEDOR
    public function formEditarProveedorShow($proveedor) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Editar Proveedor</h2>
            <form action="../../controllers/getProveedores.php" method="POST">
                
                <input type="hidden" name="id_proveedor" value="<?= $proveedor['id'] ?>">

                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>

                <label>Contacto:</label>
                <input type="text" name="contacto" value="<?= htmlspecialchars($proveedor['contacto']) ?>">

                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($proveedor['telefono']) ?>">
                
                <label>Dirección:</label>
                <textarea name="direccion"><?= htmlspecialchars($proveedor['direccion']) ?></textarea>

                <label>Estado:</label>
                <select name="estado">
                    <option value="activo" <?= $proveedor['estado']=='activo'?'selected':'' ?>>Activo</option>
                    <option value="inactivo" <?= $proveedor['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
                </select>

                <br><br>
                <button type="submit" name="btnActualizar">Actualizar Datos</button>
                <br><br>
                <a href="../../controllers/getProveedores.php?op=listar" style="text-decoration: underline; color: #333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }
}
?>
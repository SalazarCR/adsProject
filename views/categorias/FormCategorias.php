<?php
// views/categorias/FormCategorias.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormCategorias extends Formulario {

    // 1. LISTAR (Sin botón Eliminar)
    public function formListarCategoriasShow($categorias) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Administrar Categorías</h2>
                
                <div style="margin-bottom: 20px;">
                    <form action="../../controllers/getCategorias.php" method="POST" style="display:inline;">
                        <button type="submit" name="btnIrCrear" class="btn">Nueva Categoría</button>
                    </form>
                    <a href="../../views/home/dashboard.php" style="margin-left: 15px; text-decoration:underline; color:#333;">Retroceder</a>
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
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach ($categorias as $c): ?>
                        <?php 
                            $estado = $c['estado'] ?? 'activo';
                            $color = ($estado === 'activo') ? 'green' : 'red'; 
                        ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['descripcion']) ?></td>
                            
                            <td style="color: <?= $color ?>; font-weight:bold;">
                                <?= ucfirst($estado) ?>
                            </td>

                            <td style="text-align: center;">
                                <form action="../../controllers/getCategorias.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_categoria" value="<?= $c['id'] ?>">
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

    // 2. CREAR
    public function formCrearCategoriaShow() {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Nueva Categoría</h2>
            <form action="../../controllers/getCategorias.php" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
                
                <label>Descripción:</label>
                <textarea name="descripcion"></textarea>
                
                <label>Estado:</label>
                <select name="estado">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>

                <br><br>
                <button type="submit" name="btnRegistrar">Guardar</button>
                <br><br>
                <a href="../../controllers/getCategorias.php?op=listar" style="text-decoration:underline; color:#333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }

    // 3. EDITAR
    public function formEditarCategoriaShow($c) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Editar Categoría</h2>
            <form action="../../controllers/getCategorias.php" method="POST">
                <input type="hidden" name="id_categoria" value="<?= $c['id'] ?>">
                
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($c['nombre']) ?>" required>
                
                <label>Descripción:</label>
                <textarea name="descripcion"><?= htmlspecialchars($c['descripcion']) ?></textarea>
                
                <label>Estado:</label>
                <select name="estado">
                    <option value="activo" <?= $c['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= $c['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>

                <br><br>
                <button type="submit" name="btnActualizar">Actualizar</button>
                <br><br>
                <a href="../../controllers/getCategorias.php?op=listar" style="text-decoration:underline; color:#333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }
}
?>
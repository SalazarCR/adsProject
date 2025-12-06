<?php
// views/productos/FormProductos.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormProductos extends Formulario {

    // 1. PANTALLA LISTAR PRODUCTOS
    public function formListarProductosShow($productos) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Administrar Productos</h2>
                
                <div style="margin-bottom: 20px;">
                    <form action="../../controllers/getProductos.php" method="POST" style="display:inline;">
                        <button type="submit" name="btnIrCrear" class="btn">Crear Nuevo Producto</button>
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
                        <th>Código</th>
                        <th>Stock</th>
                        <th>Proveedor</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Acciones</th> </tr>
                    <?php foreach ($productos as $p): ?>
                        <?php 
                            $estado = $p['estado'] ?? 'activo';
                            $color = ($estado === 'activo') ? 'green' : 'red';
                        ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= htmlspecialchars($p['codigo_interno']) ?></td>
                            <td><?= $p['stock'] ?? 0 ?></td>
                            <td><?= htmlspecialchars($p['proveedor_nombre'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($p['categoria_nombre'] ?? 'N/A') ?></td>
                            
                            <td style="color: <?= $color ?>; font-weight:bold;">
                                <?= ucfirst($estado) ?>
                            </td>

                            <td style="text-align: center;">
                                <form action="../../controllers/getProductos.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_producto" value="<?= $p['id'] ?>">
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

    // 2. PANTALLA CREAR PRODUCTO
    public function formCrearProductoShow($categorias, $proveedores) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Crear Producto</h2>
            <form action="../../controllers/getProductos.php" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
                
                <label>Código Interno:</label>
                <input type="text" name="codigo_interno" required>
                
                <label>Descripción:</label>
                <textarea name="descripcion"></textarea>
                
                <label>Precio Venta:</label>
                <input type="number" step="0.01" name="precio_venta">

                <label>Categoría:</label>
                <select name="categoria_id">
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Proveedor:</label>
                <select name="proveedor_id">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($proveedores as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
                
                <label>Estado:</label>
                <select name="estado">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                
                <br><br>
                <button type="submit" name="btnRegistrar">Guardar Producto</button>
                <br><br>
                <a href="../../controllers/getProductos.php?op=listar" style="text-decoration: underline; color: #333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }
    
    // 3. PANTALLA EDITAR PRODUCTO
    public function formEditarProductoShow($producto, $categorias, $proveedores) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Editar Producto</h2>
            <form action="../../controllers/getProductos.php" method="POST">
                <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
                
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
                
                <label>Código Interno:</label>
                <input type="text" name="codigo_interno" value="<?= htmlspecialchars($producto['codigo_interno']) ?>" required>
                
                <label>Descripción:</label>
                <textarea name="descripcion"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                
                <label>Precio Venta:</label>
                <input type="number" step="0.01" name="precio_venta" value="<?= $producto['precio_venta'] ?>">

                <label>Categoría:</label>
                <select name="categoria_id">
                    <?php foreach ($categorias as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
                            <?= $c['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Proveedor:</label>
                <select name="proveedor_id">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($proveedores as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $p['id'] == $producto['proveedor_id'] ? 'selected' : '' ?>>
                            <?= $p['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Estado:</label>
                <select name="estado">
                    <option value="activo" <?= $producto['estado'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="inactivo" <?= $producto['estado'] == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>

                <br><br>
                <button type="submit" name="btnActualizar">Actualizar</button>
                <br><br>
                <a href="../../controllers/getProductos.php?op=listar" style="text-decoration: underline; color: #333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }
}
?>
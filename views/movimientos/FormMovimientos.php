<?php
// views/movimientos/FormMovimientos.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormMovimientos extends Formulario {

    // 1. LISTAR HISTORIAL (KARDEX)
    public function formListarMovimientosShow($movimientos) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Gestionar Movimientos</h2>
                
                <div style="margin-bottom: 20px;">
                    <form action="../../controllers/getMovimientos.php" method="POST" style="display:inline;">
                        <button type="submit" name="btnIrEntrada" class="btn" style="background-color:green;">+ Registrar Entrada</button>
                    </form>
                    
                    <form action="../../controllers/getMovimientos.php" method="POST" style="display:inline; margin-left:10px;">
                        <button type="submit" name="btnIrSalida" class="btn" style="background-color:orange;">- Registrar Salida</button>
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
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cant.</th>
                        <th>Motivo</th>
                        <th>Usuario</th>
                    </tr>
                    <?php foreach ($movimientos as $m): ?>
                        <?php $color = ($m['tipo'] == 'entrada') ? 'blue' : 'red'; ?>
                        <tr>
                            <td><?= $m['fecha'] ?></td>
                            <td style="color:<?= $color ?>; font-weight:bold;"><?= strtoupper($m['tipo']) ?></td>
                            <td><?= htmlspecialchars($m['producto']) ?></td>
                            <td><?= htmlspecialchars($m['codigo_lote']) ?></td>
                            <td><?= $m['cantidad'] ?></td>
                            <td><?= htmlspecialchars($m['motivo']) ?></td>
                            <td><?= htmlspecialchars($m['usuario']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // 2. FORMULARIO DE ENTRADA (NUEVO LOTE)
    public function formEntradaShow($productos) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px; width: 400px;">
            <h2 style="color:green;">Registrar Entrada (Compra)</h2>
            <form action="../../controllers/getMovimientos.php" method="POST">
                
                <label>Producto:</label>
                <select name="producto_id" required>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Código de Lote (Impreso en caja):</label>
                <input type="text" name="codigo_lote" placeholder="Ej: L-2025-A" required>

                <label>Fecha Vencimiento (Importante):</label>
                <input type="date" name="fecha_vencimiento" required>

                <label>Cantidad:</label>
                <input type="number" name="cantidad" min="1" required>

                <label>Costo Unitario:</label>
                <input type="number" name="costo" step="0.01" required>

                <label>Motivo:</label>
                <input type="text" name="motivo" value="Compra a proveedor" required>

                <br><br>
                <button type="submit" name="btnGuardarEntrada" style="background-color:green;">Guardar Entrada</button>
                <br><br>
                <a href="../../controllers/getMovimientos.php?op=listar" style="text-decoration: underline;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }

    // 3. FORMULARIO DE SALIDA (VENTA/MERMA)
    public function formSalidaShow($productos, $lotes, $id_selected) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px; width: 400px;">
            <h2 style="color:orange;">Registrar Salida</h2>
            
            <form action="../../controllers/getMovimientos.php" method="POST">
                <label>1. Seleccione Producto:</label>
                <select name="id_producto_seleccionado">
                    <option value="">-- Seleccionar --</option>
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($p['id'] == $id_selected) ? 'selected' : '' ?>>
                            <?= $p['nombre'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="btnIrSalida" style="margin-top:5px; padding:5px;">Buscar Lotes</button>
            </form>

            <hr>

            <?php if ($id_selected): ?>
                <form action="../../controllers/getMovimientos.php" method="POST">
                    
                    <label>2. Seleccione Lote (Vencimiento - Stock):</label>
                    <select name="lote_id" required>
                        <?php if (empty($lotes)): ?>
                            <option value="">No hay stock disponible</option>
                        <?php else: ?>
                            <?php foreach ($lotes as $l): ?>
                                <option value="<?= $l['id'] ?>">
                                    Lote: <?= $l['codigo_lote'] ?> | Vence: <?= $l['fecha_vencimiento'] ?> | Disp: <?= $l['stock_actual'] ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>

                    <label>Cantidad a retirar:</label>
                    <input type="number" name="cantidad" min="1" required>

                    <label>Motivo:</label>
                    <input type="text" name="motivo" value="Venta en mostrador" required>

                    <br><br>
                    <?php if (!empty($lotes)): ?>
                        <button type="submit" name="btnGuardarSalida" style="background-color:orange;">Confirmar Salida</button>
                    <?php else: ?>
                        <p style="color:red;">No se puede registrar salida sin stock.</p>
                    <?php endif; ?>
                    
                    <br><br>
                    <a href="../../controllers/getMovimientos.php?op=listar" style="text-decoration: underline;">Retroceder</a>
                </form>
            <?php endif; ?>
        </div>
        <?php
        $this->piePaginaShow();
    }
}
?>
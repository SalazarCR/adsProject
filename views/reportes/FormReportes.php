<?php
// views/reportes/FormReportes.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormReportes extends Formulario {

    // ==========================================
    // INTERFAZ UNIFICADA DE REPORTES (ENTRADAS Y SALIDAS)
    // ==========================================
    public function formReporteUnificadoShow($entradas, $salidas, $buscar = '') {
        $this->cabeceraShow();
        $this->menuShow();

        // Construcción de parámetros para exportación
        $paramsEntrada = "tipo=entrada";
        $paramsSalida = "tipo=salida";
        if ($buscar !== '') {
            $paramsEntrada .= "&buscar=" . urlencode($buscar);
            $paramsSalida .= "&buscar=" . urlencode($buscar);
        }
        $linkExcelEntrada = "../../export/export_excel.php?" . $paramsEntrada;
        $linkExcelSalida = "../../export/export_excel.php?" . $paramsSalida;
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Generar Reportes</h2>

                <!-- Campo de búsqueda global -->
                <div style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border: 1px solid #ddd;">
                    <form action="../../controllers/getReportes.php" method="POST">
                        <label>Colocar nombre:</label>
                        <input type="text" name="txtBuscar"
                               value="<?= htmlspecialchars($buscar) ?>"
                               placeholder="Buscar por nombre de producto...">

                        <button type="submit" name="btnBuscar" class="btn" style="padding: 5px 15px;">Buscar</button>
                    </form>
                </div>

                <!-- ==================== TABLA DE ENTRADAS ==================== -->
                <div style="margin-bottom: 40px;">
                    <h3 style="background-color: #28a745; color: white; padding: 10px;">Reportes de Entrada</h3>

                    <!-- Botón generar reporte de entradas -->
                    <div style="margin-bottom: 15px;">
                        <?php if (!empty($entradas)): ?>
                            <a href="<?= $linkExcelEntrada ?>" target="_blank" class="btn"
                               style="background-color: #217346; text-decoration: none; color: white; padding: 10px 15px; display: inline-block;">
                                Generar reporte (Excel)
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Tabla de entradas -->
                    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <tr style="background-color: #28a745; color: white;">
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Responsable</th>
                        </tr>
                        <?php if (empty($entradas)): ?>
                            <tr><td colspan="7" style="text-align: center;">No se encontraron registros de entradas.</td></tr>
                        <?php else: ?>
                            <?php foreach ($entradas as $m): ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['fecha']) ?></td>
                                    <td style="color: green; font-weight: bold;">ENTRADA</td>
                                    <td><?= htmlspecialchars($m['producto']) ?></td>
                                    <td><?= htmlspecialchars($m['codigo_lote']) ?></td>
                                    <td><?= htmlspecialchars($m['cantidad']) ?></td>
                                    <td><?= htmlspecialchars($m['motivo']) ?></td>
                                    <td><?= htmlspecialchars($m['usuario']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>

                <!-- ==================== TABLA DE SALIDAS ==================== -->
                <div style="margin-bottom: 40px;">
                    <h3 style="background-color: #ffc107; color: black; padding: 10px;">Reportes de Salida</h3>

                    <!-- Botón generar reporte de salidas -->
                    <div style="margin-bottom: 15px;">
                        <?php if (!empty($salidas)): ?>
                            <a href="<?= $linkExcelSalida ?>" target="_blank" class="btn"
                               style="background-color: #217346; text-decoration: none; color: white; padding: 10px 15px; display: inline-block;">
                                Generar reporte (Excel)
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Tabla de salidas -->
                    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <tr style="background-color: #ffc107; color: black;">
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Producto</th>
                            <th>Lote</th>
                            <th>Cantidad</th>
                            <th>Motivo</th>
                            <th>Responsable</th>
                        </tr>
                        <?php if (empty($salidas)): ?>
                            <tr><td colspan="7" style="text-align: center;">No se encontraron registros de salidas.</td></tr>
                        <?php else: ?>
                            <?php foreach ($salidas as $m): ?>
                                <tr>
                                    <td><?= htmlspecialchars($m['fecha']) ?></td>
                                    <td style="color: red; font-weight: bold;">SALIDA</td>
                                    <td><?= htmlspecialchars($m['producto']) ?></td>
                                    <td><?= htmlspecialchars($m['codigo_lote']) ?></td>
                                    <td><?= htmlspecialchars($m['cantidad']) ?></td>
                                    <td><?= htmlspecialchars($m['motivo']) ?></td>
                                    <td><?= htmlspecialchars($m['usuario']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>

                <!-- Botón regresar -->
                <div style="margin-top: 20px;">
                    <form action="../../controllers/getReportes.php" method="POST">
                        <button type="submit" name="btnRegresar" class="btn"
                                style="background-color: #6c757d; padding: 10px 15px; color: white; border: none; cursor: pointer;">
                            Regresar
                        </button>
                    </form>
                </div>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }
}
?>

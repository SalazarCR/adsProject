<?php
// views/reportes/FormReportes.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormReportes extends Formulario {

    // MÉTODO ÚNICO PARA MOSTRAR EL REPORTE (Con o sin filtros)
    public function formReporteShow($movimientos, $tipoActual = 'todos', $ini = '', $fin = '') {
        $this->cabeceraShow();
        $this->menuShow();
        
        // Título dinámico
        $titulo = "Reporte General de Movimientos";
        if ($tipoActual == 'entrada') $titulo = "Reporte de Entradas (Compras)";
        if ($tipoActual == 'salida')  $titulo = "Reporte de Salidas (Ventas)";

        // Construcción de enlaces de exportación (con parámetros actuales)
        $params = "tipo=$tipoActual&inicio=$ini&fin=$fin";
        $linkPDF = "../../export/export_pdf.php?" . $params; 
        $linkCSV = "../../export/export_excel.php?" . $params;
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2><?= $titulo ?></h2>
                
                <div style="background:#f9f9f9; padding:15px; border:1px solid #ddd; margin-bottom:20px;">
                    <form action="../../controllers/getReportes.php" method="POST">
                        <label>Desde:</label>
                        <input type="date" name="fecha_inicio" value="<?= $ini ?>">
                        
                        <label>Hasta:</label>
                        <input type="date" name="fecha_fin" value="<?= $fin ?>">
                        
                        <br><br>
                        <button type="submit" name="btnFiltrarEntradas" class="btn" style="background-color:#28a745;">Filtrar Entradas</button>
                        <button type="submit" name="btnFiltrarSalidas" class="btn" style="background-color:#ffc107;">Filtrar Salidas</button>
                        <button type="submit" name="btnVerTodo" class="btn" style="background-color:#17a2b8;">Ver Todo</button>
                    </form>
                </div>

                <?php if (!empty($movimientos)): ?>
                <div style="margin-bottom:15px;">
                    <a href="<?= $linkPDF ?>" target="_blank" class="btn" style="background-color:#d9534f; text-decoration:none; color:white; padding:8px 12px;">
                        📄 Descargar PDF
                    </a>
                    <a href="<?= $linkCSV ?>" target="_blank" class="btn" style="background-color:#217346; text-decoration:none; color:white; padding:8px 12px; margin-left:10px;">
                        📊 Descargar Excel
                    </a>
                </div>
                <?php endif; ?>

                <div style="margin-bottom: 10px;">
                    <a href="../../views/home/dashboard.php" style="text-decoration: underline; color: #333;">Retroceder</a>
                </div>

                <form method="GET" style="margin-bottom: 15px;">
                    <input type="text" name="buscar" placeholder="Buscar..." 
                    value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button type="submit">Buscar</button>
                </form>

                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #333; color: white;">
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cant.</th>
                        <th>Motivo</th>
                        <th>Responsable</th>
                    </tr>
                    <?php if (empty($movimientos)): ?>
                        <tr><td colspan="7" style="text-align:center;">No se encontraron registros.</td></tr>
                    <?php else: ?>
                        <?php foreach ($movimientos as $m): ?>
                            <?php $color = ($m['tipo'] == 'entrada') ? 'green' : 'red'; ?>
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
                    <?php endif; ?>
                </table>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }
}
?>
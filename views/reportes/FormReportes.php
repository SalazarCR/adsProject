<?php
// views/reportes/FormReportes.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormReportes extends Formulario {

    // ==========================================
    // 1. VISTA PRINCIPAL - SELECCIÓN DE TIPO DE REPORTE
    // Similar a formListarMovimientosShow
    // ==========================================
    public function formListarReportesShow() {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Gestión de Reportes</h2>
                
                <div style="margin-bottom: 20px;">
                    <form action="../../controllers/getReportes.php" method="POST" style="display:inline;">
                        <button type="submit" name="btnIrEntrada" class="btn" style="background-color:#28a745; color:white; padding:12px 24px; font-size:16px;">
                            📥 Generar Reporte de Entrada
                        </button>
                    </form>
                    
                    <form action="../../controllers/getReportes.php" method="POST" style="display:inline; margin-left:10px;">
                        <button type="submit" name="btnIrSalida" class="btn" style="background-color:#ffc107; color:black; padding:12px 24px; font-size:16px;">
                            📤 Generar Reporte de Salida
                        </button>
                    </form>

                    <a href="../../views/home/dashboard.php" style="margin-left: 15px; text-decoration: underline; color: #333;">Retroceder</a>
                </div>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6; margin-top: 20px;">
                    <h3 style="color: #495057; margin-top: 0;">Información</h3>
                    <p style="color: #6c757d; line-height: 1.6;">
                        Seleccione el tipo de reporte que desea generar. Puede filtrar por rango de fechas y exportar los resultados en formato PDF.
                    </p>
                </div>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // ==========================================
    // 2. VISTA DE REPORTE DE ENTRADA
    // RF35-RF39: Reportes de Entrada
    // ==========================================
    public function formReporteEntradaShow(
        $entradas,
        $fechaInicio = '',
        $fechaFin = '',
        $pagina = 1,
        $totalPaginas = 1,
        $totalRegistros = 0
    ) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2 style="color: #28a745;">📥 Reporte de Entrada de Inventario</h2>
                
                <div style="margin-bottom: 20px;">
                    <a href="../../controllers/getReportes.php?op=listar" style="text-decoration: underline; color: #333;">← Retroceder</a>
                </div>

                <!-- Filtros de Fecha (RF37) -->
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                    <h3 style="margin-top: 0; color: #495057;">Filtrar por Rango de Fechas</h3>
                    <form action="../../controllers/getReportes.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                        <div>
                            <label><b>Fecha Inicio:</b></label>
                            <input type="date" name="fechaInicioEntrada" value="<?= htmlspecialchars($fechaInicio) ?>" class="input-text" style="width: 100%;">
                        </div>
                        <div>
                            <label><b>Fecha Fin:</b></label>
                            <input type="date" name="fechaFinEntrada" value="<?= htmlspecialchars($fechaFin) ?>" class="input-text" style="width: 100%;">
                        </div>
                        <div>
                            <input type="hidden" name="paginaEntrada" value="1">
                            <button type="submit" name="btnGenerarEntrada" class="btn" style="background-color: #28a745; color: white; padding: 10px 20px; width: 100%;">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Información y Exportación (RF35, RF36) -->
                <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #dee2e6;">
                    <div style="color: #495057; font-size: 14px;">
                        <strong>Total de registros:</strong> <?= $totalRegistros ?> | 
                        <strong>Página:</strong> <?= $pagina ?> de <?= $totalPaginas ?>
                        <?php if ($fechaInicio || $fechaFin): ?>
                            | <strong>Período:</strong> 
                            <?= $fechaInicio ? $this->formatearFecha($fechaInicio) : 'Inicio' ?> - 
                            <?= $fechaFin ? $this->formatearFecha($fechaFin) : 'Fin' ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($entradas)): ?>
                        <a href="../../export/export_pdf.php?tipo=entrada&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>" 
                           target="_blank" 
                           class="btn" 
                           style="background-color: #dc3545; text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px;">
                            📄 Exportar PDF (RF35)
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Tabla de Entradas (RF38, RF39) -->
                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; background: white;">
                    <tr style="background-color: #28a745; color: white;">
                        <th>Fecha y Hora</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Responsable (RF39)</th>
                    </tr>
                    <?php if (empty($entradas)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #6c757d;">
                                No se encontraron registros de entradas<?= ($fechaInicio || $fechaFin) ? ' en el rango de fechas seleccionado' : '' ?>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($entradas as $m): ?>
                            <tr>
                                <td><?= $this->formatearFechaHora($m['fecha']) ?></td>
                                <td><?= htmlspecialchars($m['producto']) ?></td>
                                <td><?= htmlspecialchars($m['codigo_lote']) ?></td>
                                <td style="text-align: center; color: #28a745; font-weight: bold;">+<?= $m['cantidad'] ?></td>
                                <td><?= htmlspecialchars($m['motivo']) ?></td>
                                <td><?= htmlspecialchars($m['nombre_usuario'] ?? $m['usuario'] ?? 'Sistema') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>

                <!-- Paginación (RF38) -->
                <?php if ($totalPaginas > 1): ?>
                    <div style="margin-top: 20px; text-align: center;">
                        <?php
                        $params = http_build_query([
                            'fechaInicioEntrada' => $fechaInicio,
                            'fechaFinEntrada' => $fechaFin
                        ]);
                        ?>
                        <?php if ($pagina > 1): ?>
                            <a href="../../controllers/getReportes.php?<?= $params ?>&paginaEntrada=<?= $pagina - 1 ?>" 
                               class="btn" style="background-color: #6c757d; color: white; padding: 8px 16px; text-decoration: none; margin: 0 5px; border-radius: 5px;">
                                « Anterior
                            </a>
                        <?php endif; ?>
                        
                        <span style="padding: 8px 16px; background: #e9ecef; border-radius: 5px; margin: 0 5px; color: #495057;">
                            Página <?= $pagina ?> de <?= $totalPaginas ?>
                        </span>
                        
                        <?php if ($pagina < $totalPaginas): ?>
                            <a href="../../controllers/getReportes.php?<?= $params ?>&paginaEntrada=<?= $pagina + 1 ?>" 
                               class="btn" style="background-color: #6c757d; color: white; padding: 8px 16px; text-decoration: none; margin: 0 5px; border-radius: 5px;">
                                Siguiente »
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // ==========================================
    // 3. VISTA DE REPORTE DE SALIDA
    // RF40-RF44: Reportes de Salida
    // ==========================================
    public function formReporteSalidaShow(
        $salidas,
        $fechaInicio = '',
        $fechaFin = '',
        $pagina = 1,
        $totalPaginas = 1,
        $totalRegistros = 0
    ) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2 style="color: #ffc107;">📤 Reporte de Salida de Inventario</h2>
                
                <div style="margin-bottom: 20px;">
                    <a href="../../controllers/getReportes.php?op=listar" style="text-decoration: underline; color: #333;">← Retroceder</a>
                </div>

                <!-- Filtros de Fecha (RF43) -->
                <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border: 1px solid #ffc107; margin-bottom: 20px;">
                    <h3 style="margin-top: 0; color: #856404;">Filtrar por Rango de Fechas</h3>
                    <form action="../../controllers/getReportes.php" method="POST" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                        <div>
                            <label><b>Fecha Inicio:</b></label>
                            <input type="date" name="fechaInicioSalida" value="<?= htmlspecialchars($fechaInicio) ?>" class="input-text" style="width: 100%;">
                        </div>
                        <div>
                            <label><b>Fecha Fin:</b></label>
                            <input type="date" name="fechaFinSalida" value="<?= htmlspecialchars($fechaFin) ?>" class="input-text" style="width: 100%;">
                        </div>
                        <div>
                            <input type="hidden" name="paginaSalida" value="1">
                            <button type="submit" name="btnGenerarSalida" class="btn" style="background-color: #ffc107; color: black; padding: 10px 20px; width: 100%;">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Información y Exportación (RF40, RF41) -->
                <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #dee2e6;">
                    <div style="color: #495057; font-size: 14px;">
                        <strong>Total de registros:</strong> <?= $totalRegistros ?> | 
                        <strong>Página:</strong> <?= $pagina ?> de <?= $totalPaginas ?>
                        <?php if ($fechaInicio || $fechaFin): ?>
                            | <strong>Período:</strong> 
                            <?= $fechaInicio ? $this->formatearFecha($fechaInicio) : 'Inicio' ?> - 
                            <?= $fechaFin ? $this->formatearFecha($fechaFin) : 'Fin' ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($salidas)): ?>
                        <a href="../../export/export_pdf.php?tipo=salida&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>" 
                           target="_blank" 
                           class="btn" 
                           style="background-color: #dc3545; text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px;">
                            📄 Exportar PDF (RF40)
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Tabla de Salidas (RF42, RF44) -->
                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; background: white;">
                    <tr style="background-color: #ffc107; color: black;">
                        <th>Fecha y Hora</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Responsable (RF44)</th>
                    </tr>
                    <?php if (empty($salidas)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #6c757d;">
                                No se encontraron registros de salidas<?= ($fechaInicio || $fechaFin) ? ' en el rango de fechas seleccionado' : '' ?>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($salidas as $m): ?>
                            <tr>
                                <td><?= $this->formatearFechaHora($m['fecha']) ?></td>
                                <td><?= htmlspecialchars($m['producto']) ?></td>
                                <td><?= htmlspecialchars($m['codigo_lote']) ?></td>
                                <td style="text-align: center; color: #dc3545; font-weight: bold;">-<?= $m['cantidad'] ?></td>
                                <td><?= htmlspecialchars($m['motivo']) ?></td>
                                <td><?= htmlspecialchars($m['nombre_usuario'] ?? $m['usuario'] ?? 'Sistema') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>

                <!-- Paginación (RF42) -->
                <?php if ($totalPaginas > 1): ?>
                    <div style="margin-top: 20px; text-align: center;">
                        <?php
                        $params = http_build_query([
                            'fechaInicioSalida' => $fechaInicio,
                            'fechaFinSalida' => $fechaFin
                        ]);
                        ?>
                        <?php if ($pagina > 1): ?>
                            <a href="../../controllers/getReportes.php?<?= $params ?>&paginaSalida=<?= $pagina - 1 ?>" 
                               class="btn" style="background-color: #6c757d; color: white; padding: 8px 16px; text-decoration: none; margin: 0 5px; border-radius: 5px;">
                                « Anterior
                            </a>
                        <?php endif; ?>
                        
                        <span style="padding: 8px 16px; background: #e9ecef; border-radius: 5px; margin: 0 5px; color: #495057;">
                            Página <?= $pagina ?> de <?= $totalPaginas ?>
                        </span>
                        
                        <?php if ($pagina < $totalPaginas): ?>
                            <a href="../../controllers/getReportes.php?<?= $params ?>&paginaSalida=<?= $pagina + 1 ?>" 
                               class="btn" style="background-color: #6c757d; color: white; padding: 8px 16px; text-decoration: none; margin: 0 5px; border-radius: 5px;">
                                Siguiente »
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // ==========================================
    // FUNCIONES AUXILIARES PARA FORMATO DE FECHAS
    // ==========================================
    
    // Formatear fecha en formato español legible (ej: 15 de enero de 2025)
    private function formatearFecha($fecha) {
        if (empty($fecha)) return '';
        
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        
        $timestamp = strtotime($fecha);
        $dia = date('d', $timestamp);
        $mes = (int)date('m', $timestamp);
        $anio = date('Y', $timestamp);
        
        return $dia . ' de ' . $meses[$mes] . ' de ' . $anio;
    }
    
    // Formatear fecha y hora en formato español legible (ej: 15 de enero de 2025, 14:30)
    private function formatearFechaHora($fechaHora) {
        if (empty($fechaHora)) return '';
        
        $meses = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
        ];
        
        $timestamp = strtotime($fechaHora);
        $dia = date('d', $timestamp);
        $mes = (int)date('m', $timestamp);
        $anio = date('Y', $timestamp);
        $hora = date('H:i', $timestamp);
        
        return $dia . ' de ' . $meses[$mes] . ' de ' . $anio . ', ' . $hora . ' hrs';
    }
}
?>

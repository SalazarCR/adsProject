<?php
// views/reportes/FormReportes.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormReportes extends Formulario {

    // ==========================================
    // 1. VISTA PRINCIPAL - HISTORIAL Y SELECCIÓN DE TIPO DE REPORTE
    // Similar a formListarMovimientosShow - Muestra historial de movimientos
    // Incluye Kardex con saldo acumulado
    // ==========================================
    public function formListarReportesShow($movimientos = [], $kardex = []) {
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

                <!-- Búsqueda de movimientos - Campo en blanco para buscar -->
                <form method="GET" action="../../controllers/getReportes.php" style="margin-bottom: 15px;">
                    <input type="hidden" name="op" value="listar">
                    <input type="text" name="buscar" placeholder="Buscar por producto, lote, motivo, fecha o usuario..." 
                           value=""
                           style="padding: 8px; width: 400px; border: 1px solid #ddd; border-radius: 4px;">
                    <button type="submit" class="btn" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Buscar
                    </button>
                    <?php if (isset($_GET['buscar']) && $_GET['buscar'] !== ''): ?>
                        <a href="../../controllers/getReportes.php?op=listar" style="margin-left: 10px; color: #6c757d; text-decoration: underline;">
                            Limpiar búsqueda
                        </a>
                    <?php endif; ?>
                </form>

                <!-- KARDEX - Tabla con Entradas, Salidas y Saldo Acumulado -->
                <div style="background: white; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
                    <h3 style="background-color: #007bff; color: white; padding: 15px; margin: 0; border-bottom: 2px solid #0056b3;">
                        📊 Kardex de Inventario
                    </h3>
                    
                    <div style="overflow-x: auto;">
                        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; min-width: 800px;">
                            <tr style="background-color: #007bff; color: white;">
                                <th>Fecha y Hora</th>
                                <th>Tipo</th>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Saldo</th>
                                <th>Motivo</th>
                                <th>Responsable</th>
                            </tr>
                            <?php if (empty($kardex)): ?>
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 30px; color: #6c757d;">
                                        No se encontraron movimientos para el Kardex.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($kardex as $k): ?>
                                    <?php 
                                    $color = ($k['tipo'] == 'entrada') ? '#28a745' : '#dc3545';
                                    $entrada = ($k['tipo'] == 'entrada') ? $k['cantidad'] : '';
                                    $salida = ($k['tipo'] == 'salida') ? $k['cantidad'] : '';
                                    ?>
                                    <tr style="border-bottom: 1px solid #dee2e6;">
                                        <td><?= $this->formatearFechaHora($k['fecha']) ?></td>
                                        <td style="color:<?= $color ?>; font-weight:bold;"><?= strtoupper($k['tipo']) ?></td>
                                        <td><?= htmlspecialchars($k['producto']) ?></td>
                                        <td><?= htmlspecialchars($k['codigo_lote']) ?></td>
                                        <td style="text-align: center; color: #28a745; font-weight:bold;">
                                            <?= $entrada ? '+' . $entrada : '-' ?>
                                        </td>
                                        <td style="text-align: center; color: #dc3545; font-weight:bold;">
                                            <?= $salida ? '-' . $salida : '-' ?>
                                        </td>
                                        <td style="text-align: center; background-color: #e7f3ff; font-weight:bold; color: #007bff;">
                                            <?= $k['saldo'] ?>
                                        </td>
                                        <td><?= htmlspecialchars($k['motivo']) ?></td>
                                        <td><?= htmlspecialchars($k['nombre_usuario'] ?? $k['usuario'] ?? 'Sistema') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                    
                    <?php if (!empty($kardex)): ?>
                        <div style="padding: 15px; background: #f8f9fa; border-top: 2px solid #dee2e6; text-align: center;">
                            <a href="../../export/export_pdf.php?tipo=todos" target="_blank" 
                               class="btn" 
                               style="background-color: #dc3545; text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block;">
                                📄 Exportar Kardex Completo PDF
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tabla de Historial de Movimientos (Búsqueda) -->
                <?php if (isset($_GET['buscar']) && $_GET['buscar'] !== ''): ?>
                    <div style="background: white; border: 1px solid #dee2e6; border-radius: 8px; overflow: hidden; margin-bottom: 20px;">
                        <h3 style="background-color: #f8f9fa; padding: 15px; margin: 0; border-bottom: 2px solid #dee2e6; color: #495057;">
                            Resultados de Búsqueda
                        </h3>
                        
                        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                            <tr style="background-color: #e9ecef;">
                                <th>Fecha y Hora</th>
                                <th>Tipo</th>
                                <th>Producto</th>
                                <th>Lote</th>
                                <th>Cantidad</th>
                                <th>Motivo</th>
                                <th>Usuario</th>
                            </tr>
                            <?php if (empty($movimientos)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #6c757d;">
                                        No se encontraron movimientos.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($movimientos as $m): ?>
                                    <?php 
                                    $color = ($m['tipo'] == 'entrada') ? '#28a745' : '#dc3545';
                                    $simbolo = ($m['tipo'] == 'entrada') ? '+' : '-';
                                    ?>
                                    <tr style="border-bottom: 1px solid #dee2e6;">
                                        <td><?= $this->formatearFechaHora($m['fecha']) ?></td>
                                        <td style="color:<?= $color ?>; font-weight:bold;"><?= strtoupper($m['tipo']) ?></td>
                                        <td><?= htmlspecialchars($m['producto']) ?></td>
                                        <td><?= htmlspecialchars($m['codigo_lote']) ?></td>
                                        <td style="text-align: center; color:<?= $color ?>; font-weight:bold;">
                                            <?= $simbolo ?><?= $m['cantidad'] ?>
                                        </td>
                                        <td><?= htmlspecialchars($m['motivo']) ?></td>
                                        <td><?= htmlspecialchars($m['nombre_usuario'] ?? $m['usuario'] ?? 'Sistema') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // ==========================================
    // 2. VISTA DE REPORTE DE ENTRADA DE INVENTARIO
    // Caso de Uso: Generar Reporte de Entrada de Productos
    // Permite filtrar por fechas, ver registros paginados y exportar a PDF
    // ==========================================
    public function formReporteEntradaShow(
        $entradas,
        $fechaInicio = '',
        $fechaFin = '',
        $pagina = 1,
        $totalPaginas = 1,
        $totalRegistros = 0,
        $buscar = ''
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

                <!-- Filtro de Búsqueda General -->
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                    <form method="POST" action="../../controllers/getReportes.php" style="display: flex; gap: 10px; align-items: end;">
                        <div style="flex: 1;">
                            <label><b>Buscar:</b></label>
                            <input type="text" name="buscar" placeholder="Buscar por producto, lote, fecha, motivo, usuario..." 
                                   value="<?= htmlspecialchars($buscar) ?>"
                                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div>
                            <input type="hidden" name="paginaEntrada" value="1">
                            <button type="submit" name="btnGenerarEntrada" class="btn" style="background-color: #28a745; color: white; padding: 10px 20px; border-radius: 4px; border: none; cursor: pointer;">
                                Buscar
                            </button>
                        </div>
                        <?php if ($buscar !== ''): ?>
                            <a href="../../controllers/getReportes.php?op=entrada" style="padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                Limpiar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Información del Reporte y Opción de Exportación a PDF -->
                <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #dee2e6;">
                    <div style="color: #495057; font-size: 14px;">
                        <strong>Total:</strong> <?= $totalRegistros ?> | 
                        <strong>Página:</strong> <?= $pagina ?> de <?= $totalPaginas ?>
                    </div>
                    <?php if (!empty($entradas)): ?>
                        <a href="../../export/export_pdf.php?tipo=entrada&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>" 
                           target="_blank" 
                           class="btn" 
                           style="background-color: #dc3545; text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px;">
                            📄 Exportar PDF
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Tabla de Registros de Entrada: Muestra movimientos de entrada con paginación -->
                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; background: white;">
                    <tr style="background-color: #28a745; color: white;">
                        <th>Fecha y Hora</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Responsable</th>
                    </tr>
                    <?php if (empty($entradas)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #6c757d;">
                                No se encontraron registros.
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

                <!-- Navegación de Páginas: Permite navegar entre páginas de resultados -->
                <?php if ($totalPaginas > 1): ?>
                    <div style="margin-top: 20px; text-align: center;">
                        <?php
                        $params = http_build_query([
                            'op' => 'entrada',
                            'buscar' => $buscar
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
    // 3. VISTA DE REPORTE DE SALIDA DE INVENTARIO
    // Caso de Uso: Generar Reporte de Salida de Productos
    // Permite filtrar por fechas, ver registros paginados y exportar a PDF
    // ==========================================
    public function formReporteSalidaShow(
        $salidas,
        $fechaInicio = '',
        $fechaFin = '',
        $pagina = 1,
        $totalPaginas = 1,
        $totalRegistros = 0,
        $buscar = ''
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

                <!-- Filtro de Búsqueda General -->
                <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border: 1px solid #ffc107; margin-bottom: 20px;">
                    <form method="POST" action="../../controllers/getReportes.php" style="display: flex; gap: 10px; align-items: end;">
                        <div style="flex: 1;">
                            <label><b>Buscar:</b></label>
                            <input type="text" name="buscar" placeholder="Buscar por producto, lote, fecha, motivo, usuario..." 
                                   value="<?= htmlspecialchars($buscar) ?>"
                                   style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        </div>
                        <div>
                            <input type="hidden" name="paginaSalida" value="1">
                            <button type="submit" name="btnGenerarSalida" class="btn" style="background-color: #ffc107; color: black; padding: 10px 20px; border-radius: 4px; border: none; cursor: pointer;">
                                Buscar
                            </button>
                        </div>
                        <?php if ($buscar !== ''): ?>
                            <a href="../../controllers/getReportes.php?op=salida" style="padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                Limpiar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Información del Reporte y Opción de Exportación a PDF -->
                <div style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #dee2e6;">
                    <div style="color: #495057; font-size: 14px;">
                        <strong>Total:</strong> <?= $totalRegistros ?> | 
                        <strong>Página:</strong> <?= $pagina ?> de <?= $totalPaginas ?>
                    </div>
                    <?php if (!empty($salidas)): ?>
                        <a href="../../export/export_pdf.php?tipo=salida&inicio=<?= urlencode($fechaInicio) ?>&fin=<?= urlencode($fechaFin) ?>" 
                           target="_blank" 
                           class="btn" 
                           style="background-color: #dc3545; text-decoration: none; color: white; padding: 10px 20px; border-radius: 5px;">
                            📄 Exportar PDF
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Tabla de Registros de Salida: Muestra movimientos de salida con paginación -->
                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; background: white;">
                    <tr style="background-color: #ffc107; color: black;">
                        <th>Fecha y Hora</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cantidad</th>
                        <th>Motivo</th>
                        <th>Responsable</th>
                    </tr>
                    <?php if (empty($salidas)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #6c757d;">
                                No se encontraron registros.
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

                <!-- Navegación de Páginas: Permite navegar entre páginas de resultados -->
                <?php if ($totalPaginas > 1): ?>
                    <div style="margin-top: 20px; text-align: center;">
                        <?php
                        $params = http_build_query([
                            'op' => 'salida',
                            'buscar' => $buscar
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

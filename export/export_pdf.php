<?php
// export/export_pdf.php
// Reporte Técnico de Kardex - Formato Peruano
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Session.php';
Session::verificarSesion();

$tipo = $_GET['tipo'] ?? 'todos';
$ini  = $_GET['inicio'] ?? '';
$fin  = $_GET['fin'] ?? '';

// Función auxiliar para formatear fechas
function formatearFechaPDF($fecha) {
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

function formatearFechaHoraPDF($fechaHora) {
    if (empty($fechaHora)) return '';
    $timestamp = strtotime($fechaHora);
    return date('d/m/Y H:i', $timestamp);
}

$objMov = new Movimiento();
if ($tipo == 'todos') {
    $data = $objMov->obtenerTodos();
    $titulo = "KARDEX GENERAL DE INVENTARIO";
    $subtitulo = "Movimientos de Entrada y Salida";
} else {
    $data = $objMov->obtenerPorTipo($tipo, $ini, $fin);
    $titulo = "KARDEX DE " . strtoupper($tipo == 'entrada' ? 'ENTRADAS' : 'SALIDAS');
    $subtitulo = "Registro de Movimientos de Inventario";
}

// Calcular totales
$totalEntradas = 0;
$totalSalidas = 0;
$totalMovimientos = count($data);

foreach ($data as $d) {
    if ($d['tipo'] == 'entrada') {
        $totalEntradas += (int)$d['cantidad'];
    } else {
        $totalSalidas += (int)$d['cantidad'];
    }
}

// Información del documento
$numeroDocumento = 'KARDEX-' . strtoupper($tipo) . '-' . date('Ymd') . '-' . str_pad($totalMovimientos, 4, '0', STR_PAD_LEFT);
$fechaEmision = formatearFechaPDF(date('Y-m-d'));
$horaEmision = date('H:i:s');
$usuarioGenerador = $_SESSION['nombre'] ?? 'Sistema';
$rolGenerador = $_SESSION['rol'] ?? 'Usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kardex de Inventario - <?= strtoupper($tipo) ?></title>
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }
        
        /* Encabezado del documento */
        .encabezado {
            border: 3px solid #000;
            padding: 15px;
            margin-bottom: 20px;
            background: #f8f9fa;
        }
        
        .encabezado-superior {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .logo-empresa {
            width: 30%;
            text-align: left;
        }
        
        .info-empresa {
            width: 40%;
            text-align: center;
        }
        
        .info-empresa h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .info-empresa h2 {
            font-size: 14pt;
            font-weight: normal;
            color: #333;
        }
        
        .numero-documento {
            width: 30%;
            text-align: right;
            font-size: 9pt;
        }
        
        .numero-documento strong {
            display: block;
            margin-bottom: 5px;
            font-size: 10pt;
        }
        
        .datos-documento {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 9pt;
        }
        
        .dato-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px dotted #666;
        }
        
        .dato-label {
            font-weight: bold;
            margin-right: 10px;
        }
        
        /* Título principal */
        .titulo-principal {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #000;
            color: #fff;
        }
        
        .titulo-principal h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }
        
        .titulo-principal .subtitulo {
            font-size: 11pt;
            font-weight: normal;
            color: #e0e0e0;
        }
        
        /* Tabla de datos */
        .tabla-kardex {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9pt;
        }
        
        .tabla-kardex thead {
            background: #000;
            color: #fff;
        }
        
        .tabla-kardex th {
            border: 2px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
        }
        
        .tabla-kardex td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-size: 8.5pt;
        }
        
        .tabla-kardex tbody tr:nth-child(even) {
            background: #f5f5f5;
        }
        
        .tabla-kardex tbody tr:hover {
            background: #e8e8e8;
        }
        
        .col-fecha {
            width: 12%;
        }
        
        .col-tipo {
            width: 8%;
            font-weight: bold;
        }
        
        .col-producto {
            width: 25%;
            text-align: left;
            padding-left: 5px;
        }
        
        .col-lote {
            width: 12%;
        }
        
        .col-cantidad {
            width: 10%;
            font-weight: bold;
        }
        
        .col-motivo {
            width: 20%;
            text-align: left;
            padding-left: 5px;
            font-size: 8pt;
        }
        
        .col-usuario {
            width: 13%;
            font-size: 8pt;
        }
        
        .tipo-entrada {
            color: #006400;
            background: #e8f5e9;
        }
        
        .tipo-salida {
            color: #8b0000;
            background: #ffebee;
        }
        
        .cantidad-entrada {
            color: #006400;
            font-weight: bold;
        }
        
        .cantidad-salida {
            color: #8b0000;
            font-weight: bold;
        }
        
        /* Resumen y totales */
        .resumen {
            margin-top: 20px;
            border: 2px solid #000;
            padding: 15px;
            background: #f8f9fa;
        }
        
        .resumen h3 {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .resumen-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }
        
        .resumen-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #666;
            background: #fff;
        }
        
        .resumen-item .label {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .resumen-item .valor {
            font-size: 14pt;
            font-weight: bold;
            color: #000;
        }
        
        /* Pie de página */
        .pie-pagina {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            font-size: 9pt;
        }
        
        .firma-area {
            border-top: 2px solid #000;
            padding-top: 10px;
            text-align: center;
        }
        
        .firma-area .linea {
            border-top: 1px solid #000;
            margin: 40px 20px 5px 20px;
        }
        
        .firma-area .texto {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
        }
        
        .info-adicional {
            font-size: 8pt;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
        }
        
        /* No imprimir en pantalla */
        @media screen {
            body {
                background: #f0f0f0;
                padding: 20px;
            }
            .encabezado, .titulo-principal, .resumen {
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
        }
        
        @media print {
            body {
                background: #fff;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- ENCABEZADO DEL DOCUMENTO -->
    <div class="encabezado">
        <div class="encabezado-superior">
            <div class="logo-empresa">
                <strong>EMPRESA:</strong><br>
                <span style="font-size: 11pt; font-weight: bold;">SISTEMA DE INVENTARIO</span><br>
                <span style="font-size: 8pt;">Lima, Perú</span>
            </div>
            
            <div class="info-empresa">
                <h1>KARDEX DE INVENTARIO</h1>
                <h2>Registro de Movimientos</h2>
            </div>
            
            <div class="numero-documento">
                <strong>N° DOCUMENTO:</strong>
                <div style="font-size: 10pt; font-weight: bold; margin-top: 5px;">
                    <?= $numeroDocumento ?>
                </div>
            </div>
        </div>
        
        <div class="datos-documento">
            <div class="dato-item">
                <span class="dato-label">Fecha de Emisión:</span>
                <span><?= $fechaEmision ?> - <?= $horaEmision ?></span>
            </div>
            <div class="dato-item">
                <span class="dato-label">Generado por:</span>
                <span><?= htmlspecialchars($usuarioGenerador) ?> (<?= strtoupper($rolGenerador) ?>)</span>
            </div>
            <?php if($ini && $fin): ?>
            <div class="dato-item">
                <span class="dato-label">Período Reportado:</span>
                <span>Del <?= formatearFechaPDF($ini) ?> al <?= formatearFechaPDF($fin) ?></span>
            </div>
            <?php endif; ?>
            <div class="dato-item">
                <span class="dato-label">Total de Registros:</span>
                <span><strong><?= $totalMovimientos ?></strong> movimientos</span>
            </div>
        </div>
    </div>

    <!-- TÍTULO PRINCIPAL -->
    <div class="titulo-principal">
        <h1><?= $titulo ?></h1>
        <div class="subtitulo"><?= $subtitulo ?></div>
    </div>

    <!-- TABLA DE KARDEX -->
    <table class="tabla-kardex">
        <thead>
            <tr>
                <th class="col-fecha">FECHA Y HORA</th>
                <th class="col-tipo">TIPO</th>
                <th class="col-producto">PRODUCTO</th>
                <th class="col-lote">CÓD. LOTE</th>
                <th class="col-cantidad">CANTIDAD</th>
                <th class="col-motivo">MOTIVO / OBSERVACIÓN</th>
                <th class="col-usuario">RESPONSABLE</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="7" style="padding: 20px; text-align: center; font-style: italic; color: #666;">
                        No se encontraron registros para el período seleccionado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($data as $d): ?>
                <tr>
                    <td class="col-fecha"><?= formatearFechaHoraPDF($d['fecha']) ?></td>
                    
                    <td class="col-tipo <?= $d['tipo'] == 'entrada' ? 'tipo-entrada' : 'tipo-salida' ?>">
                        <?= $d['tipo'] == 'entrada' ? 'ENTRADA' : 'SALIDA' ?>
                    </td>
                    
                    <td class="col-producto"><?= htmlspecialchars($d['producto']) ?></td>
                    <td class="col-lote"><?= htmlspecialchars($d['codigo_lote']) ?></td>
                    
                    <td class="col-cantidad <?= $d['tipo'] == 'entrada' ? 'cantidad-entrada' : 'cantidad-salida' ?>">
                        <?= $d['tipo'] == 'entrada' ? '+' : '-' ?><?= number_format($d['cantidad'], 0, '.', '') ?>
                    </td>
                    
                    <td class="col-motivo"><?= htmlspecialchars($d['motivo']) ?></td>
                    <td class="col-usuario"><?= htmlspecialchars($d['nombre_usuario'] ?? $d['usuario'] ?? 'Sistema') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- RESUMEN Y TOTALES -->
    <div class="resumen">
        <h3>RESUMEN DE MOVIMIENTOS</h3>
        <div class="resumen-grid">
            <div class="resumen-item">
                <div class="label">Total Entradas</div>
                <div class="valor" style="color: #006400;">+<?= number_format($totalEntradas, 0, '.', ',') ?></div>
            </div>
            <div class="resumen-item">
                <div class="label">Total Salidas</div>
                <div class="valor" style="color: #8b0000;">-<?= number_format($totalSalidas, 0, '.', ',') ?></div>
            </div>
            <div class="resumen-item">
                <div class="label">Total Movimientos</div>
                <div class="valor"><?= $totalMovimientos ?></div>
            </div>
        </div>
    </div>

    <!-- PIE DE PÁGINA CON FIRMAS -->
    <div class="pie-pagina">
        <div class="firma-area">
            <div class="texto">Responsable del Almacén</div>
            <div class="linea"></div>
            <div style="margin-top: 5px; font-size: 8pt;">
                <?= htmlspecialchars($usuarioGenerador) ?><br>
                <?= strtoupper($rolGenerador) ?>
            </div>
        </div>
        
        <div class="firma-area">
            <div class="texto">Aprobado por</div>
            <div class="linea"></div>
            <div style="margin-top: 5px; font-size: 8pt;">
                _________________________<br>
                Firma y Sello
            </div>
        </div>
    </div>

    <!-- INFORMACIÓN ADICIONAL -->
    <div class="info-adicional">
        <strong>NOTA:</strong> Este documento ha sido generado automáticamente por el Sistema de Gestión de Inventario.<br>
        Documento válido únicamente para efectos de control interno. | <?= $numeroDocumento ?> | Página 1 de 1
    </div>

</body>
</html>

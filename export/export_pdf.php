<?php
// export/export_pdf.php
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

$objMov = new Movimiento();
if ($tipo == 'todos') {
    $data = $objMov->obtenerTodos();
    $titulo = "REPORTE GENERAL DE MOVIMIENTOS";
} else {
    $data = $objMov->obtenerPorTipo($tipo, $ini, $fin);
    $titulo = "REPORTE DE " . strtoupper($tipo) . "S";
    if ($ini && $fin) {
        $titulo .= " - Del " . formatearFechaPDF($ini) . " al " . formatearFechaPDF($fin);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; color: #333; }
        .info { text-align: center; margin-bottom: 20px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        .entrada { color: green; font-weight: bold; }
        .salida { color: red; font-weight: bold; }
    </style>
</head>
<body onload="window.print()">

    <h1><?= $titulo ?></h1>
    
    <div class="info">
        <strong>Fecha de Emisión:</strong> <?= formatearFechaHoraPDF(date('Y-m-d H:i:s')) ?><br>
        <?php if($ini && $fin): ?>
            <strong>Período del Reporte:</strong> Del <?= formatearFechaPDF($ini) ?> al <?= formatearFechaPDF($fin) ?>
        <?php endif; ?>
        <br>
        <strong>Generado por:</strong> <?= $_SESSION['nombre'] ?? 'Sistema' ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>FECHA</th>
                <th>TIPO</th>
                <th>PRODUCTO</th>
                <th>LOTE</th>
                <th>CANT.</th>
                <th>MOTIVO</th>
                <th>USUARIO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $d): ?>
            <tr>
                <td><?= formatearFechaHoraPDF($d['fecha']) ?></td>
                
                <?php $clase = ($d['tipo'] == 'entrada') ? 'entrada' : 'salida'; ?>
                <td class="<?= $clase ?>"><?= strtoupper($d['tipo']) ?></td>
                
                <td><?= htmlspecialchars($d['producto']) ?></td>
                <td><?= htmlspecialchars($d['codigo_lote']) ?></td>
                <td><?= $d['cantidad'] ?></td>
                <td><?= htmlspecialchars($d['motivo']) ?></td>
                <td><?= htmlspecialchars($d['nombre_usuario'] ?? $d['usuario'] ?? 'Sistema') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
<?php
// export/export_pdf.php
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Session.php';
Session::verificarSesion();

$tipo = $_GET['tipo'] ?? 'todos';
$ini  = $_GET['inicio'] ?? '';
$fin  = $_GET['fin'] ?? '';

$objMov = new Movimiento();
if ($tipo == 'todos') {
    $data = $objMov->obtenerTodos();
    $titulo = "REPORTE GENERAL DE MOVIMIENTOS";
} else {
    $data = $objMov->obtenerPorTipo($tipo, $ini, $fin);
    $titulo = "REPORTE DE " . strtoupper($tipo) . "S";
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
        <strong>Fecha de Emisión:</strong> <?= date('d/m/Y H:i:s') ?><br>
        <?php if($ini && $fin): ?>
            <strong>Rango de Fechas:</strong> Del <?= $ini ?> al <?= $fin ?>
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
                <td><?= $d['fecha'] ?></td>
                
                <?php $clase = ($d['tipo'] == 'entrada') ? 'entrada' : 'salida'; ?>
                <td class="<?= $clase ?>"><?= strtoupper($d['tipo']) ?></td>
                
                <td><?= htmlspecialchars($d['producto']) ?></td>
                <td><?= htmlspecialchars($d['codigo_lote']) ?></td>
                <td><?= $d['cantidad'] ?></td>
                <td><?= htmlspecialchars($d['motivo']) ?></td>
                <td><?= htmlspecialchars($d['usuario']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
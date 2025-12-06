<?php
// export/export_excel.php
require_once __DIR__ . '/../core/Movimiento.php';
require_once __DIR__ . '/../core/Session.php';
Session::verificarSesion();

// 1. Recibir Filtros
$tipo = $_GET['tipo'] ?? 'todos';
$ini  = $_GET['inicio'] ?? '';
$fin  = $_GET['fin'] ?? '';

// 2. Obtener Datos
$objMov = new Movimiento();
if ($tipo == 'todos') {
    $data = $objMov->obtenerTodos();
} else {
    $data = $objMov->obtenerPorTipo($tipo, $ini, $fin);
}

// 3. Configurar cabeceras para descarga CSV compatible con Excel
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename=reporte_'.$tipo.'.csv');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// [TRUCO] Agregar BOM para que Excel lea bien las tildes (UTF-8)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// 4. Encabezados EXACTOS como pediste
fputcsv($output, ['ID', 'FECHA', 'TIPO', 'PRODUCTO', 'LOTE', 'CANTIDAD', 'MOTIVO', 'USUARIO']);

// 5. Datos
foreach ($data as $row) {
    fputcsv($output, [
        $row['id'],
        $row['fecha'],
        strtoupper($row['tipo']), // ENTRADA / SALIDA en mayúsculas
        $row['producto'],
        $row['codigo_lote'],
        $row['cantidad'],
        $row['motivo'],
        $row['usuario']
    ]);
}

fclose($output);
exit;
?>
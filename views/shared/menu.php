<?php
// views/shared/menu.php
require_once __DIR__ . '/../../core/Session.php';
Session::start();
$rol = Session::get('rol') ?? 'invitado';

// Detectar si estamos en la sección de reportes
$esReportes = (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'getReportes.php') !== false);
?>

<nav class="main-menu">
<?php if ($rol === 'admin'): ?>
    <a href="../../controllers/getUsuarios.php?op=listar">Administrar Usuarios</a>
    <a href="../../controllers/getProductos.php?op=listar">Administrar Productos</a>
    <a href="../../controllers/getCategorias.php?op=listar">Administrar Categorías</a>
    <a href="../../controllers/getProveedores.php?op=listar">Administrar Proveedores</a>
    <a href="../../controllers/getMovimientos.php?op=listar">Gestionar Entradas/Salidas</a>
    <?php if (!$esReportes): ?>
        <a href="../../controllers/getReportes.php?op=listar">Gestión de Reportes</a>
    <?php endif; ?>

<?php elseif ($rol === 'analista'): ?>
    <?php if (!$esReportes): ?>
        <a href="../../controllers/getReportes.php?op=listar">Gestión de Reportes</a>
    <?php endif; ?>

<?php elseif ($rol === 'auxiliar'): ?>
    <a href="../../controllers/getMovimientos.php?op=listar">Gestionar Entradas/Salidas</a>

  <?php else: ?>
    <span>Acceso limitado</span>
  <?php endif; ?>
</nav>
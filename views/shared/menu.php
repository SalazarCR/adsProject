<?php
// views/shared/menu.php
require_once __DIR__ . '/../../core/Session.php';
Session::start();
$rol = Session::get('rol') ?? 'invitado';
?>

<nav class="main-menu">
<?php if ($rol === 'admin'): ?>
    <a href="../../controllers/getUsuarios.php?op=listar">Administrar Usuarios</a>
    <a href="../../controllers/getProductos.php?op=listar">Administrar Productos</a>
    <a href="../../controllers/getCategorias.php?op=listar">Administrar Categorías</a>
    <a href="../../controllers/getProveedores.php?op=listar">Administrar Proveedores</a>
    <a href="../../controllers/getMovimientos.php?op=listar">Gestionar Entradas/Salidas</a>
    <a href="../../controllers/getReportes.php?op=listar">Gestión de Reportes</a>

<?php elseif ($rol === 'analista'): ?>
    <a href="../../controllers/getReportes.php?op=listar">Gestión de Reportes</a>

<?php elseif ($rol === 'auxiliar'): ?>
    <a href="../../controllers/getMovimientos.php?op=listar">Gestionar Entradas/Salidas</a>

  <?php else: ?>
    <span>Acceso limitado</span>
  <?php endif; ?>
</nav>
<?php

// CORRECCIÓN: Solo un "../" para ir de 'home' a 'views'
require_once __DIR__ . '/../shared/Formulario.php'; 

// Este sí necesita dos "../" para ir a la raíz y entrar a core
require_once __DIR__ . '/../../core/Session.php';

class FormPanelControl extends Formulario {

    public function formPanelControlShow() {
        // 1. Cumplir Teoría: Validar sesión al inicio
        Session::verificarSesion();
        
        // 2. Cumplir Teoría: Usar métodos de la clase padre para Header y Menú
        $this->cabeceraShow(); 
        $this->menuShow();
        
        // Datos para la vista
        $nombre = Session::get('nombre');
        $rol = Session::get('rol');
        ?>

        <main class="dashboard">
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="mensaje-sistema"><?= htmlspecialchars($_SESSION['mensaje']) ?></div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>

            <section class="panel-welcome">
                <h1>Bienvenido</h1>
                <h2><?= htmlspecialchars($nombre) ?></h2>
                <p>Rol: <?= htmlspecialchars($rol) ?></p>
                <div class="btn-config">
                    <a href="../../controllers/getUsuarios.php?op=actualizar_pass&id=<?= Session::get('usuario_id') ?>">
                        Actualizar contraseña
                    </a>
                </div>

            </section>

            <section class="panel-tiles">
                <?php if ($rol === 'admin'): ?>
                    <article class="tile">
                        <a href="../../controllers/getUsuarios.php?op=listar">Administrar usuarios</a>
                    </article>
                    <article class="tile">
                        <a href="../../controllers/getProveedores.php?op=listar">Administrar proveedores</a>
                    </article>
                    <article class="tile">
                        <a href="../../controllers/getProductos.php?op=listar">Administrar Productos</a>
                    </article>
                <?php endif; ?>

                <?php if ($rol === 'admin' || $rol === 'auxiliar'): ?>
                    <article class="tile">
                        <a href="../movimientos/listar.php">Gestionar entradas/salidas</a>
                    </article>
                <?php endif; ?>

                <?php if ($rol === 'admin' || $rol === 'analista'): ?>
                    <article class="tile">
                        <a href="../../index.php?controller=Reporte&action=entradas">Generar reportes</a>
                    </article>
                <?php endif; ?>
            </section>
        </main>
        <?php
        // 3. Cumplir Teoría: Pie de página
        $this->piePaginaShow();
    }
}
?>
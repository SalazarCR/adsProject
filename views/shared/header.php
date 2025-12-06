<?php
require_once __DIR__ . '/../../core/Session.php';
Session::start();

// Verificamos si realmente hay alguien logueado
$is_logged = Session::get('usuario_id') !== null;
$nombre = Session::get('nombre') ?? ''; 
?>

<header class="header">
  <div class="title">MyProgram 1.0.1</div>
  
  <div class="user-area">
    <?php if ($is_logged): ?>
        <div class="welcome">Bienvenido, <?= htmlspecialchars($nombre) ?></div>
        
        <form method="POST" action="../../controllers/getCerrarSesion.php" style="display:inline;">
            <button type="submit" name="btnCerrarSesion" class="btn-logout" style="border:none; cursor:pointer;">
                Cerrar sesión
            </button>
        </form>
    <?php else: ?>
        <div class="welcome">Bienvenido, Invitado</div>
    <?php endif; ?>
  </div>
</header>
<?php
// views/usuarios/FormUsuarios.php
require_once __DIR__ . '/../shared/Formulario.php';

class FormUsuarios extends Formulario {

    // 1. PANTALLA LISTAR USUARIOS
    public function formListarUsuariosShow($usuarios) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <main class="dashboard">
            <div class="container" style="padding: 20px; width: 100%;">
                <h2>Administrar Usuarios</h2>
                
                <div style="margin-bottom: 20px;">
                    <form action="../../controllers/getUsuarios.php" method="POST" style="display:inline;">
                        <button type="submit" name="btnIrCrear" class="btn">Crear Nuevo Usuario</button>
                    </form>
                    <a href="../views/home/dashboard.php" style="margin-left: 10px; text-decoration: underline; color: #333;">Retroceder</a>
                </div>

                <form method="GET" style="margin-bottom: 15px;">
                    <input type="text" name="buscar" placeholder="Buscar..." 
                    value="<?= isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : '' ?>">
                <button type="submit">Buscar</button>
                </form>


                <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #ddd;">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Email</th>    <th>Teléfono</th> <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                    <?php foreach ($usuarios as $u): ?>
                        <?php 
                            $estado = $u['estado_tmp'] ?? 'inactivo';
                            $color = ($estado === 'activo') ? 'green' : 'red';
                        ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['nombre']) ?></td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['rol']) ?></td>
                            <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>    <td><?= htmlspecialchars($u['telefono'] ?? '-') ?></td> <td style="color: <?= $color ?>; font-weight:bold;"><?= ucfirst($estado) ?></td>
                            <td style="text-align: center;">
                                <form action="../../controllers/getUsuarios.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_usuario" value="<?= $u['id'] ?>">
                                    <button type="submit" name="btnIrEditar" style="cursor:pointer;">Editar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </main>
        <?php
        $this->piePaginaShow();
    }

    // 2. PANTALLA CREAR USUARIO
    public function formCrearUsuarioShow() {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Crear Usuario</h2>
            <form action="../../controllers/getUsuarios.php" method="POST">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>

                <label>Usuario (Login):</label>
                <input type="text" name="username" required>

                <label>Contraseña:</label>
                <input type="password" name="password" required>

                <label>Email:</label>
                <input type="email" name="email">

                <label>Teléfono:</label>
                <input type="text" name="telefono">
                <label>Rol:</label>
                <select name="rol">
                    <option value="admin">Admin</option>
                    <option value="auxiliar">Auxiliar</option>
                    <option value="analista">Analista</option>
                </select>

                <label>Estado:</label>
                <select name="estado_tmp">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>

                <br><br>
                <button type="submit" name="btnRegistrar">Guardar Usuario</button>
                <br><br>
                <a href="../../controllers/getUsuarios.php?op=listar" style="text-decoration: underline; color: #333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }

    // 3. PANTALLA EDITAR USUARIO
    public function formEditarUsuarioShow($usuario) {
        $this->cabeceraShow();
        $this->menuShow();
        ?>
        <div class="login-box" style="margin-top: 20px;">
            <h2>Editar Usuario</h2>
            <form action="../../controllers/getUsuarios.php" method="POST">
                
                <input type="hidden" name="id_usuario" value="<?= $usuario['id'] ?>">

                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

                <label>Usuario (Login):</label>
                <input type="text" name="username" value="<?= htmlspecialchars($usuario['username']) ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>">

                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                <label>Rol:</label>
                <select name="rol">
                    <option value="admin" <?= $usuario['rol']=='admin'?'selected':'' ?>>Admin</option>
                    <option value="auxiliar" <?= $usuario['rol']=='auxiliar'?'selected':'' ?>>Auxiliar</option>
                    <option value="analista" <?= $usuario['rol']=='analista'?'selected':'' ?>>Analista</option>
                </select>

                <label>Estado:</label>
                <select name="estado_tmp">
                    <option value="activo" <?= $usuario['estado_tmp']=='activo'?'selected':'' ?>>Activo</option>
                    <option value="inactivo" <?= $usuario['estado_tmp']=='inactivo'?'selected':'' ?>>Inactivo</option>
                </select>

                <br><br>
                <button type="submit" name="btnActualizar">Actualizar Datos</button>
                <br><br>
                <a href="../../controllers/getUsuarios.php?op=listar" style="text-decoration: underline; color: #333;">Retroceder</a>
            </form>
        </div>
        <?php
        $this->piePaginaShow();
    }
}
?>
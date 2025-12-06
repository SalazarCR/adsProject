<!DOCTYPE html>
<html>
<head>
    <title>Hackear Login</title>
    <style>body{font-family:sans-serif; text-align:center; padding:50px;}</style>
</head>
<body>
    <h2 style="color:red">😈 Intento de Acceso Ilegal</h2>
    <p>Este formulario intenta enviar usuario y contraseña válidos al controlador,</p>
    <p>pero <b>NO presiona el botón correcto</b> ('btnIngresar').</p>
    
    <form method="POST" action="controllers/getUsuario.php">
        
        <input type="hidden" name="usuario" value="admin">
        <input type="hidden" name="password" value="123">
        
        <button type="submit" name="btnHacker" style="padding:10px 20px; font-size:18px; cursor:pointer;">
            Forzar Entrada
        </button>
    
    </form>
</body>
</html>
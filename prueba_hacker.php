<!DOCTYPE html>
<html>
<head>
    <title>Simulación de Ataque</title>
</head>
<body>
    <h1>Prueba de Seguridad: Validar Botón</h1>
    <p>Este formulario intenta ejecutar el cierre de sesión, pero el botón tiene el nombre incorrecto.</p>
    
    <form method="POST" action="controllers/getCerrarSesion.php">
        
        <button type="submit" name="btnFalso">Intentar Hackear Logout</button>
    
    </form>
</body>
</html>
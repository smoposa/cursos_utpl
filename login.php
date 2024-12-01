<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: perfil.php");
    exit;  
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ingresar</title>
        <link rel="icon" type="image/x-icon" href="img/favicon.png">
        <meta charset="UTF-8">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/estilos.css">
    </head>
    
    <body>
    <header class="header">
        <div class="container">
            <img src="img/utpl_logo_blnc2.png" alt="Logo" class="logo">
            <nav class="menu">
                <ul>
                    <li><a href="index.html" class="btn">Inicio</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="contenedor">
            <div class="caja">
                <div class="caja-login">
                    <h2>Inicie Sesión <br>para acceder a los cursos</h2>   
                    <form action="procesar_login.php" method="POST">
                        <input name="email" type="email" placeholder="Correo" id="email"  required>
                        <input name="password" type="password" placeholder="Contraseña" id="password"  required>
                        <button type="submit" class="btn">Ingresar</button>
                    </form><br>
                   
                    <!-- Enlaces -->
                    <p >¿No tienes una cuenta?  &nbsp; <a href="registro.html" class="registro-enlace">Registrate</a></p><br>
                </div>
            </div>
        </div>
    </main><br><br><br><br>
    <footer class="footer">
    </footer>
    </body>
</html>



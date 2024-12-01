<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = 'localhost';
    $db = 'cursos_utpl';  
    $user = 'root';      
    $password_db = '';  

    // Crear y verifica la conexión
    $conn = new mysqli($host, $user, $password_db, $db);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consultar usuario con el email proporcionado
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();

    // Variable para el mensaje
    $mensaje = "";

    // Proceso
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];

            header("Location: perfil.php");
            exit;
        } else {
            $mensaje = "Contraseña Incorrecta.<br> Intenta nuevamente.";            
        }
    } else {
        $mensaje = "Usuario no existe<br>Correo no registrado.";
    }

    $_SESSION['mensaje'] = $mensaje;

    // Cerrar conexión
    $conn->close();
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
                    <li><a href="login.php" class="btn">Regresar</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="contenedor">
            <div class="login-caja"><br>
                <?php
                if (isset($_SESSION['mensaje'])) {
                    echo "<div class='mensaje' style='color: #033166; font-size: 25px; font-weight: bold; font-family: Arial, sans-serif;'>" . $_SESSION['mensaje'] . "</div>";
                    unset($_SESSION['mensaje']);
                }
                ?>
                <br>
            </div>
        </div>
    </main><br><br><br><br>
    <footer class="footer"></footer>
    </body>
</html>

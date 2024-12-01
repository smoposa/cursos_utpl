<?php
session_start(); 

$host = 'localhost';
$db = 'cursos_utpl';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$password = $_POST['password'];

$password_hashed = password_hash($password, PASSWORD_BCRYPT);

$sql_verificar_email = "SELECT id FROM usuarios WHERE email = '$email'";
$resultado = $conn->query($sql_verificar_email);

if ($resultado->num_rows > 0) {
    die("Error: El correo electrónico ya está registrado. Por favor, utiliza otro correo.");
}

$sql_usuario = "INSERT INTO usuarios (nombre, email, telefono, password) VALUES ('$nombre', '$email', '$telefono', '$password_hashed')";

if ($conn->query($sql_usuario) === TRUE) {
    $usuario_id = $conn->insert_id; 

    $_SESSION['user_id'] = $usuario_id;
    $_SESSION['user_name'] = $nombre;

    header("Location: perfil.php");
    exit;
} else {
    echo "Error al registrar el usuario: " . $conn->error;
}

$conn->close();
?>

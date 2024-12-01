<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Debes iniciar sesión para inscribirte.";
    exit;
}

$host = 'localhost';
$db = 'cursos_utpl';  
$user = 'root';      
$password_db = '';   

$conn = new mysqli($host, $user, $password_db, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario_id = $_SESSION['user_id'];
$curso_id = $_POST['curso_id'];

$sql_verificar = "SELECT * FROM inscripciones WHERE usuario_id = ? AND curso_id = ?";
$stmt = $conn->prepare($sql_verificar);
$stmt->bind_param("ii", $usuario_id, $curso_id); 
$stmt->execute();
$result_verificar = $stmt->get_result();

if ($result_verificar->num_rows > 0) {
    echo "Ya estás inscrito en este curso.";
} else {
    $sql_inscripcion = "INSERT INTO inscripciones (usuario_id, curso_id) VALUES (?, ?)";
    $stmt_inscripcion = $conn->prepare($sql_inscripcion);
    $stmt_inscripcion->bind_param("ii", $usuario_id, $curso_id);

    if ($stmt_inscripcion->execute()) {
        echo "¡Inscripción exitosa!";
    } else {
        echo "Error al inscribirte: " . $conn->error;
    }
}

$stmt->close();  
if (isset($stmt_inscripcion)) {
    $stmt_inscripcion->close();  
}
$conn->close();  
?>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$db = 'cursos_utpl';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql_cursos = "SELECT * FROM cursos";
$result_cursos = $conn->query($sql_cursos);

$user_id = $_SESSION['user_id']; 
$sql_inscripciones = "SELECT c.id, c.nombre, c.descripcion, c.imagen FROM cursos c 
                      INNER JOIN inscripciones i ON c.id = i.curso_id 
                      WHERE i.usuario_id = ?";
$stmt = $conn->prepare($sql_inscripciones);
$stmt->bind_param('i', $user_id); 
$stmt->execute();
$result_inscripciones = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <img src="img/utpl_logo_blnc2.png" alt="Logo" class="logo">
            <nav class="menu">
                <ul>
                    <li><a href="logout.php" class="btn">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h1>¡Hola, <?php echo $_SESSION['user_name']; ?>  ! 👋</h1> <br><br>

            <form id="inscripcion-form" action="procesar_inscripcion.php" method="POST" class="perfil-form">
    <h2>Selecciona un Curso</h2>
    <select name="curso_id" id="curso_id" required>
        <option value="" disabled selected>- Seleccione un curso -</option>
        <?php
        while ($curso = $result_cursos->fetch_assoc()) {
            echo "<option value='" . $curso['id'] . "'>" . $curso['nombre'] . "</option>";
        }
        ?>
    </select>
    <button type="submit">Inscribirme</button>
</form>

<div id="mensaje"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#inscripcion-form").on("submit", function(event) {
            event.preventDefault(); 

            var curso_id = $("#curso_id").val(); 

            // Enviar los datos con AJAX
            $.ajax({
                url: "procesar_inscripcion.php", 
                type: "POST",
                data: { curso_id: curso_id }, 
                success: function(response) {
                    $("#mensaje").html(response);
                },
                error: function() {
                    $("#mensaje").html("<p style='color:red;'>Hubo un error al procesar tu inscripción. Intenta nuevamente.</p>");
                }
            });
        });
    });
</script>

        </div>
    </main>

    <!-- Cursos Disponibles -->
    <div class="container">
        <div class="row">
            <div class="profile"><br>
                <h2>Cursos Inscritos</h2>
                <div class="row">
                    <?php
                    if ($result_inscripciones->num_rows > 0) {
                        // Si el usuario tiene cursos inscritos
                        while ($curso = $result_inscripciones->fetch_assoc()) {
                            // Construir la ruta de la imagen
                            $ruta_imagen = 'img/' . $curso['imagen']; // Ruta relativa de la imagen
                            echo "
                            <div class='col-md-4'>
                                <div class='blog-left'>
                                    <div class='blog-img'>
                                        <img src='" . $ruta_imagen . "' alt='" . $curso['nombre'] . "'>
                                    </div>
                                    <div class='blog-content'>
                                        <h3 class='title'>" . $curso['nombre'] . "</h3>
                                        <p>" . $curso['descripcion'] . "</p>
                                    </div>

                                </div>
                            </div>
                            ";
                        }
                    } else {
                        // Si el usuario no tiene cursos inscritos
                        echo "<p>No tienes cursos inscritos.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
    <!-- Fin Cursos Disponibles -->

    <footer class="footer">
        <p>Derechos Reservados</p>
    </footer>

</body>
</html>

<?php
// Cerrar conexión a la base de datos
$conn->close();
?>

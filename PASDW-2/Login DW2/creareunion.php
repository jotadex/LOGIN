<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); 
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $invitados = explode(',', $_POST['invitados']); 

    
    $conn = new mysqli('localhost', 'root', '', 'logindatabase');

    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

   
    $titulo = $conn->real_escape_string($titulo);
    $descripcion = $conn->real_escape_string($descripcion);

    
    $creador_id = $_SESSION['id'];

    
    $sql = "INSERT INTO reuniones (titulo, descripcion, fecha, hora, creador_id) VALUES ('$titulo', '$descripcion', '$fecha', '$hora', '$creador_id')";

    if ($conn->query($sql) === TRUE) {
        $reunion_id = $conn->insert_id; 

        
        $sql_acta = "INSERT INTO actas (reunion_id) VALUES ('$reunion_id')";
        if ($conn->query($sql_acta) !== TRUE) {
            echo "Error al insertar en la tabla acta: " . $conn->error;
        }

        
        $subject = "Invitación a Reunión: $titulo";
        $message = "¡Te invitamos a nuestra próxima reunión!<br>";
        $message .= "Título: $titulo<br>";
        $message .= "Descripción: $descripcion<br>";
        $message .= "Fecha: $fecha<br>";
        $message .= "Hora: $hora<br>";
        $message .= "¡Esperamos verte allí!";

        foreach ($invitados as $invitado) {
            $invitado = trim($invitado); 

            
            $sql_usuario = "SELECT id FROM usuarios WHERE email = '$invitado'";
            $result_usuario = $conn->query($sql_usuario);

            if ($result_usuario->num_rows > 0) {
                $fila_usuario = $result_usuario->fetch_assoc();
                $usuario_id = $fila_usuario['id'];

                $sql_invitacion = "INSERT INTO invitaciones (reunion_id, usuario_id) VALUES ('$reunion_id', '$usuario_id')";
                if ($conn->query($sql_invitacion) !== TRUE) {
                    echo "Error al insertar en la tabla de invitaciones: " . $conn->error;
                }
            } else {
                echo "No se encontró un usuario con el correo electrónico: $invitado";
            }
        }

        
        header("Location: pagina_de_inicio.php");
        exit();
    } else {
        echo "Error al crear la reunión: " . $conn->error;
    }

    
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Reunión</title>
  <link rel="stylesheet" href="css.css">
</head>
<body>
  <div class="container">
    <h2>Crear Reunión</h2>
    <form method="POST" action="">
      <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
      </div>
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>
      </div>
      <div class="form-group">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
      </div>
      <div class="form-group">
        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required>
      </div>
      <div class="form-group">
        <label for="invitados">Invitados (separados por comas):</label>
        <input type="text" id="invitados" name="invitados" required>
      </div>
      <!-- Puedes agregar más campos según sea necesario, como la lista de usuarios para invitar -->
      <button type="submit">Crear Reunión </button>
    </form>
  </div>
</body>
</html>


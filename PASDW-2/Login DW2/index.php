<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    $conn = new mysqli('localhost', 'root', "", 'logindatabase');

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $usuario = $conn->real_escape_string($usuario);

    $sql = "SELECT contraseña FROM usuarios WHERE usuario='$usuario'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $fila = $result->fetch_assoc();
        $contraseña_hash = $fila['contraseña'];

        if (password_verify($contraseña, $contraseña_hash)) {
          $sql_id = "SELECT id FROM usuarios WHERE usuario='$usuario'";
          $result_id = $conn->query($sql_id);
      
          if ($result_id->num_rows == 1) {
              $fila_id = $result_id->fetch_assoc();
              $_SESSION['id'] = $fila_id['id'];
          }
      
          $_SESSION['usuario'] = $usuario;
          header("Location: paginadeinicio.php");
          exit();
      } else {
          $error = "Usuario o contraseña incorrectos";
      }
      

    $conn->close();
}}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión</title>
  <link rel="stylesheet" href="css.css">
</head>
<body>
  <div class="container">
    <h2>¡Bienvenido!</h2>
    <?php if ($error != ""): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="form-group">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
      </div>
      <div class="form-group">
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
      </div>
      <div class="form-group">
        <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
      </div>
      <div class="form-group">
        <a href="signup.php" class="create-account">Crear una cuenta</a>
      </div>
      <button type="submit">Iniciar Sesión</button>
    </form>
  </div>
</body>
</html>


  
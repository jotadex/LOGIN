<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $email = $_POST['email'];

    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', "", 'logindatabase');

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $usuario = $conn->real_escape_string($usuario);
    $email = $conn->real_escape_string($email);

    $consulta = "SELECT * FROM usuarios WHERE usuario='$usuario' OR email='$email'";
    $resultado = $conn->query($consulta);

    if ($resultado->num_rows > 0) {
        $error = "El usuario o el correo electrónico ya existen. Por favor, elige otro nombre de usuario o correo.";
    } else {
        $sql = "INSERT INTO usuarios (usuario, contraseña, email) VALUES ('$usuario', '$hash', '$email')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Error al crear la cuenta: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta</title>
  <link rel="stylesheet" href="css.css">
</head>
<body>
  <div class="container">
    <h2>¡Bienvenido!</h2>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="form-group">
        <label for="usuario">Crear Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
      </div>
      <div class="form-group">
        <label for="contraseña">Crear Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
      </div>
      <div class="form-group">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required style="width: 100%;">
      </div>
      <button type="submit">Crear cuenta</button>
    </form>
  </div>
</body>
</html>

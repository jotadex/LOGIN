<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link rel="stylesheet" href="css.css">
</head>
<body>
  <div class="container">
    <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?>!</h2>
    <div class="opciones">
      <a href="creareunion.php" class="boton">Crear Reunión</a>
      <a href="gestion_actas.php" class="boton">Gestionar Actas</a>
    </div>
    <a href="index.php" class="boton-salir">Salir</a>
  </div>
</body>
</html>

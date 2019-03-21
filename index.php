<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/script.js"></script>
</head>

<body id="body_index">
  <div class="izq">
    <form id="login" action="scripts/login.php" method="post">
      <div>
        <label>Usuario:</label>
        <input type="text" name="usuario" />
      </div>
      <div>
        <label>Password:</label>
        <input type="password" name="password" />
      </div>
      <?php
      session_start();
      if(!empty($_SESSION["mensajeIndex"])){
        echo "<p id='errorLogin'>".$_SESSION["mensajeIndex"]."</p>";
        unset($_SESSION["mensajeIndex"]);
      } ?>
      <input type="submit" value="Acceder" />
    </form>
    <button id="boton_mensaje">Enviar mensaje al Administrador</button>
    <form id="mensaje" name="mensaje">
      <label>Asunto:</label>
      <input id="asunto" type="text" />
      <label>Descripción:</label>
      <textarea id="descripcion"></textarea>
      <button id="boton_enviar">Enviar</button>
    </form>
  </div>
  <div class="der">
    <span>
      <h3>Bienvenido a la aplicación web de gestión de concesionarios</h3>
      <p>Concesionarios y proveedores pueden ver y administrar los pedidos de forma sencilla y llevar un control completo sobre su negocio</p>
    </span>
  </div>
  <img src="images/mate.png" />
</body>

</html>

<?php
  require_once __DIR__.'/../dao/UsuarioDAO.php';
  require_once __DIR__.'/../dao/MensajeDAO.php';

  session_start();
  if(isset($_SESSION["usuario"])){
  	$usuarioActual = unserialize($_SESSION["usuario"]);
  }
  if(!isset($_SESSION["usuario"]) || $usuarioActual->getRol() != "Administrador") {
    header("Location: ../scripts/logout.php");
  	exit();
  }
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Administrador</title>
  <link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
</head>

<body>
  <button class="boton_pestana" onclick="desplegar(event, 'admin_usuarios')">Gestionar usuarios</button>
  <div id="admin_usuarios" class="contenido_pestana">
    <button class="alta_usuario">Dar de alta nuevo usuario</button>
    <form class="alta_usuario">
      <label>Nombre</label>
      <input type="text" name="nombre" maxlength="200">
      <label>Login</label>
      <input type="text" name="login" required="required" maxlength="15">
      <label>Contraseña</label>
      <input type="password" name="contrasena" required="required" maxlength="20">
      <label>Rol</label>
      <select name="rol" required="required">
        <option value=""></option>
        <option value="Concesionario">Concesionario</option>
        <option value="Proveedor">Proveedor</option>
      </select>
      <input type="submit" value="Dar de alta">
    </form>
    <ul class="instrucciones">
      <li>Puede desloguear a un usuario logueado haciendo click en la celda correspondiente de la columna <i>Logueado</i></li>
      <li>Puede bloquear/desbloquear a un usuario haciendo click en la celda correspondiente de la columna <i>Bloqueado</i></li>
      <li>Puede dar de baja/alta a un usuario haciendo click en la celda correspondiente de la columna <i>Dado de baja</i></li>
    </ul>
    <table>
      <caption>USUARIOS NO ADMINISTRADORES</caption>
      <thead>
        <tr>
          <th>Id</th>
          <th>Nombre</th>
          <th>Login</th>
          <th>Rol</th>
          <th>Logueado</th>
          <th>Bloqueado</th>
          <th>Dado de baja</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $usuarios = getUsuarios();
          $contador = 0;
          if (count($usuarios) > 0)
            foreach ($usuarios as $usuario)
              if($usuario->getRol() != "Administrador") {
                $contador++;
        ?>
        <tr>
          <td class="id_usuario"><?php echo $usuario->getId() ?></td>
          <td><?php echo $usuario->getNombre() ?></td>
          <td><?php echo $usuario->getLogin() ?></td>
          <td><?php echo $usuario->getRol() ?></td>
          <td class="logueado_usuario"><?php echo ($usuario->getLogueado()) ? 'Sí' : 'No' ?></td>
          <td class="bloqueado_usuario"><?php echo ($usuario->getBloqueado()) ? 'Sí' : 'No' ?></td>
          <td class="baja_usuario"><?php echo ($usuario->getDadoDeBaja()) ? 'Sí' : 'No' ?></td>
        </tr>
      <?php } if($contador==0) { ?>
          <tr>
            <td class="tabla_vacia" colspan="7">No hay contenido para mostrar</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <button class="boton_pestana" onclick="desplegar(event, 'admin_mensajes')">Gestionar mensajes</button>
  <div id="admin_mensajes" class="contenido_pestana">
    <ul class="instrucciones">
      <li>Puede marcar un mensaje como leído haciendo click en la celda correspondiente de la columna <i>Leído</i></li>
    </ul>
    <div id="filtro">
      <label>Filtrar mensajes:</label>
      <input type="radio" name="mensajes" value="no_leidos" id="no_leidos" checked="checked" />
      <label for="no_leidos">Solo no leídos</label>
      <input type="radio" name="mensajes" value="leidos" id="leidos" />
      <label for="leidos">Todos</label>
    </div>
    <table>
      <caption>MENSAJES NO LEÍDOS</caption>
      <thead>
        <tr>
          <th>Id</th>
          <th>Asunto</th>
          <th>Descripción</th>
          <th>Leído</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $mensajes = getMensajes();
          $contador = 0;
          if (count($mensajes) > 0)
            foreach ($mensajes as $mensaje)
              if(!$mensaje->getLeido()) { // por defecto solo los no leídos
                $contador++;
        ?>
        <tr>
          <td class="id_mensaje"><?php echo $mensaje->getId() ?></td>
          <td><?php echo $mensaje->getAsunto() ?></td>
          <td><?php echo $mensaje->getDescripcion() ?></td>
          <td class="leido"><?php echo ($mensaje->getLeido()) ? 'Sí' : 'No' ?></td>
        </tr>
        <?php } if ($contador == 0) { ?>
          <tr>
            <td colspan="4">No hay contenido para mostrar</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <form action="../scripts/logout.php">
    <input class="boton_logout" name="logout" type="submit" value="Cerrar sesión" />
  </form>
</body>

</html>

<?php
  require_once __DIR__.'/../dao/UsuarioDAO.php';

  use models\usuario\Usuario;

  session_start();
  if(!isset($_SESSION["usuario"]) || !isset($_POST["login"]) || !isset($_POST["contrasena"]) || !isset($_POST["rol"])){
  	header("Location: logout.php");
  	exit();
  }
  $usuarioActual = unserialize($_SESSION["usuario"]);
  if($usuarioActual->getRol() != "Administrador"){
  	header("Location: logout.php");
  	exit();
  }

  $nombre = filter_var($_POST['nombre'],FILTER_SANITIZE_STRING);
  $login = filter_var($_POST['login'],FILTER_SANITIZE_STRING);
  $contrasena = filter_var($_POST['contrasena'],FILTER_SANITIZE_STRING);
  $rol = filter_var($_POST['rol'],FILTER_SANITIZE_STRING);

  $usuario = new Usuario();
  if($nombre)
    $usuario->setNombre($nombre);
  $usuario->setLogin($login);
  $usuario->setContrasena($contrasena);
  $usuario->setRol($rol);
  $usuario->setLogueado(false);
  $usuario->setBloqueado(false);
  $usuario->setDadoDeBaja(false);

  $usuario->setId(saveUsuario($usuario));
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

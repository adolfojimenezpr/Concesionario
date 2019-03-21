<?php
  require_once __DIR__.'/../dao/UsuarioDAO.php';

  use models\usuario\Usuario;

  session_start();
  if(!isset($_POST['id']) || !isset($_POST['bloqueado']) || !isset($_SESSION["usuario"])){
    header("Location: logout.php");
  	exit();
  }
  $usuarioActual = unserialize($_SESSION["usuario"]);
  if($usuarioActual->getRol() != "Administrador"){
  	header("Location: logout.php");
  	exit();
  }

  $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
  $bloqueado = filter_var($_POST['bloqueado'],FILTER_SANITIZE_NUMBER_INT);

  bloquear(intval($id), intval($bloqueado));
?>

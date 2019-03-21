<?php
  require_once __DIR__.'/../dao/UsuarioDAO.php';

  use models\usuario\Usuario;

  session_start();
  if(!isset($_POST['id']) || !isset($_POST['dadoDeBaja']) || !isset($_SESSION["usuario"])){
    header("Location: logout.php");
  	exit();
  }
  $usuarioActual = unserialize($_SESSION["usuario"]);
  if($usuarioActual->getRol() != "Administrador"){
  	header("Location: logout.php");
  	exit();
  }

  $id = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);
  $dadoDeBaja = filter_var($_POST['dadoDeBaja'],FILTER_SANITIZE_NUMBER_INT);

  darDeBaja(intval($id), intval($dadoDeBaja));
?>

<?php
  require_once __DIR__.'/../dao/UsuarioDAO.php';

  use models\usuario\Usuario;

  session_start();

  if(isset($_SESSION["usuario"])){
    $id = unserialize($_SESSION["usuario"])->getId();
    loguear(intval($id), intval(false));
  }

  session_unset();
  session_destroy();

  header("Location: ../index.php");
?>

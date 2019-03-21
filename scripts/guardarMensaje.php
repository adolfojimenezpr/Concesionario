<?php
  require_once __DIR__.'/../dao/MensajeDAO.php';

  use models\mensaje\Mensaje;

  if(!isset($_POST['asunto']) || !isset($_POST['descripcion'])){
  	header("Location: ../index.html");
  	exit();
  }

  $asunto = filter_var($_POST['asunto'],FILTER_SANITIZE_STRING);
  $descripcion = filter_var($_POST['descripcion'],FILTER_SANITIZE_STRING);

  $mensaje = new Mensaje();
  if($asunto)
    $mensaje->setAsunto($asunto);
  $mensaje->setDescripcion($descripcion);

  saveMensaje($mensaje);
?>

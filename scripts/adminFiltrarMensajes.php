<?php
  require_once __DIR__.'/../dao/MensajeDAO.php';
  require_once __DIR__.'/../models/Usuario.php';

  use models\usuario\Usuario;
  use models\mensaje\Mensaje;

  session_start();
  if(!isset($_POST["leidos"]) || !isset($_SESSION["usuario"])){
  	header("Location: logout.php");
  	exit();
  }
  $usuarioActual = unserialize($_SESSION["usuario"]);
  if($usuarioActual->getRol() != "Administrador"){
  	header("Location: logout.php");
  	exit();
  }

  $leidos = filter_var($_POST['leidos'],FILTER_VALIDATE_BOOLEAN);

  $mensajes = getMensajes();
  $contador = 0;
  if (count($mensajes) > 0)
    foreach ($mensajes as $mensaje)
      if($leidos || !$mensaje->getLeido()) {
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

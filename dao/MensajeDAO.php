<?php
  require_once __DIR__.'/../models/Conexion.php';
  require_once __DIR__.'/../models/Mensaje.php';

  use models\mensaje\Mensaje;
  use models\conexion\Conexion;

  function saveMensaje($mensaje) {
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("insert into mensaje (Asunto, Descripcion, Leido) values(?,?,?)");
    $asunto = $mensaje->getAsunto();
    $descripcion = $mensaje->getDescripcion();
    $leido = $mensaje->getLeido();
    $stmt->bind_param("ssi", $asunto, $descripcion, intval($leido));
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }

  function setLeido($id){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("update mensaje set Leido = ? where Id = ?");
    $stmt->bind_param("ii", intval(true), intval($id));
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }

  function getMensajes(){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from mensaje order by Id desc");
    $stmt->execute();
  	$result = $stmt->get_result();
    $mensajes = [];
    while($row = $result->fetch_assoc()){
      $mensaje = new Mensaje();
      $mensaje->setId($row['Id']);
      $mensaje->setAsunto($row['Asunto']);
      $mensaje->setDescripcion($row['Descripcion']);
      $mensaje->setLeido(boolval($row['Leido']));
  		$mensajes[] = $mensaje;
  	}
    $stmt->close();
    $conn->close();
    return $mensajes;
  }
?>

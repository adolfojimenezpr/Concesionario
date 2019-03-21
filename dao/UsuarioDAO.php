<?php
  require_once __DIR__.'/../models/Conexion.php';
  require_once __DIR__.'/../models/Usuario.php';

  use models\usuario\Usuario;
  use models\conexion\Conexion;

  function getUsuarioId($id){
    $conn = Conexion::getConexion();
  	$stmt = $conn->prepare("select * from usuario where Id = ?");
    $stmt->bind_param("i", intval($id));
    $stmt->execute();
  	$result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
      $usuario = new Usuario();
      $usuario->setId($row['Id']);
      $usuario->setNombre($row['Nombre']);
      $usuario->setLogin($row['Login']);
      $usuario->setContrasena($row['Contrasena']);
      $usuario->setRol($row['Rol']);
      $usuario->setLogueado(boolval($row['Logueado']));
      $usuario->setBloqueado(boolval($row['Bloqueado']));
      $usuario->setDadoDeBaja(boolval($row['DadoDeBaja']));
    }
    else {
      $usuario = false;
    }
    $result->free_result();
  	$stmt->close();
  	$conn->close();
    return $usuario;
  }

  function getProveedores(){
    $conn = Conexion::getConexion();
  	$stmt = $conn->prepare("select * from usuario where Rol = 'Proveedor'");
    $stmt->execute();
  	$result = $stmt->get_result();
    $proveedores = [];
    while($row = $result->fetch_assoc()){
      $usuario = new Usuario();
      $usuario->setId($row['Id']);
      $usuario->setNombre($row['Nombre']);
      $usuario->setLogin($row['Login']);
      $usuario->setContrasena($row['Contrasena']);
      $usuario->setRol($row['Rol']);
      $usuario->setLogueado(boolval($row['Logueado']));
      $usuario->setBloqueado(boolval($row['Bloqueado']));
      $usuario->setDadoDeBaja(boolval($row['DadoDeBaja']));
      $proveedores[] = $usuario;
    }

    $result->free_result();
  	$stmt->close();
  	$conn->close();
    return $proveedores;
  }

  function getUsuarioLogin($login, $contrasena){
    $conn = Conexion::getConexion();
  	$stmt = $conn->prepare("select Id from usuario where Login = ? and Contrasena = ?");
    $stmt->bind_param("ss", $login, $contrasena);
    $stmt->execute();
  	$result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
      $usuario = getUsuarioId($row['Id']);
    }
    else {
      $usuario = false;
    }
    $result->free_result();
  	$stmt->close();
  	$conn->close();
    return $usuario;
  }

  function getProveedor($idProducto){
  	$conn = Conexion::getConexion();
  	$stmt = $conn->prepare("select Id_Proveedor from producto where Id = ?");
  	$stmt->bind_param("i", intval($idProducto));
  	$stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
      $proveedor = getUsuarioId($row['Id']);
    }
    else {
      $proveedor = false;
    }
    $result->free_result();
  	$stmt->close();
  	$conn->close();
    return $proveedor;
	}

  function saveUsuario($usuario){
    $conn = Conexion::getConexion();
  	$stmt = $conn->prepare("insert into usuario (Nombre, Login, Contrasena, Rol, Logueado, Bloqueado, DadoDeBaja) values(?,?,?,?,?,?,?)");

    $nombre = $usuario->getNombre();
    $login = $usuario->getLogin();
    $contrasena = $usuario->getContrasena();
    $rol = $usuario->getRol();
    $logueado = $usuario->getLogueado();
    $bloqueado = $usuario->getBloqueado();
    $dadoDeBaja = $usuario->getDadoDeBaja();

  	$stmt->bind_param("ssssiii", $nombre, $login, $contrasena, $rol, intval($logueado), intval($bloqueado), intval($dadoDeBaja));
  	$stmt->execute();
  	$stmt->close();
    $id = $conn->insert_id;
  	$conn->close();
    return $id;
  }

  function loguear($id, $logueado){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("update usuario set Logueado = ? where Id = ?");
    $stmt->bind_param("ii", intval($logueado), intval($id));
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }

  function bloquear($id, $bloqueado){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("update usuario set Bloqueado = ? where Id = ?");
    $stmt->bind_param("ii", intval($bloqueado), intval($id));
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }

  function darDeBaja($id, $dadoDeBaja){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("update usuario set DadoDeBaja = ? where Id = ?");
    $stmt->bind_param("ii", intval($dadoDeBaja), intval($id));
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }

  function getUsuarios(){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from usuario");
    $stmt->execute();
  	$result = $stmt->get_result();
    $usuarios = [];
    while($row = $result->fetch_assoc()){
  		$usuarios[] = getUsuarioId($row['Id']);
  	}
    $stmt->close();
    $conn->close();
    return $usuarios;
  }
?>

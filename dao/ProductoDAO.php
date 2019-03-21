<?php
  require_once __DIR__.'/../models/Conexion.php';
  require_once __DIR__.'/../models/Producto.php';

  use models\producto\Producto;
  use models\conexion\Conexion;

  function getProductos(){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from producto");
    $stmt->execute();
  	$result = $stmt->get_result();
  	$productos = [];
    while($row = $result->fetch_assoc()){
      $producto = new Producto();
      $producto->setId($row['Id']);
      $producto->setNombre($row['Nombre']);
      $producto->setPrecio($row['Precio']);
      $producto->setIdProveedor($row['Id_Proveedor']);
      $producto->setBaja(boolval($row['DadoDeBaja']));
  		$productos[] = $producto;
  	}

    $result->free_result();
    $stmt->close();
    $conn->close();

    return $productos;
  }

  function getProducto($idProducto) {
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from producto where Id = ?");
    $stmt->bind_param("i",intval($idProducto));
    $stmt->execute();
  	$result = $stmt->get_result();
    $producto = new Producto();
    if($row = $result->fetch_assoc()){
      $producto->setId($row['Id']);
      $producto->setNombre($row['Nombre']);
      $producto->setPrecio($row['Precio']);
      $producto->setIdProveedor($row['Id_Proveedor']);
      $producto->setBaja(boolval($row['DadoDeBaja']));
    }
    else
      $producto = false;

    $result->free_result();
    $stmt->close();
    $conn->close();

    return $producto;
  }

  function getProductosPorIdProveedor($idProveedor){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from producto where Id_Proveedor = ?");
    $stmt->bind_param("i",intval($idProveedor));
    $stmt->execute();
    $result = $stmt->get_result();
    $productos = [];
    while($row = $result->fetch_assoc()){
      $producto = new Producto();
      $producto->setId($row['Id']);
      $producto->setNombre($row['Nombre']);
      $producto->setPrecio($row['Precio']);
      $producto->setIdProveedor($row['Id_Proveedor']);
      $producto->setBaja(boolval($row['DadoDeBaja']));
      $productos[] = $producto;
    }

    $result->free_result();
    $stmt->close();
    $conn->close();

    return $productos;
  }

  function saveProducto($producto){
    $conn = Conexion::getConexion();
  	$stmt = $conn->prepare("insert into producto (Nombre, Precio, Id_Proveedor, DadoDeBaja) values(?,?,?,?)");

    $nombre = $producto->getNombre();
    $precio = $producto->getPrecio();
    $idProveedor = $producto->getIdProveedor();
    $baja = $producto->getBaja();

    $stmt->bind_param("sdii", $nombre, floatval($precio), intval($idProveedor), intval($baja));
  	$stmt->execute();
  	$stmt->close();
    $id = $conn->insert_id;
    $conn->close();
    return $id;

  }

  function actualizarProducto($producto){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("update producto set Nombre = ?, Precio = ?, Id_Proveedor = ?, DadoDeBaja = ? where Id = ?");

    $nombre = $producto->getNombre();
    $precio = $producto->getPrecio();
    $idProveedor = $producto->getIdProveedor();
    $id = $producto->getId();
    $baja = $producto->getBaja();

    $stmt->bind_param("sdiii", $nombre, $precio, $idProveedor, intval($baja), $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }
?>

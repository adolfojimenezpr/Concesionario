<?php
  require_once __DIR__.'/../models/Conexion.php';
  require_once __DIR__.'/../models/Producto_Pedido.php';
  require_once 'ProductoDAO.php';
  require_once 'PedidoDAO.php';

  use models\producto_pedido\Producto_Pedido;
  use models\producto\Producto;
  use models\pedido\Pedido;
  use models\conexion\Conexion;


  function getProducto_Pedidos($idPedido){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from producto_pedido where Id_Pedido = ?");

    $stmt->bind_param("i",intval($idPedido));

    $stmt->execute();
  	$result = $stmt->get_result();
  	$productoPedidos = [];
    while($row = $result->fetch_assoc()){
      $productoPedido = new Producto_Pedido();
      $productoPedido->setProducto(getProducto($row['Id_Producto']));
      $productoPedido->setIdPedido($row['Id_Pedido']);
      $productoPedido->setCantidad($row['Cantidad']);
      $productoPedido->setConfirmado($row['Confirmado']);
  		$productoPedidos[] = $productoPedido;
  	}

    $result->free_result();
    $stmt->close();
    $conn->close();

    return $productoPedidos;
  }

  function getProducto_Pedido($idProducto,$idPedido){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("select * from producto_pedido where Id_Producto = ? and Id_Pedido = ?");

    $stmt->bind_param("ii",intval($idProducto),intval($idPedido));

    $stmt->execute();
  	$result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
      $productoPedido = new Producto_Pedido();
      $productoPedido->setProducto(getProducto($row['Id_Producto']));
      $productoPedido->setIdPedido($row['Id_Pedido']);
      $productoPedido->setCantidad($row['Cantidad']);
      $productoPedido->setConfirmado($row['Confirmado']);
  	}
    else {
      $productoPedido = false;
    }

    $result->free_result();
    $stmt->close();
    $conn->close();

    return $productoPedido;
  }

  function saveProducto_Pedido($productoPedido){
    $conn = Conexion::getConexion();
  	$stmt = $conn->prepare("insert into producto_pedido (Id_Producto, Id_Pedido, Cantidad, Confirmado) values(?,?,?,?)");

    $idProducto = $productoPedido->getProducto()->getId();
    $idPedido = $productoPedido->getIdPedido();
    $cantidad = $productoPedido->getCantidad();
    $confirmado = $productoPedido->getConfirmado();

    $stmt->bind_param("iiii", intval($idProducto), intval($idPedido), intval($cantidad), intval($confirmado));
  	$stmt->execute();
  	$stmt->close();
  	$conn->close();
  }

  function actualizarProducto_Pedido($productoPedido){
    $existente = getProducto_Pedido($productoPedido->getProducto()->getId(),$productoPedido->getIdPedido());
    if(!$existente && $productoPedido->getCantidad() > 0)
      saveProducto_Pedido($productoPedido);
    else if($existente && $productoPedido->getCantidad() == 0)
      eliminarProducto_Pedido($productoPedido);
    else if($existente && $productoPedido->getCantidad() > 0) {

      $conn = Conexion::getConexion();
      $stmt = $conn->prepare("update producto_pedido set Cantidad = ?, Confirmado = ? where Id_Producto = ? and Id_Pedido = ?");

      $idProducto = $productoPedido->getProducto()->getId();
      $idPedido = $productoPedido->getIdPedido();
      $cantidad = $productoPedido->getCantidad();
      $confirmado = $productoPedido->getConfirmado();

      $stmt->bind_param("iiii", intval($cantidad), intval($confirmado), intval($idProducto), intval($idPedido));
      $stmt->execute();
      $stmt->close();
      $conn->close();
    }
  }

  function confirmarProducto_Pedido($idProducto,$idPedido){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("update producto_pedido set Confirmado = ? where Id_Producto = ? and Id_Pedido = ?");

    $estado = true;

    $stmt->bind_param("iii", intval($estado),$idProducto, $idPedido);
  	$stmt->execute();
  	$stmt->close();
  	$conn->close();

    // comprobar si hay que confirmar el pedido
    $productoPedidos = getProducto_Pedidos($idPedido);
    if (count($productoPedidos) > 0)
      foreach ($productoPedidos as $productoPedido)
        if(!$productoPedido->getConfirmado())
          return;
    confirmarPedido($idPedido);
  }

  function eliminarProducto_Pedido($productoPedido){
    $conn = Conexion::getConexion();
    $stmt = $conn->prepare("delete from producto_pedido where Id_Producto = ? and Id_Pedido = ?");

    $idProducto = $productoPedido->getProducto()->getId();
    $idPedido = $productoPedido->getIdPedido();

    $stmt->bind_param("ii", $idProducto, $idPedido);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // comprobar si hay que borrar el pedido
    $productoPedidos = getProducto_Pedidos($idPedido);
    if (count($productoPedidos) == 0)
      eliminarPedido($idPedido);
  }
  ?>

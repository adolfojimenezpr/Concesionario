<?php
require_once __DIR__.'/../models/Conexion.php';
require_once __DIR__.'/../models/Pedido.php';
require_once 'Producto_PedidoDAO.php';

use models\pedido\Pedido;
use models\conexion\Conexion;
use models\producto_pedido\Producto_Pedido;


function getPedidos($idUsuario){
 $conn = Conexion::getConexion();
 $stmt = $conn->prepare("select Id, Id_Usuario, Confirmado, Fecha from pedido where Id_Usuario = ?");

 $stmt->bind_param("i",intval($idUsuario));
 $stmt->execute();
 $result = $stmt->get_result();
 $pedidos = [];
 while($row = $result->fetch_assoc()){
   $pedido = new Pedido();
   $pedido->setId($row['Id']);
   $pedido->setIdUsuario($row['Id_Usuario']);
   $pedido->setConfirmado($row['Confirmado']);
   $pedido->setFecha($row['Fecha']);
   $pedido->setProductoPedidos(getProducto_Pedidos($row['Id']));

   $pedidos[] = $pedido;
 }

 $result->free_result();
 $stmt->close();
 $conn->close();

 return $pedidos;
}

function getConjuntoPedidos(){
 $conn = Conexion::getConexion();
 $stmt = $conn->prepare("select * from pedido");

 $stmt->execute();
 $result = $stmt->get_result();
 $pedidos = [];
 while($row = $result->fetch_assoc()){
   $pedido = new Pedido();
   $pedido->setId($row['Id']);
   $pedido->setIdUsuario($row['Id_Usuario']);
   $pedido->setConfirmado($row['Confirmado']);
   $pedido->setFecha($row['Fecha']);
   $pedido->setProductoPedidos(getProducto_Pedidos($row['Id']));

   $pedidos[] = $pedido;
 }

 $result->free_result();
 $stmt->close();
 $conn->close();

 return $pedidos;
}

function getPedido($idPedido) {

  $conn = Conexion::getConexion();
  $stmt = $conn->prepare("select Id, Id_Usuario, Confirmado, Fecha from pedido where Id = ?");

  $stmt->bind_param("i",intval($idPedido));
  $stmt->execute();
  $result = $stmt->get_result();
  $pedido = new Pedido();
  if($row = $result->fetch_assoc()){
    $pedido->setId($row['Id']);
    $pedido->setIdUsuario($row['Id_Usuario']);
    $pedido->setConfirmado($row['Confirmado']);
    $pedido->setFecha($row['Fecha']);
    $pedido->setProductoPedidos(getProducto_Pedidos($row['Id']));
  }

  $result->free_result();
  $stmt->close();
  $conn->close();

  return $pedido;

}

 function savePedido($pedido){
  $conn = Conexion::getConexion();
  $stmt = $conn->prepare("insert into pedido (Id_Usuario, Confirmado, Fecha) values(?, ?, ?)");

  $idUsuario = $pedido->getIdUsuario();
  $confirmado = $pedido->getConfirmado();
  $fecha = $pedido->getFecha();
  $stmt->bind_param("iis", intval($idUsuario), intval($confirmado), strval($fecha));
  $stmt->execute();
  $stmt->close();
  $id = $conn->insert_id;
  $conn->close();
  if(count($pedido->getProductoPedidos()) > 0)
    foreach($pedido->getProductoPedidos() as $productoPedido){
      $productoPedido->setIdPedido($id);
      saveProducto_Pedido($productoPedido);
    }
}

 function actualizarPedido($pedido) {
   foreach($pedido->getProductoPedidos() as $productoPedido){
     actualizarProducto_Pedido($productoPedido);
   }

 }

 function confirmarPedido($id){
  $conn = Conexion::getConexion();
  $stmt = $conn->prepare("update pedido set Confirmado = ? where Id = ?");

  $estado = true;

  $stmt->bind_param("ii", intval($estado), $id);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}

function eliminarPedido($id){
  $conn = Conexion::getConexion();
  $stmt = $conn->prepare("delete from pedido where Id = ?");

  $stmt->bind_param("i", intval($id));
  $stmt->execute();
  $stmt->close();
  $conn->close();
}
?>

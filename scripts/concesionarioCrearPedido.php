<?php
require_once __DIR__.'/../dao/ProductoDAO.php';
require_once __DIR__.'/../models/Producto_Pedido.php';
require_once __DIR__.'/../dao/PedidoDAO.php';
require_once __DIR__.'/../models/Usuario.php';

use models\usuario\Usuario;
use models\producto\Producto;
use models\pedido\Pedido;
use models\producto_pedido\Producto_Pedido;

session_start();
if(!isset($_SESSION["usuario"])){
  header("Location: logout.php");
  exit();
}
$usuarioActual = unserialize($_SESSION["usuario"]);
$myId = $usuarioActual->getId();
if($usuarioActual->getRol() != "Concesionario"){
  header("Location: logout.php");
  exit();
}


$pedido = new Pedido();
$productoPedidos = [];

if(count(getProductos())>0)
foreach(getProductos() as $producto) {
  $string = "cantidad".$producto->getId();
  $cantidad = filter_var($_POST[$string],FILTER_SANITIZE_NUMBER_INT);
    if($cantidad > 0) {
      $productoPedido = new Producto_Pedido();
      $productoPedido->setCantidad($cantidad);
      $productoPedido->setProducto($producto);
      $productoPedido->setConfirmado(false);
      $productoPedidos[] = $productoPedido;
    }
}
if(count($productoPedidos) > 0) {

  $pedido->setIdUsuario($myId);
  $pedido->setConfirmado(false);
  $pedido->setProductoPedidos($productoPedidos);
  $pedido->setFecha(date("Y-m-d"));

  savePedido($pedido);

  echo "Pedido creado";
}
else
  echo "El pedido no contiene ningún producto";
?>

<?php
require_once __DIR__.'/../dao/ProductoDAO.php';
require_once __DIR__.'/../dao/PedidoDAO.php';
require_once __DIR__.'/../dao/Producto_PedidoDAO.php';
require_once __DIR__.'/../models/Pedido.php';
require_once __DIR__.'/../models/Usuario.php';

use models\usuario\Usuario;
use models\producto\Producto;
use models\pedido\Pedido;
use models\producto_pedido\Producto_Pedido;

session_start();
if(!isset($_SESSION["usuario"]) || !isset($_POST["id"])){
  header("Location: logout.php");
  exit();
}
$usuarioActual = unserialize($_SESSION["usuario"]);
$myId = $usuarioActual->getId();
if($usuarioActual->getRol() != "Concesionario"){
  header("Location: logout.php");
  exit();
}


$id = filter_var($_POST["id"],FILTER_SANITIZE_NUMBER_INT);

$productoPedidos = [];
if(count(getProductos())>0)
foreach (getProductos() as $producto) {
  $string = "cantidad".$producto->getId();
  $cantidad = filter_var($_POST[$string],FILTER_SANITIZE_NUMBER_INT);
  $productoPedido = new Producto_Pedido();
  $productoPedido->setCantidad($cantidad);
  $productoPedido->setProducto($producto);
  $productoPedido->setIdPedido($id);
  $productoPedido->setConfirmado(false);
  $productoPedidos[] = $productoPedido;
}
$pedido = new Pedido();
$pedido->setProductoPedidos($productoPedidos);

actualizarPedido($pedido);
?>

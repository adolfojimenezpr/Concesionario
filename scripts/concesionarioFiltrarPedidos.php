<?php
require_once __DIR__.'/../dao/ProductoDAO.php';
require_once __DIR__.'/../models/Producto_Pedido.php';
require_once __DIR__.'/../models/Pedido.php';
require_once __DIR__.'/../dao/UsuarioDAO.php';
require_once __DIR__.'/../dao/PedidoDAO.php';

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

$myId = $usuarioActual->getId();
if(isset($_POST['fecha']))
  $fecha = filter_var($_POST['fecha'], FILTER_SANITIZE_STRING);
if(isset($_POST['proveedor']))
  $proveedor = filter_var($_POST['proveedor'],FILTER_SANITIZE_STRING);
if(isset($_POST['producto']))
  $producto = filter_var($_POST['producto'],FILTER_SANITIZE_STRING);

$pedidosSinFiltrar = getPedidos($myId);

$pedidos = [];
if(count($pedidosSinFiltrar)>0)
foreach ($pedidosSinFiltrar as $p) {

  // pedido más reciente que la fecha
  if(!empty($fecha) && $fecha > $p->getFecha())
    continue;

  $cumple = false;
  if(count($p->getProductoPedidos())>0)
  foreach ($p->getProductoPedidos() as $pp){
    if((empty($proveedor) || $proveedor == $pp->getProducto()->getIdProveedor()) && (empty($producto) || $producto == $pp->getProducto()->getId())){
      $cumple = true;
      break;
    }
  }

  if($cumple)
    $pedidos[] = $p;
}


if(count($pedidos)>0)
foreach($pedidos as $pedido)
{
  echo "<tr>";
  echo "<td rowspan='".count($pedido->getProductoPedidos())."'>".$pedido->getId()."</td>";
  echo "<td rowspan='".count($pedido->getProductoPedidos())."'>".date("d/m/Y", strtotime($pedido->getFecha()))."</td>";
  echo "<td rowspan='".count($pedido->getProductoPedidos())."'>".(($pedido->getConfirmado()) ? "Confirmado" : "Sin confirmar")."</td>";

  $productoPedidos = $pedido->getProductoPedidos();
  if(count($productoPedidos)>0){
    $idUltimo = end($productoPedidos)->getProducto()->getId();
    foreach($productoPedidos as $productoPedido) {

      echo "<td>".$productoPedido->getProducto()->getNombre()."</td>";

      echo "<td>".$productoPedido->getProducto()->getPrecio()."</td>";

      echo "<td>".$productoPedido->getCantidad()."</td>";

      echo "<td>".getUsuarioId($productoPedido->getProducto()->getIdProveedor())->getNombre()."</td>";

      if($productoPedido->getProducto()->getId() !== $idUltimo)
        echo "</tr><tr>";
    }
  }

  echo "</tr>";

}

?>

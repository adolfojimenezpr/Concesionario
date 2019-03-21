<?php

require_once __DIR__.'/../dao/Producto_PedidoDAO.php';
require_once __DIR__.'/../dao/UsuarioDAO.php';

  use models\usuario\Usuario;
  use models\producto_pedido\Producto_Pedido;

  session_start();

  if(!isset($_POST['idProducto']) || !isset($_POST['idPedido']) || !isset($_SESSION["usuario"])){
    header("Location: logout.php");
    exit();
  }
  $usuarioActual = unserialize($_SESSION["usuario"]);
  if($usuarioActual->getRol() != "Proveedor"){
    header("Location: logout.php");
    exit();
  }

  $idProducto = filter_var($_POST['idProducto'],FILTER_SANITIZE_NUMBER_INT);
  $idPedido = filter_var($_POST['idPedido'],FILTER_SANITIZE_NUMBER_INT);



  confirmarProducto_Pedido($idProducto,$idPedido);


?>

<?php
  require_once __DIR__.'/../dao/UsuarioDAO.php';
  require_once __DIR__.'/../dao/ProductoDAO.php';
  require_once __DIR__.'/../dao/PedidoDAO.php';
  require_once __DIR__.'/../dao/Producto_PedidoDAO.php';

  use models\usuario\Usuario;
  use models\producto\Producto;
  use models\pedido\Pedido;
  use models\producto_pedido\Producto_Pedido;

  session_start();
  if(!isset($_POST["confirmados"]) || !isset($_SESSION["usuario"])){
  	header("Location: logout.php");
  	exit();
  }
  $usuarioActual = unserialize($_SESSION["usuario"]);
  if($usuarioActual->getRol() != "Proveedor"){
    header("Location: logout.php");
    exit();
  }
  $idUsuario = $usuarioActual->getId();
  $confirmados = filter_var($_POST['confirmados'],FILTER_VALIDATE_BOOLEAN);
  $pedidos = getConjuntoPedidos();
  //echo $pedidos[0]->getConfirmado();
  if(count($pedidos)>0)
  foreach($pedidos as $ped)
  {
    $idPedido = $ped->getId();
    $producto_pedido = $ped->getProductoPedidos();
    $idConcesionario = $ped->getIdUsuario();
    $usuarioConcesionario = getUsuarioId($idConcesionario);
    $nombreConcesionario = $usuarioConcesionario->getNombre();
    if(count($producto_pedido)>0)
    foreach($producto_pedido as $prp){
      $prFinal = $prp->getProducto();
      if($prFinal->getIdProveedor() == $idUsuario)
      {
        if($confirmados || !$prp->getConfirmado()){
          echo "<tr>";
            echo "<td class = 'idPedidoConfirmado'>".$idPedido."</td>";
            echo "<td>".$nombreConcesionario."</td>";
            echo "<td class = 'idProductoConfirmado'>".$prFinal->getId()."</td>";
            echo "<td>".$prFinal->getNombre()."</td>";
            echo "<td>".$prFinal->getPrecio()."</td>";
            echo "<td>".$prp->getCantidad()."</td>";
            echo "<td><button class='botonConfirmar'".(($prp->getConfirmado()) ? " disabled='disabled'" : "").">Confirmar</button></td>";
          echo "</tr>";
        }
      }
    }
  }
?>

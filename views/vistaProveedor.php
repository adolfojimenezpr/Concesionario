<?php

  require_once __DIR__.'/../dao/UsuarioDAO.php';
  require_once __DIR__.'/../dao/ProductoDAO.php';
  require_once __DIR__.'/../dao/PedidoDAO.php';
  require_once __DIR__.'/../dao/Producto_PedidoDAO.php';



  session_start();

  if(isset($_SESSION["usuario"])){
    $usuarioActual = unserialize($_SESSION["usuario"]);
    $idUsuario = $usuarioActual->getId();
  }
  if(!isset($_SESSION["usuario"]) || $usuarioActual->getRol() != "Proveedor") {
    header("Location: ../scripts/logout.php");
    exit();
  }


  $productosExistentes = getProductosPorIdProveedor($idUsuario);


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Proveedor</title>
  <link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
</head>

<body>
  <button class="boton_pestana" onclick="desplegar(event, 'p1')">Actualizar lista de productos</button>
  <div id="p1" class="contenido_pestana">
  <br>
	<form method="post" id="uploadform" enctype="multipart/form-data">
		<input id="xml" type="file" name="directorio">
		<input type="submit" name="submit" value="Subir">
	</form>
  <br>
  </div>


  <button class="boton_pestana" onclick="desplegar(event, 'prov_ver_confirmarProds')">Ver y confirmar lista de pedidos</button>
  <div id="prov_ver_confirmarProds" class="contenido_pestana">

    <div id="filtro">
      <label>Filtrar productos pedidos:</label>
      <input type="radio" name="producto_pedido" value="no_confirmados" id="no_confirmados" checked="checked" />
      <label for="no_confirmados">Solo no confirmados</label>
      <input type="radio" name="producto_pedido" value="confirmados" id="confirmados" />
      <label for="confirmados">Todos</label>
    </div>

	<table>
	  <caption>PRODUCTOS PEDIDOS SIN CONFIRMAR</caption>
    <thead>
      <tr>
        <th colspan="2">Pedido</th>
        <th colspan="4">Producto</th>
        <th rowspan="2">Confirmar</th>
      </tr>
  	  <tr>
  	    <th>ID</th>
        <th>Concesionario</th>
  	    <th>ID</th>
  	    <th>Nombre</th>
  	    <th>Precio</th>
  	    <th>Cantidad</th>
  	  </tr>
    </thead>

    <tbody>
    <?php

    //echo "<br><br>ID USUARIO:".$idUsuario;

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

        if($prFinal->getIdProveedor() == $idUsuario && !$prp->getConfirmado())
        {
          echo "<tr>";
            echo "<td class = 'idPedidoConfirmado'>".$idPedido."</td>";
            echo "<td>".$nombreConcesionario."</td>";
            echo "<td class = 'idProductoConfirmado'>".$prFinal->getId()."</td>";
            echo "<td>".$prFinal->getNombre()."</td>";
            echo "<td>".$prFinal->getPrecio()."</td>";
            echo "<td>".$prp->getCantidad()."</td>";
            echo "<td><button class='botonConfirmar'>Confirmar</button></td>";
          echo "</tr>";

        }

      }



    }


    ?>

    </tbody>
		</table>



  </div>

  <button class="boton_pestana" onclick="desplegar(event, 'p3')">Consultar listado de productos subidos</button>
  <div id="p3" class="contenido_pestana">
	<table>
    <caption>Listado de productos en venta</caption>
	  <tr>
	    <th>ID Producto</th>
	    <th>Nombre</th>
      <th>Precio/unidad</th>

	  </tr>
    <?php

    if(count($productosExistentes)>0)
    foreach($productosExistentes as $productosEx)
    {
      if(!$productosEx->getBaja()){
    ?>

    <tr>
      <td><?php echo $productosEx->getId();?></td>
      <td><?php echo $productosEx->getNombre();?></td>
      <td><?php echo $productosEx->getPrecio();?></td>
    </tr>

    <?php
      }
    }



    ?>
    </table>
  </div>

  <form action="../scripts/logout.php">
    <input class="boton_logout" name="logout" type="submit" value="Cerrar sesión" />
  </form>
</body>

</html>

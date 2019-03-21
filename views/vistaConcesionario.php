<?php

require_once __DIR__.'/../dao/UsuarioDAO.php';
require_once __DIR__.'/../dao/ProductoDAO.php';
require_once __DIR__.'/../dao/PedidoDAO.php';
require_once __DIR__.'/../dao/Producto_PedidoDAO.php';

session_start();
if(isset($_SESSION["usuario"])){
  $usuarioActual = unserialize($_SESSION["usuario"]);
  $myId = $usuarioActual->getId();
}
if(!isset($_SESSION["usuario"]) || $usuarioActual->getRol() != "Concesionario") {
  header("Location: ../scripts/logout.php");
  exit();
}

$pedidos = getPedidos($myId);

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Concesionario</title>
  <link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="../js/script.js"></script>
</head>

<body>
  <button class="boton_pestana" onclick="desplegar(event, 'p1')">Crear pedido</button>
  <div id="p1" class="contenido_pestana">

  <form class="crearPedidoConcesionario">

  <table>
    <caption>
      Productos del nuevo pedido
    </caption>

    <tr>
      <th>Nombre</th>
      <th>Precio</th>
      <th>Proveedor</th>
      <th>Cantidad</th>
    </tr>

      <?php
        if(count(getProductos())>0)
        foreach(getProductos() as $producto) {
          if(!$producto->getBaja()) {
      ?>
        <tr>
          <td><?php echo $producto->getNombre()?></td>
          <td><?php echo $producto->getPrecio()?></td>
          <td><?php echo getUsuarioId($producto->getIdProveedor())->getNombre()?></td>
          <td>
            <input type="number" min="0" name="cantidad<?php echo $producto->getId(); ?>" value = "0" />
          </td>
        </tr>
      <?php
        }
      }
      ?>
    </table>
    <button id="botonCrearPedido">Crear pedido</button>
  </form>
  </div>

  <button class="boton_pestana" onclick="desplegar(event, 'p2')">Modificar pedido</button>
  <div id="p2" class="contenido_pestana">
    <div class="formularioModificar">
      <?php
        if(count($pedidos)>0)
        foreach($pedidos as $pedido) {
          if(!$pedido->getConfirmado()) {
      ?>

        <form class = "modificarPedidoConcesionario" id = "f<?php echo $pedido->getId();?>">
            <table>
              <caption>
                Productos del pedido <?php echo $pedido->getId();?>
              </caption>

              <tr>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Proveedor</th>
                <th>Cantidad</th>
              </tr>

              <?php
                if(count(getProductos())>0)
                foreach(getProductos() as $producto)
                  if(!$producto->getBaja()){
              ?>
                  <tr>
                    <td><?php echo $producto->getNombre()?></td>
                    <td><?php echo $producto->getPrecio()?></td>
                    <td><?php echo getUsuarioId($producto->getIdProveedor())->getNombre()?></td>
                    <td>
                  <input type="number" min="0" name="cantidad<?php echo $producto->getId(); ?>" value= "<?php
                      $encontrado = false;
                      $confirmado = false;
                      if(count($pedido->getProductoPedidos())>0)
                      foreach($pedido->getProductoPedidos() as $productoPedido) {
                        if($productoPedido->getIdPedido() == $pedido->getId() && $productoPedido->getProducto()->getId() == $producto->getId()) {
                          echo $productoPedido->getCantidad();
                          $confirmado = $productoPedido->getConfirmado();
                          $encontrado = true;
                          break;
                        }
                      }
                      if(!$encontrado) {
                        echo 0;
                      }
                    ?>" <?php if($confirmado) echo "disabled = 'disabled'"; ?> />
                    </td>
                    </tr>

              <?php
                }
              ?>
            </table>
        <input class = "botonModificarPedido" id = "botonModificarPedido<?php echo $pedido->getId();?>" type="submit" value="Modificar"/>
        </form>
      <?php
          }
        }
      ?>
    </div>
  </div>

  <button class="boton_pestana" onclick="desplegar(event, 'p3')">Filtrar pedidos</button>
  <div id="p3" class="contenido_pestana">

    <form class="filtrarConcesionario">
      <div>
        <label>A partir de la fecha:</label>
        <input type="date" name="fecha" />
      </div>
      <div>
        <label>Con productos del proveedor:</label>
        <select name="proveedor">
          <option value=""></option>
          <?php
          if(count(getProveedores())>0)
          foreach (getProveedores() as $proveedor) {
            echo "<option value='" .$proveedor->getId() ."'>" .$proveedor->getNombre() ."</option>";
          }
          ?>
        </select>
      </div>
      <div>
        <label>Con el producto:</label>
        <select name="producto">
          <option value=""></option>
          <?php
          if(count(getProductos())>0)
          foreach (getProductos() as $producto)
            if(!$producto->getBaja()) {
            echo "<option value=\"" .$producto->getId() ."\">" .$producto->getNombre() ."</option>";
          }
          ?>
        </select>
      </div>

      <input type="submit" value="Filtrar" />
    </form>

    <table>
      <thead>
        <tr>
          <th rowspan="2">Id</th>
          <th rowspan="2">Fecha</th>
          <th rowspan="2">Estado</th>
          <th colspan="4">Productos</th>
        </tr>
        <tr>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Proveedor</th>
        </tr>
      </thead>
        <tbody>
        <?php

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

        } ?>
      </tbody>
    </table>
  </div>

  <form action="../scripts/logout.php">
    <input class="boton_logout" name="logout" type="submit" value="Cerrar sesión" />
  </form>

</body>

</html>

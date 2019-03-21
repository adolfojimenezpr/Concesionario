<?php
  require_once __DIR__.'/../dao/ProductoDAO.php';
  require_once __DIR__.'/../models/Producto.php';
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
  if($usuarioActual->getRol() != "Proveedor"){
    header("Location: logout.php");
  	exit();
  }
  $dir_to_search = $_FILES['directorio']['tmp_name'];
  if (file_exists($dir_to_search)) {
      $xml = simplexml_load_file($dir_to_search);
  ?>

      <table>
        <caption>
          Elementos actualizados en la base de datos
        </caption>
      <tr>
        <th>ID Producto</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>ID Proveedor</th>
        <th>Dado de baja</th>
      </tr>
  <?php
  if(count($xml)>0)
      foreach($xml as $products)
      {
        $id = $products['id'];
        $productoActualizar = new Producto();
        $productoActualizar->setNombre(filter_var($products->children()->nombre,FILTER_SANITIZE_STRING));
        $productoActualizar->setPrecio(filter_var($products->children()->precio,FILTER_VALIDATE_FLOAT));
        $productoActualizar->setIdProveedor(filter_var($usuarioActual->getId(),FILTER_VALIDATE_INT));
        $productoActualizar->setBaja(filter_var($products->children()->baja,FILTER_VALIDATE_INT));
        if($products['id'] == "")
        {
          //insertar
          $id = saveProducto($productoActualizar);
        }else{
          if(!getProducto($products['id']) || $usuarioActual->getId() != getProducto($products['id'])->getIdProveedor())
            continue;
          //actualizar
          $productoActualizar->setId($products['id']);
          actualizarProducto($productoActualizar);
        }
        ?>
        <tr>
          <td>
            <?php echo $id?>
          </td>
          <td>
            <?php echo $products->children()->nombre?>
          </td>
          <td>
            <?php echo $products->children()->precio?>
          </td>
          <td>
            <?php echo $usuarioActual->getId()?>
          </td>
          <td>
            <?php echo intval($products->children()->baja) ? 'Sí' : 'No'?>
          </td>
        </tr>
        <?php
      }
      ?></table><?php
    }
?>

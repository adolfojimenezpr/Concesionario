<?php
namespace models\producto_pedido;

class Producto_Pedido{
  private $producto;
  private $idPedido;
	private $cantidad;
	private $confirmado;


    public function getProducto()
    {
        return $this->producto;
    }

    public function setProducto($producto)
    {
        $this->producto = $producto;

        return $this;
    }

    public function getIdPedido()
    {
        return $this->idPedido;
    }

    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getConfirmado()
    {
        return $this->confirmado;
    }

    public function setConfirmado($confirmado)
    {
        $this->confirmado = $confirmado;

        return $this;
    }


}
?>

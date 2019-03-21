<?php
namespace models\pedido;

class Pedido{
  private $id;
	private $idUsuario;
	private $confirmado;
  private $fecha;
  private $productoPedidos;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

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

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }
    public function getProductoPedidos()
    {
        return $this->productoPedidos;
    }

    public function setProductoPedidos($productoPedidos)
    {
        $this->productoPedidos = $productoPedidos;

        return $this;
    }


}
?>

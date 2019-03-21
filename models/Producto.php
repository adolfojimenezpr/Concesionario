<?php
namespace models\producto;

  class Producto{
    private $id;
    private $nombre;
    private $precio;
    private $idProveedor;
    private $baja;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    public function getIdProveedor()
    {
        return $this->idProveedor;
    }

    public function setIdProveedor($idProveedor)
    {
        $this->idProveedor = $idProveedor;

        return $this;
    }

    public function getBaja()
    {
        return $this->baja;
    }

    public function setBaja($baja)
    {
        $this->baja = $baja;

        return $this;
    }

}


?>

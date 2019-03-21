<?php
namespace models\mensaje;

class Mensaje {
  private $id;
  private $asunto;
  private $descripcion;
  private $leido;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getAsunto()
    {
        return $this->asunto;
    }

    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getLeido()
    {
        return $this->leido;
    }

    public function setLeido($leido)
    {
        $this->leido = $leido;

        return $this;
    }

}
?>

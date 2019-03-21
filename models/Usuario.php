<?php
namespace models\usuario;

class Usuario{
	private $id;
	private $nombre;
	private $login;
	private $contrasena;
	private $rol;
	private $logueado;
	private $bloqueado;
	private $dadoDeBaja;

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

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getContrasena()
    {
        return $this->contrasena;
    }

    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;

        return $this;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;

        return $this;
    }

    public function getLogueado()
    {
        return $this->logueado;
    }

    public function setLogueado($logueado)
    {
        $this->logueado = $logueado;

        return $this;
    }

    public function getBloqueado()
    {
        return $this->bloqueado;
    }

    public function setBloqueado($bloqueado)
    {
        $this->bloqueado = $bloqueado;

        return $this;
    }

    public function getDadoDeBaja()
    {
        return $this->dadoDeBaja;
    }

    public function setDadoDeBaja($dadoDeBaja)
    {
        $this->dadoDeBaja = $dadoDeBaja;

        return $this;
    }

}
?>

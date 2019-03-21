<?php
require_once __DIR__.'/../dao/UsuarioDAO.php';

use models\usuario\Usuario;

session_start();
session_unset();
session_destroy();

session_start();

if(!isset($_POST["usuario"]) || !isset($_POST["password"]) || empty($_POST["usuario"]) || empty($_POST["password"])){
	$_SESSION["mensajeIndex"] = "Rellene usuario y contraseña.";
	header("Location: ../index.php");
	exit();
}

$login = filter_var($_POST["usuario"], FILTER_SANITIZE_STRING);
$contrasena = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

$usuario = getUsuarioLogin($login, $contrasena);
if($usuario && !$usuario->getBloqueado() && !$usuario->getDadoDeBaja() && ($usuario->getRol() == "Administrador" || !$usuario->getLogueado())){
	session_start();
	session_regenerate_id();
	loguear($usuario->getId(), intval(true));
	$_SESSION["usuario"] = serialize($usuario);
	switch($usuario->getRol()){
		case "Proveedor":
			header("Location: ../views/vistaProveedor.php");
			break;
		case "Concesionario":
			header("Location: ../views/vistaConcesionario.php");
			break;
		case "Administrador":
			header("Location: ../views/vistaAdministrador.php");
			break;
	}
}else{
	if($usuario){
		if($usuario->getBloqueado())
			$_SESSION["mensajeIndex"] = "Este usuario ha sido bloqueado por el administrador.";
		else if($usuario->getDadoDeBaja())
			$_SESSION["mensajeIndex"] = "Este usuario ha sido dado de baja.";
		else if($usuario->getLogueado())
			$_SESSION["mensajeIndex"] = "Este usuario ya tiene una sesión activa. Envíe un mensaje al administrador.";
	}else
		$_SESSION["mensajeIndex"] = "Usuario y contraseña incorrectos.";
	header("Location: ../index.php");
}
?>

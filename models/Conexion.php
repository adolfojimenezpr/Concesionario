<?php
namespace models\conexion;

use mysqli;

define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "");
define("BD", "concesionario");

class Conexion {
  static function getConexion(){
  	$conn = new mysqli(HOST, USER, PASSWORD, BD);
  	if($conn->connect_errno){
  		die("Error de conexión de la base de datos");
  	}else{
  		$conn->set_charset("utf-8");
  	}
  	return $conn;
  }
}
?>

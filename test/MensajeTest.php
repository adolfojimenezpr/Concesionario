<?php
  require_once __DIR__.'/../dao/MensajeDAO.php';
  require_once 'dbunit-4.0.0.phar';

  use PHPUnit\Framework\TestCase;
  use PHPUnit\DbUnit\TestCaseTrait;
  use models\mensaje\Mensaje;

  class MensajeTest extends TestCase {
    use TestCaseTrait;

    private $conn;

    public function getConnection(){
      if($this->conn == null){
        $pdo = new PDO("mysql:hostname=localhost;dbname=concesionario","root","");
        $this->conn = $this->createDefaultDBConnection($pdo, "concesionario");
      }
      return $this->conn;
    }

    public function getDataSet(){
      return $this->createXMLDataSet("datosInicialesMensaje.xml");
    }

    public function testObtenerTodos(){
      $registros = $this->getConnection()->getRowCount("mensaje");
      $m = getMensajes();
      // comprobar que devuelve tantos mensajes como filas
      $this->assertEquals(count($m),$registros);
    }

    public function testInsertar(){
      $m = new Mensaje();
      $m->setAsunto("MsjD");
      $m->setDescripcion("Mensaje D");

      $regsInicio = $this->getConnection()->getRowCount("mensaje");
      saveMensaje($m);
      $regsFinal = $this->getConnection()->getRowCount("mensaje");
      // comprobar que hay una fila más
      $this->assertEquals($regsFinal-$regsInicio,1);

      $dataSet = $this->getConnection()->createDataSet(["mensaje"]);
      $expectedDataSet = $this->createFlatXmlDataSet("datosInsertMensaje.xml");
      // comprobar que coinciden los datos actualizados con los esperados
      $this->assertDataSetsEqual($expectedDataSet,$dataSet);
    }

    public function testActualizarLeido(){
      setLeido(1);
      $dataSet = $this->getConnection()->createDataSet(["mensaje"]);
      $expectedDataSet = $this->createFlatXmlDataSet("datosUpdateMensaje.xml");
      // comprobar que coinciden los datos actualizados con los esperados
      $this->assertDataSetsEqual($expectedDataSet,$dataSet);
    }
  }
?>

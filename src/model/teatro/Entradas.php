/*
 * El código de la práctica es el resultado exclusivamente de sus miembros, Raul Cobos Hernando, Alicia Rodriguez Torija y Sergio Rodríguez Gundin
 * Fecha: 27/01/2015
 * AUTORES: Raul Cobos Hernando, Alicia Rodríguez Torija y Sergio Rodríguez Gundin
 */

<?php

include_once dirname(__DIR__) . '/DAOContents.php';

class Entradas {

    private static $instance = NULL;
    private $conexion;
    private $enlace;
    private $datos;
    public $dia;

    //constructor privado (SINGLETON PATTERN)
    private function entradas() {

        $m = getDate();
        $this->dia = $m['year'] . "-" . $m['mon'] . "-" . $m['mday'];

        $hostName = DAOContents::getInstance()->hostName;
        $user = DAOContents::getInstance()->dbUser;
        $password = DAOContents::getInstance()->dbPassword;
        $databaseName = DAOContents::getInstance()->dbName;

        /* Intentamos establecer una conexi�n persistente con el servidor. */
        $this->conexion = new MongoClient();
        $this->enlace = $this->conexion->selectDB(DAOContents::getInstance()->dbName);
    }

    //Destructor
    function _entradas() {
        /* Liberamos la conexion persistente con el servidor. */
    }

    // Devuelve la instanacia de la clase (SINGLETON PATTERN)
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Entradas();
        }

        return self::$instance;
    }

    public function getEntradasCompradas() {

        //$query = [array('id_usuario'=>$_SESSION['id']), array("id_teatro","sesion","nume_fila","nume_asiento","fecha","DNI_usuario")];
        //$query = "SELECT * FROM entradas WHERE id_usuario='" . $_SESSION['id'] . "'";
        //entradas es la coleccion de la base de datos creada "pruebas" 

        $coleccion = $this->enlace->selectCollection('entradas');
        $this->datos = $coleccion->find(array("id_usuario" => $_SESSION['id']), array("id_teatro", "sesion", "fila", "asiento", "dia", "DNI_usuario"));
        $this->datos->count();

        if ($this->datos->count() <= 0) {
            return false;
        }

        return $this->datos;
    }
    
    public function compradas($Id, $sesion, $dia, $a, $f) {

        //$query = "SELECT * FROM entradas WHERE Id_teatro='" . $Id . "' AND sesion='" . $sesion . "' AND fila=" . $f . " AND asiento=" . $a . " AND dia='" . $dia . "'";
        //entradas es la coleccion donde consultar las compradas
        $coleccion = $this->enlace->selectCollection('entradas');
        $this->datos = $coleccion->find(array("id_teatro" => $Id, "sesion" => $sesion, "fila" => (string) $f, "asiento" => (string) $a, "dia" => (string) $dia));

        if ($this->datos->count() <= 0) {
            return false;
        }

        return $this->datos;
    }

    public function compradas_usuario($Id, $sesion, $dia, $a, $f) {

        //$query = [array('Id_teatro' => $Id, 'sesion' => $sesion, 'fila' => $f, 'asiento' => $a, 'dia' => $dia, 'Id_usuario' => $_SESSION['id']),
        //array("id_teatro","sesion","nume_fila","nume_asiento","fecha","DNI_usuario")];
        //$query = "SELECT * FROM entradas WHERE Id_teatro='" . $Id . "' AND sesion='" . $sesion . "' AND fila=" . $f . " AND asiento=" . $a . " AND dia='" . $dia . "' AND id_usuario='" . $_SESSION['id'] . "'";

        $coleccion = $this->enlace->selectCollection("entradas");
        $this->datos = $coleccion->find(array("id_teatro" => $Id, "sesion" => $sesion, "fila" => (string)$f, "asiento" => (string)$a, "dia" => (string)$dia, "id_usuario" => $_SESSION['id']));

        if ($this->datos->count() <= 0) {
            return false;
        }

        return $this->datos;
    }

    public function compradas_otros($Id, $sesion, $dia, $a, $f) {

        //$query = [array('Id_teatro' => $Id, 'sesion' => $sesion, 'fila' => $f, 'asiento' => $a, 'dia' => $dia, 'Id_usuario' => $_SESSION['id']),
        //array("id_teatro","sesion","nume_fila","nume_asiento","fecha","DNI_usuario")];
        //$query = "SELECT * FROM entradas WHERE Id_teatro='" . $Id . "' AND sesion='" . $sesion . "' AND fila=" . $f . " AND asiento=" . $a . " AND dia='" . $dia . "' AND id_usuario!='" . $_SESSION['id'] . "'";

        $coleccion = $this->enlace->selectCollection('entradas');
        $this->datos = $coleccion->find(array("id_teatro" => $Id, "sesion" => $sesion, "fila" => (string)$f, "asiento" => (string)$a, "dia" => (string)$dia, "id_usuario" => array('$ne' => $_SESSION['id'])));

        if ($this->datos->count() <= 0) {
            return false;
        }

        return $this->datos;
    }

    // Funcion que almacena/elimina en base de datos la reserva de entradas
    public function exec_comprar($Id, $sesion, $fila, $asiento, $dia) {

        //Ejecuta un insert o un delete de la tabla entradas teniendo en cuenta si el asiento estaba seleccionado o no(variable $accion). Actualiza la pagina.
        //$documento = "INSERT INTO entradas (`id_teatro`, `sesion`, `fila`, `asiento`, `dia`, `id_usuario`) 
        //VALUES ('" . $Id . "', '" . $sesion . "', '" . $fila . "', '" . $asiento . "', '" . $dia . "', '" . $_SESSION['id'] . "')";

        $coleccion = $this->enlace->selectCollection('entradas');
        $this->datos = $coleccion->insert(array("id_teatro" => $Id, "sesion" => $sesion, "fila" => $fila, "asiento" => $asiento, "dia" => $dia, "id_usuario" => $_SESSION['id']));

        if (!$this->datos) {
            return false;
        }

        return true;
    }

    public function descomprar($Id, $sesion, $fila, $asiento, $dia) {


        //Ejecuta un insert o un delete de la tabla entradas teniendo en cuenta si el asiento estaba seleccionado o no(variable $accion). Actualiza la pagina.
        //$query = "DELETE FROM `entradas` WHERE Id_teatro='" . $Id . "' AND sesion='" . $sesion . "' AND dia='" . $dia . "' AND fila='" . $fila . "' AND asiento='" . $asiento . "'";

        $coleccion = $this->enlace->selectCollection('entradas');
        $this->datos = $coleccion->remove(array("id_teatro" => (string)$Id, "sesion" => $sesion, "dia" => (string)$dia, "fila" => (string)$fila, "asiento" => (string)$asiento), array('justOne' => True));

        if (!$this->datos) {
            return false;
        }

        return true;

        //$Id es el identificador del teatro, $sesion la sesion de la obra, $fila y $asiento la localizacion de la butaca y $dia el dia de la respresentacion.
    }

}
?>


/*
 * El código de la práctica es el resultado exclusivamente de sus miembros, Raul Cobos Hernando, Alicia Rodriguez Torija y Sergio Rodríguez Gundin
 * Fecha: 27/01/2015
 * AUTORES: Raul Cobos Hernando, Alicia Rodríguez Torija y Sergio Rodríguez Gundin
 */

<?PHP

include_once dirname(__DIR__) . '/DAOContents.php';

class Teatro {

    private static $instance = NULL;
    private $conexion;
    private $enlace;
    private $datos;
    public $dia;

//constructor privado (SINGLETON PATTERN)
    private function teatro() {

        $m = getDate();
        $this->dia = $m['year'] . "-" . $m['mon'] . "-" . $m['mday'];
        $this->conexion = new MongoClient();
        $this->enlace = $this->conexion->selectDB(DAOContents::getInstance()->dbName);
    }

//Destructor
    function _teatro() {
        
    }

// Devuelve la instanacia de la clase (SINGLETON PATTERN)
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new teatro();
        }

        return self::$instance;
    }

    //Añade un teatro
    public function add_teatro($nombre_teatro, $nombre_obra, $descripcion, $sesion1, $sesion2, $sesion3, $nume_filas, $nume_asientos) {

        if (!Input::string_seguro($nombre_teatro)) {
            return "ERROR, el nombre del teatro contiene caracteres prohibidos";
        } else if (!Input::string_seguro($nombre_obra)) {
            return "ERROR, el nombre de la obra contiene caracteres prohibidos";
        } else if (!Input::mensaje_seguro($descripcion)) {
            return "ERROR, la descripcion contiene caracteres prohibidos";
        } else if (!Input::es_numero($nume_filas)) {
            return "ERROR, el numero de filas no es un entero";
        } else if (!Input::es_numero($nume_asientos)) {
            return "ERROR, el numero de asientos no es un entero";
        } else if ($nombre_teatro != "" && $nombre_obra != "") {



            $coleccion = $this->enlace->selectCollection('teatro');
            $this->datos = $coleccion->insert(array("nombre_teatro" => $nombre_teatro, "nombre_obra" => $nombre_obra, "descripcion" => $descripcion,
                "sesion1" => $sesion1, "sesion2" => $sesion2, "sesion3" => $sesion3, "nume_filas" => $nume_filas, "nume_asientos" => $nume_asientos));

            if (!$this->datos) {
                return "ERROR, insercion sin exito";
            } else {
                return "Se ha creado el teatro con exito";
            }
        } else {
            return "Debes rellenar los campos obligatorios";
        }
    }

// Modifica un teatro
    public function modificar($nombre_actual, $nombre_teatro, $nombre_obra, $descripcion, $sesion1, $sesion2, $sesion3, $nume_filas, $nume_asientos) {


        if (!Input::string_seguro($nombre_teatro)) {
            return "ERROR, el nombre del teatro contiene caracteres prohibidos";
        } else if (!Input::string_seguro($nombre_obra)) {
            return "ERROR, el nombre de la obra contiene caracteres prohibidos";
        } else if (!Input::mensaje_seguro($descripcion)) {
            return "ERROR, la descripcion contiene caracteres prohibidos";
        } else if (!Input::es_numero($nume_filas)) {
            return "ERROR, el numero de filas no es un entero";
        } else if (!Input::es_numero($nume_asientos)) {
            return "ERROR, el numero de asientos no es un entero";
        } else
        if ($nombre_teatro != "" && $nombre_obra != "") {

            //$documento = [array("nombre_teatro" => $nombre_teatro), array("nombre_teatro" => $nombre_teatro, "nombre_obra" => $nombre_obra, "descripcion" => $descripcion,
            //"sesion1" => $sesion1, "sesion2" => $sesion2, "sesion3" => $sesion3, "nume_filas" => $nume_filas, "nume_asientos" => $nume_asientos)];

            /* $query = "UPDATE `teatro` SET 
              `nombre_teatro`='" . $nombre_teatro . "',
              `nombre_obra`='" . $nombre_obra . "',
              `descripcion`='" . $descripcion . "',
              `sesion1`='" . $sesion1 . "',`sesion2`='" . $sesion2 . "',`sesion3`='" . $sesion3 . "',
              `nume_filas`='" . $nume_filas . "',
              `nume_asientos`='" . $nume_asientos . "'
              WHERE `nombre_teatro`='" . $nombre_teatro . "'";
             */
            $coleccion = $this->enlace->selectCollection('teatro');
            $nuevosDatos = array('$set' => array("nombre_teatro" => $nombre_teatro, "nombre_obra" => $nombre_obra, "descripcion" => $descripcion,
                    "sesion1" => $sesion1, "sesion2" => $sesion2, "sesion3" => $sesion3, "nume_filas" => $nume_filas, "nume_asientos" => $nume_asientos));
            $this->datos = $coleccion->update(array("nombre_teatro" => $nombre_actual), $nuevosDatos);

            if (!$this->datos)
                return "ERROR, insercion sin exito";
            else {
                return "Se ha editado el teatro con exito";
            }
        } else {
            return "Debes rellenar los campos obligatorios";
        }
    }

// Recupera el nº total de obras
    public function nume_obras() {
        //$query = "SELECT nombre_obra FROM teatro";
        //el primer array es vacio para que busque en todos los documentos de la collecion teatro
        //$query = [array(), array("nombre_obra")];
        //si la variable $documento no recoge bien los dos arrays podriamos pasarle al find() directamente la consulta
        //de esta forma $consultar = $coleccion->find(array(), array("nombre_obra")); 

        $coleccion = $this->enlace->selectCollection('teatro');
        $consultar = $coleccion->find(array(), array("nombre_obra"));
        $consultar->count();

        return $consultar->count();
    }

// Borra un teatro
    public function del_teatro($identificador) {

        $coleccion = $this->enlace->selectCollection('teatro');
        $this->datos = $coleccion->remove(array('_id' => new MongoId($identificador)), array("safe" => True));
        $coleccion = $this->enlace->selectCollection('entradas');
        $this->datos = $coleccion->remove(array('id_teatro' => (string) $identificador), array("safe" => True));

        if (!$this->datos)
            return "ERROR, no se ha podido eliminar el registro";
        else {
            return "Se ha eliminado el teatro con exito";
        }
    }

    // Recuperar datos para modificar un teatro
    public function recuperar_teatro($identificador) {
        $coleccion = $this->enlace->selectCollection('teatro');
        $this->datos = $coleccion->find(array('_id' => new MongoId($identificador)), array("nombre_teatro", "nombre_obra", "descripcion", "sesion1", "sesion2", "sesion3", "nume_filas", "nume_asientos"));

        return $this->datos;
    }

    public function recuperar_valoraciones($id_obra, $orden) {

        $coleccion = $this->enlace->selectCollection('valoraciones');

        if ($orden == 'puntuacion') {
            $this->datos = $coleccion->find(array("id_obra" => (string) $id_obra), array("id_usuario", "id_obra", "fecha", "puntuacion", "comentario"));
            $this->datos->sort(array("puntuacion" => -1));
        } else {
            $this->datos = $coleccion->find(array("id_obra" => (string) $id_obra), array("id_usuario", "id_obra", "fecha", "puntuacion", "comentario"));
            $this->datos->sort(array("fecha" => -1));
        }

        return $this->datos;
    }

    // Buscar obra
    public function buscar($busqueda) {
        //Se ejecuta un select para recuperar toda la informacion de la tabla "teatro" referida al campo de busqueda.
        //$query = "SELECT * FROM teatro WHERE `nombre_teatro` LIKE '%" . $busqueda . "%' OR `nombre_obra` LIKE '%" . $busqueda . "%' OR `descripcion` LIKE '%" . $busqueda . "%'";
        //Si se produce un error se indica al usuario. En caso contrario si no hay resultados se muestra un mensaje informando de tal situacion, y en caso de que haya resultados se muestran los resultados recuperados.
        if (!Input::string_seguro($busqueda)) {
            return "ERROR, la busqueda contiene caracteres prohibidos";
        } else {

            $coleccion = $this->enlace->selectCollection('teatro');
            $this->datos = $coleccion->find(array('$or' => array(array("nombre_teatro" => $busqueda), array("descripcion" => new MongoRegex("/" . $busqueda . "/")), array("nombre_obra" => $busqueda))), array("nombre_teatro", "nombre_obra", "descripcion", "sesion1", "sesion2", "sesion3", "nume_filas", "nume_asientos"));

            if ($this->datos->count() <= 0) {
                return false;
            }

            return $this->datos;
        }
    }

    // Listado de obras
    public function listar() {

        //Se ejecuta un select para recuperar toda la informacion de la tabla "teatro".
        //Si se produce un error se indica al usuario. En caso contrario se muestran los resultados recuperados.
        //$query = "SELECT * FROM teatro";

        $query = array();

        $coleccion = $this->enlace->selectCollection('teatro');
        $this->datos = $coleccion->find($query);

        return $this->datos;
    }

    public function addValoracion($id_obra, $fecha, $puntuacion, $comentario) {

        if (!Input::mensaje_seguro(Input::escapar_string($comentario))) {
            return "La valoracion contiene caracteres prohibidos";
        } else {
            //$query = "INSERT INTO `valoraciones`(`id_usuario`, `id_obra`, `fecha`, `puntuacion`, `comentario`) VALUES ('" . $_SESSION['id'] . "','$id_obra','$fecha','$puntuacion','$comentario')";

            $documento = array("id_usuario" => $_SESSION['id'], "id_obra" => $id_obra, "fecha" => $fecha, "puntuacion" => $puntuacion, "comentario" => $comentario);

            $coleccion = $this->enlace->selectCollection('valoraciones');
            $this->datos = $coleccion->insert($documento);

            return $this->datos;
        }
    }

}

//END clase teatro
?>
<?php

include_once dirname(__DIR__) . '/DAOContents.php';
include_once dirname(__DIR__) . '/Input.php';

class Usuario {

    private static $instance = NULL;
    private $conexion;
    private $enlace;
    private $datos;

    //constructor privado (SINGLETON PATTERN)
    private function Usuario() {
        $this->conexion = new MongoClient();
        $this->enlace = $this->conexion->selectDB(DAOContents::getInstance()->dbName);
    }

    //Destructor
    function _Usuario() {
        
    }

    // Devuelve la instanacia de la clase (SINGLETON PATTERN)
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Usuario();
        }

        return self::$instance;
    }

    public function loggin($usuario, $password) {

        $coleccion = $this->enlace->selectCollection('usuarios');
        $this->datos = $coleccion->find(array("usuario" => $usuario), array("sal"));

        $sal = $this->datos->getNext()['sal'];
        $this->datos = $coleccion->find(array("usuario" => $usuario, "password" => hash("sha256", $password . $sal)), array("DNI", "nombre", "apellidos", "email", "usuario", "rol", "password"));

        if (!Input::string_seguro($usuario))
            return "El nombre de usuario tiene caracteres no permitidos";
        else if (!Input::string_seguro($password))
            return "La contraseña contiene caracteres no permitidos";
        else {
            $resultado = $this->datos->count();

            if ($resultado > 0) {

                while ($this->datos->hasNext()) {
                    $f = $this->datos->getNext();
                }



                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = $f['rol'];
                $_SESSION['id'] = $f['DNI'];

                return true;
            } else {
                return false;
                echo "no entra aqui";
            }
        }
    }

    public function loggout() {
        session_destroy();
        echo "Hasta pronto";
    }

    public function registrar($DNI, $nombre, $apellidos, $email, $usuario, $rol, $password) {

        if (!Input::comprobar_dni($DNI))
            return "El formato de dni es erroneo";
        else if (!Input::string_seguro($nombre))
            return "El nombre tiene caracteres no permitidos";
        else if (!Input::string_seguro($apellidos))
            return "El apellido tiene caracteres no permitidos";
        else if (!Input::email_valido($email))
            return "El formato del email es erroneo";
        else if (!Input::string_seguro($usuario))
            return "El nombre de usuario tiene caracteres no permitidos";
        else if (!Input::pw_segura($password))
            return "La contraseña no es segura, pruebe con letras en mayuscula y minuscula y numeros";
        else if (!Input::string_seguro($password))
            return "La contraseña tiene caracteres no permitidos";
        else {
            $coleccion = $this->enlace->selectCollection('usuarios');
            $this->datos = $coleccion->find(array("usuario" => $usuario));

            $num_resultados = $this->datos->count();

            $sal = sha1(uniqid(rand(), true));

            if ($num_resultados == 0) {

                $this->datos = $coleccion->insert(array("DNI" => $DNI, "nombre" => $nombre, "apellidos" => $apellidos, "email" => $email, "usuario" => $usuario, "rol" => $rol, "sal" => $sal, "password" => hash("sha256", $password . $sal)));
                if ($this->datos) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function editar($nombre, $apellidos, $email, $rol, $usuario) {

        if (!Input::string_seguro($nombre))
            return "El nombre tiene caracteres no permitidos";
        else if (!Input::string_seguro($apellidos))
            return "El apellido tiene caracteres no permitidos";
        else if (!Input::email_valido($email))
            return "El formato del email es erroneo";
        else if (!Input::string_seguro($usuario))
            return "El nombre de usuario tiene caracteres no permitidos";
        else {
            $coleccion = $this->enlace->selectCollection('usuarios');
            $this->datos = $coleccion->find(array("usuario" => $usuario));
            $num_resultados = $this->datos->count();

            if ($_SESSION['usuario'] != $usuario) {
                $exito = $num_resultados <= 0;
            } else {
                $exito = true;
            }

            if ($exito) {

                $nuevosDatos = array('$set' => array("nombre" => $nombre, "apellidos" => $apellidos, "email" => $email, "usuario" => $usuario, "rol" => $rol));
                $this->datos = $coleccion->update(array("usuario" => $_SESSION['usuario']), $nuevosDatos);

                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = $rol;
                return true;
            } else {
                return false;
            }
        }
    }

    public function editar_password($passwordActual, $passwordNueva) {

        if (!Input::pw_segura($passwordNueva))
            return "La nueva contraseña no es segura, pruebe con letras en mayuscula y minuscula y numeros";
        else if (!Input::string_seguro($passwordNueva))
            return "La nueva contraseña tiene caracteres no permitidos";
        else if (!Input::string_seguro($passwordActual))
            return "La contraseña actual tiene caracteres no permitidos";
        else {
            $coleccion = $this->enlace->selectCollection('usuarios');
            $this->datos = $coleccion->find(array("password" => sha1($passwordActual . DAOContents::getInstance()->salt)), array("DNI", "nombre", "apellidos", "email", "usuario", "rol", "password"));


            if ($this->datos->count() > 0) {
                $passwordGenerada = array('$set' => array("password" => sha1($passwordNueva . DAOContents::getInstance()->salt)));
                $this->datos = $coleccion->update(array("password" => sha1($passwordActual . DAOContents::getInstance()->salt)), $passwordGenerada);
                return true;
            } else {
                return false;
            }
        }
    }

    public function getDatos($usuario) {
        $coleccion = $this->enlace->selectCollection('usuarios');
        $this->datos = $coleccion->find(array("usuario" => $usuario), array("DNI", "nombre", "apellidos", "email", "usuario", "rol", "password"));

        return $this->datos;
    }

    public function getDatosFromDNI($dni) {

        $coleccion = $this->enlace->selectCollection('usuarios');
        $this->datos = $coleccion->find(array("DNI" => $dni), array("DNI", "nombre", "apellidos", "email", "usuario", "rol", "password"));

        return $this->datos;
    }

    /**
     * Comprueba si el usuario puede valorar una obra cumpliendo dos condiciones:
     * 1. Tener al menos una entrada de la obra.
     * 2. No haber valorado antes la obra.
     */
    public function obraValorable($id_obra) {

        $coleccion = $this->enlace->selectCollection('valoraciones');
        $this->datos = $coleccion->find(array("id_usuario" => (string) $_SESSION['id'], "id_obra" => (string) $id_obra));

        if ($this->datos->count() <= 0) {

            $coleccion = $this->enlace->selectCollection('entradas');
            $this->datos = $coleccion->find(array("id_usuario" => (string) $_SESSION['id'], "id_teatro" => (string) $id_obra));

            if ($this->datos->count() > 0) {
                return true;
            }
        }
        return false;
    }

}

?>
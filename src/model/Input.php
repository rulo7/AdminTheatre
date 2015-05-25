/*
 * El código de la práctica es el resultado exclusivamente de sus miembros, Raul Cobos Hernando, Alicia Rodriguez Torija y Sergio Rodríguez Gundin
 * Fecha: 27/01/2015
 * AUTORES: Raul Cobos Hernando, Alicia Rodríguez Torija y Sergio Rodríguez Gundin
 */

<?php

class Input {

    public static function comprobar_dni($dni) {
        //comparamos la longitud del dni
        if ((strlen($dni) == 9) & (preg_match('/^([0-9]{7,8})([A-Z])$/i', $dni, $matches) == true)) {
            return true;
        } else {
            return false;
        }
    }

    public static function es_numero($string) {
        if (is_numeric($string))
            return true;
        else
            return false;
    }

    public static function es_fecha($fecha) {
        if (DateTime::createFromFormat('Y-m-d G:i:s', $fecha) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public static function pw_segura($pw) {
        if (strlen($pw) < 6) {
            return false;
            echo "la contraseña es demasiado corta";
        } else if (preg_match("/^.*(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $pw)) {
            return true;
        } else {
            return false;
            echo "la contraseña ha de contener numeros y letras en mayuscula y minuscula";
        }
    }

    //email
    public static function email_valido($direccion) {
        if (preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,4}$/', $direccion))
            return true;
        else
            return false;
    }

    //string seguro y con long adecuada
    public static function string_seguro($string) {
        if (preg_match('/^[a-zA-Z0-9 ]*$/', $string))
            if (strlen($string) < 60) {
                return true;
            } else
                return false;
        else
            return false;
    }

    //mensaje seguro y con long adecuada
    public static function mensaje_seguro($string) {
        if (preg_match('/^[a-zA-Z0-9 ?!,.;:]*$/', $string)) {
            if (strlen($string) < 160) {
                return true;
            } else
                return false;
        } else
            return false;
    }

    //para escapar vale con hacer un cast como string
    public static function escapar_string($mensaje) {
        return (string) $mensaje;
    }

}

?>
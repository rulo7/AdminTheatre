/*
 * El código de la práctica es el resultado exclusivamente de sus miembros, Raul Cobos Hernando, Alicia Rodriguez Torija y Sergio Rodríguez Gundin
 * Fecha: 27/01/2015
 * AUTORES: Raul Cobos Hernando, Alicia Rodríguez Torija y Sergio Rodríguez Gundin
 */

<?php

Class DAOContents {

    private static $instance = NULL;
    public $dbUser;
    public $dbPassword;
    public $dbName;
    public $hostName;
    public $salt;

    private function __construct() {
        $this->dbUser = 'root';
        $this->dbPassword = 'root';
        $this->hostName = 'localhost';
        $this->dbName = 'giw_teatros';
    }

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new DAOContents();
        }
        return self::$instance;
    }

}

?>

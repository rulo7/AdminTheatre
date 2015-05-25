/*
 * El código de la práctica es el resultado exclusivamente de sus miembros, Raul Cobos Hernando, Alicia Rodriguez Torija y Sergio Rodríguez Gundin
 * Fecha: 27/01/2015
 * AUTORES: Raul Cobos Hernando, Alicia Rodríguez Torija y Sergio Rodríguez Gundin
 */

<?php

include_once "Usuario.php";
session_start();
if ($_REQUEST['id_token'] == $_SESSION['id_token']) {
    if ($_REQUEST['operacion'] == "loggin") {
        $log = Usuario::getInstance()->loggin($_REQUEST['usuario'], $_REQUEST['password']);
        if (is_string($log)) {
            echo $log;
        } else if (!$log) {
            echo "Datos erroneos";
        } else
            echo "Loggin realizado con exito";
    } else if ($_REQUEST['operacion'] == "loggout") {

        Usuario::getInstance()->loggout();
    } else if ($_REQUEST['operacion'] == "registrar") {

        if ($_REQUEST['password'] == $_REQUEST['password2']) {
            $registro = Usuario::getInstance()->registrar($_REQUEST['DNI'], $_REQUEST['nombre'], $_REQUEST['apellidos'], $_REQUEST['email'], $_REQUEST['usuario'], $_REQUEST['rol'], $_REQUEST['password']);
            if (is_string($registro)) {
                echo $registro;
            } else if ($registro) {
                echo "ok en el registro";
            } else
                echo "error en el registro";
        } else {
            echo "Las contraseñas no coinden";
        }
    } else if ($_REQUEST['operacion'] == "editar") {
        $edit = Usuario::getInstance()->editar($_REQUEST['nombre'], $_REQUEST['apellidos'], $_REQUEST['email'], $_REQUEST['rol'], $_REQUEST['usuario']);
        if (is_string($edit)) {
            echo $edit;
        } else if ($edit) {
            echo "Perfil editado con exito";
        } else {
            echo "error al editar perfil";
        }
    } else if ($_REQUEST['operacion'] == "editar_password") {
        $pw = Usuario::getInstance()->editar_password($_REQUEST['passwordActual'], $_REQUEST['passwordNueva']);
        if (is_string($pw)) {
            echo $pw;
        } else if ($pw) {
            echo "Password editada con exito";
        } else {
            echo "error al editar Password";
        }
    }
} else {
    echo "eres muy pirata tu";
}

echo "<p><a href='../../view/index.php'>Volver al inicio</a></p>";
?>
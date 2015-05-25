/*
 * El código de la práctica es el resultado exclusivamente de sus miembros, Raul Cobos Hernando, Alicia Rodriguez Torija y Sergio Rodríguez Gundin
 * Fecha: 27/01/2015
 * AUTORES: Raul Cobos Hernando, Alicia Rodríguez Torija y Sergio Rodríguez Gundin
 */

<HTML>
    <HEAD>
        <TITLE>Practica-Teatros</TITLE>
        <STYLE  TYPE="text/css">
            <!--
            input
            {
                font-family : Arial, Helvetica;
                font-size : large;
                color : #000033;
                font-weight : normal;
                border-color : #999999;
                border-width : 1px;
                background-color : #FFFFFF;
            }
            body{
                margin: 0 auto;
                text-align: center;
            }
            #dinamico {
                display: inline-block;
                text-align: left;
            }

            #dinamico h1{
                width: 100%;
                font-size: larger;
                font-family: "Book Antiqua";
                border: 1px solid white;
                color: white;
                font-weight: bold;
                background-color: green;
                padding: 5px;
            }

            #dinamico table td{
                font-weight: bolder;
            }

            #dinamico a{
                background-color: white;
                border: 1px solid #999999;
                padding: 2px;
            }

            #dinamico label{
                background-color: green;
                color: white;
                font-weight: bold;
                margin-right: 15px;
                padding: 2px;
            }

            #dinamico a:hover {
                color: black;
            }

            #dinamico textarea{
                height: 60px;
                width: 100%;
            }

            #teatro {
                width: 100%;
                text-align: center;
                margin: 0 auto;
            }

            -->
        </style>
    </HEAD>

    <BODY bgcolor="#C0C0C0" link="green" vlink="green" alink="green">
    <BASEFONT face="arial, helvetica">

        <TABLE border="0" align="center" cellspacing="3" cellpadding="3" width="1000">
            <TR>
                <TH colspan="2" width="100%" bgcolor="green"><FONT size="6" color="white">Teatros</FONT></TH>
            </TR>
        </TABLE>
        <P>
            <?PHP
            session_start();
            if (!isset($_SESSION['usuario'])) {
                $_SESSION['id_token'] = sha1(uniqid(rand(), true));
                /*
                 * MUESTRA LA PAGINA EN ESTADO DE USUARIO NO LOGGEADO
                 */
                echo "<div id='dinamico'>";
                echo "<h1 style='color:red; background-color:gray;'>Acceso restringido a usuarios</h1>";
                echo
                "<h1 style='font-size:medium'>Iniciar sesion</h1>
                <form action='../model/usuarios/sesion.php?operacion=loggin&id_token=" . $_SESSION['id_token'] . "' method='POST'>
                <p><label>Usuario&nbsp</label><input type='text' name='usuario'/></p>
                <p><label>Password&nbsp</label><input type='password' name='password'/></p>
                <input type='submit' value='Iniciar sesion'/>
                </form>
                <form action='../model/usuarios/sesion.php?operacion=registrar&id_token=" . $_SESSION['id_token'] . "' method='POST'>
                <h1 style='font-size:medium'>Registrarse</h1>
                <p><label>Nombre&nbsp</label><input type='text' name='nombre'/></p>
                <p><label>Apellidos&nbsp</label><input type='text' name='apellidos'/></p>
                <p><label>DNI&nbsp</label><input type='text' name='DNI'/></p>
                <p><label>email&nbsp</label><input type='text' name='email'/></p>
                <p><label>Usuario&nbsp</label><input type='text' name='usuario'/></p>
                <p><label>Rol&nbsp</label>
                <select name='rol'>
                <option value='cliente'>cliente</option>
                <option value='admin'>admin</option>                
                </select>
                </p>
                <p><label>Password&nbsp</label><input type='password' name='password'/></p>
                <p><label>Repite la password&nbsp</label><input type='password' name='password2'/></p>
                <input type='submit' value='registrarse'/>
                </form>
                ";
                echo "</div>";
            } else {
                header('Location:' . $_SESSION['rol'] . '.php');
            }
            ?>


            </BODY>
            </HTML>


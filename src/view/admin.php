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
            if (isset($_SESSION['usuario']) && $_SESSION['rol'] == 'admin') {

                /*
                 * MUESTRA LA CABECERA EN ESTADO DE USUARIO LOGGEADO AL INICIO
                 */
                include_once "../model/teatro/Teatro.php";
                include_once "../model/teatro/Entradas.php";
                include_once "../model/usuarios/Usuario.php";

                $_SESSION['id_token'] = sha1(uniqid(rand(), true));

                if (isset($_REQUEST['operacion'])) {
                    $operacion = $_REQUEST['operacion'];
                }

                echo "<p style='width:90%; text-align: right'><a href='../model/usuarios/sesion.php?operacion=loggout&id_token=" . $_SESSION['id_token'] . "'>Cerrar sesion</a></p>";
                echo "<p style='width:90%; text-align: right'><a href='admin.php?operacion=ver_perfil'>ver perfil</a></p>";
                echo "<p style='width:90%; text-align: right'><a href='admin.php?operacion=editar_perfil'>editar perfil</a></p>";
                echo "<p style='width:90%; text-align: right'>Rol: " . $_SESSION['rol'] . "</p>";



                echo
                "<CENTER><P>
                <TABLE border='0' width='600'>
                <TR><TD align=right>

                <FORM name='form2' METHOD='POST' ACTION='admin.php?operacion=introducir'>
                <INPUT TYPE='SUBMIT' NAME='alta' VALUE='Nuevo teatro'>
                </FORM>
                </TD><TD width=100 align=left>
                <FORM name='form3' METHOD='POST' ACTION='admin.php?operacion=listado'>
                <INPUT TYPE='SUBMIT' NAME='alta' VALUE='Listado completo'>
                </FORM>
                </TD>
                </TR></TABLE>";

                echo "<div id=\"dinamico\">";
                /*
                 * MUESTRA EL RESTO DE LA PAGINA 
                 */
                if (isset($operacion)) {
                    /*
                     * OPERACIONES DEL TEATRO
                     */
                    if ($operacion == "listado") {
                        $datos = Teatro::getInstance()->listar();

                        $_SESSION['id_token_teatro'] = $_SESSION['id_token'];

                        if (!$datos) {
                            echo "error";
                        } else {

                            if ($datos->count() <= 0) {
                                echo "No existen registros";
                            } else {

                                echo "<table width='1200'>";

                                echo "<tr bgcolor='green' color='white'>
			
								<td><font size='4' color='white'>Nombre del Teatro</font></td>
								<td><font size='4' color='white'>Obra</font></td>
								<td width='40%'><font size='4' color='white'>Descripcion</font></td>
								<td><font size='4' color='white'>Operaciones</font></td>				
								
								</tr>";
                                //$datos->hasNext()
                                while ($datos->hasNext()) {
                                    $fila = $datos->getNext();
                                    echo "<tr>
								<td>" . $fila['nombre_teatro'] . "</td>
								<td>" . $fila['nombre_obra'] . "</td>
								<td>" . $fila['descripcion'] . "</td>
								<td>
								<a href='admin.php?operacion=consultar&id=" . $fila['_id'] . "&orden=fecha'>Consultar</a> 
								<a href='admin.php?operacion=editar&id=" . $fila['_id'] . "'>Editar</a>
								<a href='admin.php?operacion=borrar&id_token=" . $_SESSION['id_token_teatro'] . "&id=" . $fila['_id'] . "'>Borrar</a>
				                <a href='admin.php?operacion=ver_localidades_vendidas&id=" . $fila['_id'] . "&dia=" . Teatro::getInstance()->dia . "&sesion=1'>Entradas</a>
								</td>				
								</tr>";
                                }
                            }

                            echo "</table>";
                            echo "<p>El numero total de obras es: " . $datos->count() . "</p>";
                        }
                    } else if ($operacion == "introducir") {

                        $_SESSION['id_token_teatro'] = $_SESSION['id_token'];

                        echo "<h1>NUEVO TEATRO</h1>";
                        echo
                        "<FORM method='POST' action='admin.php?operacion=crear_teatro&id_token=" . $_SESSION['id_token_teatro'] . "'>
                        <p><label>Nombre del Teatro(*)</label><INPUT TYPE='TEXT' NAME='nombre_teatro' size='20'/></p>
                        <p><label>Nombre de la Obra(*)</label><INPUT TYPE='TEXT' NAME='nombre_obra' size='20'/></p>
                        <p><label>Descripcion</label><textarea TYPE='TEXT' NAME='descripcion' size='20'></textarea></p>
                        <p><label>Sesion 1</label><INPUT TYPE='TEXT' NAME='sesion1' value='00:00'/></p>
                        <p><label>Sesion 2</label><INPUT TYPE='TEXT' NAME='sesion2' value='00:00'/></p>
                        <p><label>Sesion 3</label><INPUT TYPE='TEXT' NAME='sesion3' value='00:00'/></p>
                        <p><label>Numero de Filas</label><INPUT TYPE='TEXT' NAME='nume_filas' value='0' size='20'/></p>
                        <p><label>Numero de asientos</label><INPUT TYPE='TEXT' NAME='nume_asientos' value='0' size='20'/></p>
                        <p>(*) Obligatorios</p>
                        <p><input type='submit' value='crear'/>
                        </FORM>
						";
                    } else if ($operacion == "crear_teatro") {
                        if ($_SESSION['id_token_teatro'] == $_REQUEST['id_token']) {
                            echo Teatro::getInstance()->add_teatro($_REQUEST['nombre_teatro'], $_REQUEST['nombre_obra'], $_REQUEST['descripcion'], $_REQUEST['sesion1'], $_REQUEST['sesion2'], $_REQUEST['sesion3'], $_REQUEST['nume_filas'], $_REQUEST['nume_asientos']);
                        } else {
                            echo"eres muy pirata tu";
                        }
                    } else if ($operacion == "consultar") {
                        echo "<h1>CONSULTA TEATRO</h1>";
                        $datos = Teatro::getInstance()->recuperar_teatro($_REQUEST['id']);
                        while ($datos->hasNext()) {
                            $fila = $datos->getNext();
                            echo "
                            <p><label>Nombre del Teatro</label>" . $fila['nombre_teatro'] . "</p>
                            <p><label>Nombre de la Obra</label>" . $fila['nombre_obra'] . "</p>
                            <p><label>Descripcion</label>" . $fila['descripcion'] . "</p>
                            <p><label>Sesion 1</label>" . substr($fila['sesion1'], 0, 5) . "</p>
                            <p><label>Sesion 2</label>" . substr($fila['sesion2'], 0, 5) . "</p>
                            <p><label>Sesion 3</label>" . substr($fila['sesion3'], 0, 5) . "</p>
                            <p><label>Numero de Filas</label>" . $fila['nume_filas'] . "</p>
                            <p><label>Numero de asientos</label>" . $fila['nume_asientos'] . "</p>
                            ";

                            echo "<h2>Valoraciones</h2>";
                            echo "<table cellpadding=10>
                            <tr>
                            <td><label>Usuario</label></td>
                            <td><label><a href='admin.php?operacion=consultar&id=" . $_REQUEST['id'] . "&orden=fecha'>Fecha</a></label></td>
                            <td><label>Comentario</label></td>
                            <td><label><a href='admin.php?operacion=consultar&id=" . $_REQUEST['id'] . "&orden=puntuacion'>Puntuacion</a></label></td>
                            </tr>
                            ";
                        }

                        $valoraciones = Teatro::getInstance()->recuperar_valoraciones($_REQUEST['id'], $_REQUEST['orden']);

                        while ($valoraciones->hasNext()) {

                            $fila = $valoraciones->getNext();

                            $usuario = Usuario::getInstance()->getDatosFromDNI($fila['id_usuario']);
                            while ($usuario->hasNext()) {
                                $fu = $usuario->getNext();
                                echo "
                            <tr>
                            <td>" . $fu['usuario'] . "</p>
                            <td>" . $fila['fecha'] . "</p>
                            <td>" . $fila['comentario'] . "</p>
                            <td align=center>" . $fila['puntuacion'] . "</p>
                            </tr>
                            ";
                            }
                        }
                        echo "</table>";
                    } else if ($operacion == "editar") {
                        $datos = Teatro::getInstance()->recuperar_teatro($_REQUEST['id']);

                        $_SESSION['id_token_teatro'] = $_SESSION['id_token'];

                        while ($datos->hasNext()) {
                            $fila = $datos->getNext();
                            echo "<h1>EDITAR TEATRO</h1>";
                            echo "
                            <FORM method='POST' action='admin.php?operacion=editar_teatro&nombre_actual=" . $fila['nombre_teatro'] . "&id_token=" . $_SESSION['id_token_teatro'] . "'>
                            <p><label>Nombre del Teatro(*)</label><input type='text' name='nombre_teatro' value='" . $fila['nombre_teatro'] . "'/></p>
                            <p><label>Nombre de la Obra(*)</label><input type='text' name='nombre_obra' value='" . $fila['nombre_obra'] . "'/></p>
                            <p><label>Descripcion</label><textarea type='text' name='descripcion'>" . $fila['descripcion'] . "</textarea></p>
                            <p><label>Sesion 1</label><input type='text' name='sesion1' value='" . substr($fila['sesion1'], 0, 5) . "'/></p>
                            <p><label>Sesion 2</label><input type='text' name='sesion2' value='" . substr($fila['sesion2'], 0, 5) . "'/></p>
                            <p><label>Sesion 3</label><input type='text' name='sesion3' value='" . substr($fila['sesion3'], 0, 5) . "'/></p>
                            <p><label>Numero de Filas</label><input type='text' name='nume_filas' value='" . $fila['nume_filas'] . "'/></p>
                            <p><label>Numero de asientos</label><input type='text' name='nume_asientos' value='" . $fila['nume_asientos'] . "'/></p>
                            <p>(*) Obligatorios</p>
                            <p><input type='submit' value='editar'/></p>
                            </form>
                            ";
                        }
                    } else if ($operacion == "editar_teatro") {
                        if ($_SESSION['id_token_teatro'] == $_REQUEST['id_token']) {
                            echo Teatro::getInstance()->modificar($_REQUEST['nombre_actual'], $_REQUEST['nombre_teatro'], $_REQUEST['nombre_obra'], $_REQUEST['descripcion'], $_REQUEST['sesion1'], $_REQUEST['sesion2'], $_REQUEST['sesion3'], $_REQUEST['nume_filas'], $_REQUEST['nume_asientos']);
                        } else {
                            echo "eres muy pirata tu";
                        }
                    } else if ($operacion == "borrar") {
                        if ($_SESSION['id_token_teatro'] == $_REQUEST['id_token']) {
                            echo Teatro::getInstance()->del_teatro($_REQUEST['id']);
                        } else {
                            echo "eres muy pirata tu";
                        }
                    }
                    /*
                     * OPCIONES DE COMPRA DE ENTRADAS
                     */ else if ($operacion == "ver_localidades_vendidas") {
                        echo "<h1>Localidades vendidas</h1>";
                        $datos = Teatro::getInstance()->recuperar_teatro($_REQUEST['id']);
                        if (!$datos) {
                            echo "error";
                        } else {
                            if ($datos->count() < 0) {
                                echo "No existen registros";
                            } else {
                                $dia = $_REQUEST['dia'];
                                $Id = $_REQUEST['id'];
                                $sesion = $_REQUEST['sesion'];
                                $fila = $datos->getNext();
                                ;
                                $filas = $fila['nume_filas'];
                                $asientos = $fila['nume_asientos'];
                                switch ($sesion) {
                                    case 1 :
                                        $opciones = "
                                        <option value='1'>" . substr($fila['sesion1'], 0, 5) . "</option>
                                        <option value='2'>" . substr($fila['sesion2'], 0, 5) . "</option>
                                        <option value='3'>" . substr($fila['sesion3'], 0, 5) . "</option>
                                        ";
                                        break;
                                    case 2 :
                                        $opciones = "
                                        <option value='2'>" . substr($fila['sesion2'], 0, 5) . "</option>
                                        <option value='1'>" . substr($fila['sesion1'], 0, 5) . "</option>
                                        <option value='3'>" . substr($fila['sesion3'], 0, 5) . "</option>
                                        ";
                                        break;
                                    case 3 :
                                        $opciones = "
                                        <option value='3'>" . substr($fila['sesion3'], 0, 5) . "</option>
                                        <option value='1'>" . substr($fila['sesion1'], 0, 5) . "</option>
                                        <option value='2'>" . substr($fila['sesion2'], 0, 5) . "</option>
                                        ";
                                        break;
                                    default :
                                        $opciones = "";
                                        break;
                                }
                                echo
                                "<form action='admin.php?operacion=ver_localidades_vendidas&id=" . $Id . "' method='POST'>
                                <p><label>Nombre teatro</label>" . $fila['nombre_teatro'] . "
                                &nbsp&nbsp&nbsp<label>Nombre obra</label>" . $fila['nombre_obra'] . "</p>
                                <p><label>sesion</label>
                                <select name='sesion'>
                                " . $opciones . "
                                </select>
                                <label>Dia</label><input type='text' value='" . $dia . "' name='dia'/>
                                <input type='submit' value='cambiar sesion'/></p>
                                </form>";
                                echo "<div id='teatro'>";
                                echo "<u>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspESCENARIO&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</u>";
                                echo "<table align='center'>";
                                for ($f = 1; $f <= $filas; $f++) {
                                    echo "<tr>";
                                    for ($a = 1; $a <= $asientos; $a++) {
                                        $datos = Entradas::getInstance()->compradas($_REQUEST['id'], $sesion, $dia, $a, $f);
                                        if (!$datos) {
                                            echo "
                                                <td>
                                                <form action='admin.php?operacion=ver_localidades_vendidas&id=$Id&sesion=$sesion&dia=" . $_REQUEST['dia'] . "' method='POST'>
                                                <input type='submit' value='' style='background-color:green'/>
                                                </form>
                                                </td>
                                                ";
                                        } else {
                                            echo "
                                                <td>
                                                <form action='admin.php?operacion=ver_localidades_vendidas&id=$Id&sesion=$sesion&dia=" . $_REQUEST['dia'] . "' method='POST'>
                                                <input type='submit' value='' style='background-color:blue'/>
                                                </form>
                                                </td>
                                                ";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";
                                echo "</div>";
                            }
                        }
                    }

                    /*
                     * OPCIONES DE USUARIO
                     */ else if ($operacion == "editar_perfil") {

                        $data = Usuario::getInstance()->getDatos($_SESSION['usuario']);
                        while ($data->hasNext()) {
                            $fila = $data->getNext();

                            echo "
                            <form action='../model/usuarios/sesion.php?operacion=editar&id_token=" . $_SESSION['id_token'] . "' method='POST'>
                            <h1 style='font-size:medium'>Editar perfil</h1>
                            <p><label>Nombre&nbsp</label><input type='text' name='nombre' value='" . $fila['nombre'] . "'/></p>
                            <p><label>Apellidos&nbsp</label><input type='text' name='apellidos' value='" . $fila['apellidos'] . "'/></p>
                            <p><label>DNI&nbsp</label>" . $fila['DNI'] . "</p>
                            <p><label>email&nbsp</label><input type='text' name='email' value='" . $fila['email'] . "'/></p>
                            <p><label>Usuario&nbsp</label><input type='text' name='usuario' value='" . $fila['usuario'] . "'/></p>
                            <select name='rol'>
                            <option value='admin'>" . $fila['rol'] . "</option>
                            <option value='cliente'>cliente</option>
                            </select>    
                            <input type='submit' value='editar'/>
                            </form>";
                        }
                        echo "	
                            <form action='../model/usuarios/sesion.php?operacion=editar_password&id_token=" . $_SESSION['id_token'] . "' method='POST'>
                            <h1 style='font-size:medium'>Editar Password</h1>
                            <p><label>Nueva Password&nbsp</label><input type='password' name='passwordNueva'/></p>
                            <p><label>Password Actual&nbsp</label><input type='password' name='passwordActual'/></p>
                            <input type='submit' value='editar password'/>
                            </form>";
                    } else if ($operacion == "ver_perfil") {

                        $data = Usuario::getInstance()->getDatos($_SESSION['usuario']);
                        while ($data->hasNext()) {
                            $fila = $data->getNext();
                            echo "
                            <h1 style='font-size:medium'>Ver perfil</h1>
                            <p><label>Nombre&nbsp</label>" . $fila['nombre'] . "</p>
                            <p><label>Apellidos&nbsp</label>" . $fila['apellidos'] . "</p>
                            <p><label>DNI&nbsp</label>" . $fila['DNI'] . "</p>
                            <p><label>email&nbsp</label>" . $fila['email'] . "</p>
                            <p><label>Usuario&nbsp</label>" . $fila['usuario'] . "</p>
                            <p><label>Rol&nbsp</label>" . $fila['rol'] . "</p>
                            ";
                        }
                    }
                }
                ?>
                </div>
                <?PHP
            } else {
                header('Location:index.php');
            }
            ?>
            </BODY>
            </HTML>


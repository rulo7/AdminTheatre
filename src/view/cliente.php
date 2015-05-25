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
            include_once "../model/teatro/Teatro.php";
            include_once "../model/teatro/Entradas.php";
            include_once "../model/usuarios/Usuario.php";


            if (isset($_SESSION['usuario']) && $_SESSION['rol'] == 'cliente') {
                $_SESSION['id_token'] = sha1(uniqid(rand(), true));
                /*
                 * MUESTRA LA CABECERA EN ESTADO DE USUARIO LOGGEADO AL INICIO
                 */


                if (isset($_REQUEST['operacion'])) {
                    $operacion = $_REQUEST['operacion'];
                }

                echo "<p style='width:90%; text-align: right'><a href='../model/usuarios/sesion.php?operacion=loggout&id_token=" . $_SESSION['id_token'] . "'>Cerrar sesion</a></p>";
                echo "<p style='width:90%; text-align: right'><a href='cliente.php?operacion=ver_perfil'>ver perfil</a></p>";
                echo "<p style='width:90%; text-align: right'><a href='cliente.php?operacion=editar_perfil'>editar perfil</a></p>";
                echo "<p style='width:90%; text-align: right'><a href='cliente.php?operacion=ver_entradas'>mis entradas</a></p>";
                echo "<p style='width:90%; text-align: right'>Rol: " . $_SESSION['rol'] . "</p>";



                echo
                "<CENTER><P>
                <TABLE border='0' width='600'>
                <TR>
                <TD valign=top align=CENTER colspan=2>
                <FORM name='form1' METHOD='POST' ACTION='cliente.php?operacion=buscar'>
                <FONT size ='-1'>Buscar teatro </FONT>";

                if (!isset($_REQUEST['busqueda'])) {
                    $busqueda = "busqueda";
                } else {
                    $busqueda = $_REQUEST['busqueda'];
                }

                echo "<INPUT TYPE='TEXT' NAME='busqueda' value='$busqueda' size='20'> ";

                echo
                "<INPUT TYPE='SUBMIT' NAME='boton_buscar' VALUE='Buscar'>
                </FORM>
                </TD></TR><TR><TD width=100 align=center>
                <FORM name='form3' METHOD='POST' ACTION='cliente.php?operacion=listado'>
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

                                while ($datos->hasNext()) {
                                    $fila = $datos->getNext();
                                    echo "<tr>
				
									<td>" . $fila['nombre_teatro'] . "</td>
									<td>" . $fila['nombre_obra'] . "</td>
									<td>" . $fila['descripcion'] . "</td>
									<td>
									<a href='cliente.php?operacion=consultar&id=" . $fila['_id'] . "&orden=fecha'>Consultar</a> 
									<a href='cliente.php?operacion=comprar&id=" . $fila['_id'] . "&dia=" . Teatro::getInstance()->dia . "&sesion=1'>Comprar</a>
                                ";

                                    $valorable = Usuario::getInstance()->obraValorable($fila['_id']);
                                    if ($valorable) {
                                        echo "<a href='cliente.php?operacion=valorar&id=" . $fila['_id'] . "'>Valorar</a>";
                                    }

                                    echo
                                    "</td>
                                    				
									</tr>";
                                }
                            }

                            echo "</table>";
                            echo "<p>El numero total de obras es: " . $datos->count() . "</p>";
                        }
                    } else if ($operacion == "buscar") {

                        $datos = Teatro::getInstance()->buscar($_REQUEST['busqueda']);
                        if (is_string($datos)) {
                            echo $datos;
                        }
                        if (!$datos) {
                            echo "<p>No existen registros</p>";
                        } else {

                            echo "<table width='1200'>";

                            echo "<tr bgcolor='green' color='white'>
                                
							<td><font size='4' color='white'>Nombre del Teatro</font></td>
							<td><font size='4' color='white'>Obra</font></td>
							<td width='40%'><font size='4' color='white'>Descripcion</font></td>
							<td><font size='4' color='white'>Operaciones</font></td>				
							
							</tr>";
                            $cont = 0;

                            while ($datos->hasNext()) {
                                $fila = $datos->getNext();
                                echo "<tr>
				
								<td>" . $fila['nombre_teatro'] . "</td>
								<td>" . $fila['nombre_obra'] . "</td>
								<td>" . $fila['descripcion'] . "</td>
								<td>
								<a href='cliente.php?operacion=consultar&id=" . $fila['_id'] . "&orden=fecha'>Consultar</a> 
								<a href='cliente.php?operacion=comprar&id=" . $fila['_id'] . "&dia=" . Teatro::getInstance()->dia . "&sesion=1'>Comprar</a>
				                                ";

                                if (Usuario::getInstance()->obraValorable($fila['_id'])) {
                                    echo "<a href='cliente.php?operacion=valorar&id=" . $fila['_id'] . "'>Valorar</a>";
                                }


                                echo "</td>				
				</tr>";
                                $cont++;
                            }

                            echo "</table>";
                            echo "<p>El numero total de obras es: " . $cont . "</p>";
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
                            <td><label><a href='cliente.php?operacion=consultar&id=" . $_REQUEST['id'] . "&orden=fecha'>Fecha</a></label></td>
                            <td><label>Comentario</label></td>
                            <td><label><a href='cliente.php?operacion=consultar&id=" . $_REQUEST['id'] . "&orden=puntuacion'>Puntuacion</a></label></td>
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
                    }
                    /*
                     * OPCIONES DE COMPRA DE ENTRADAS
                     */ else if ($operacion == "comprar") {
                        echo "<h1>COMPRAR</h1>";

                        $datos = Teatro::getInstance()->recuperar_teatro($_REQUEST['id']);

                        if (!$datos) {
                            echo "error";
                        } else {

                            if (!$datos) {
                                echo "No existen registros";
                            } else {

                                $dia = $_REQUEST['dia'];
                                $Id = $_REQUEST['id'];
                                $sesion = $_REQUEST['sesion'];
                                $fila = $datos->getNext();
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
                                "<form action='cliente.php?operacion=comprar&id=" . $Id . "' method='POST'>
                                <p><label>Nombre teatro</label>" . $fila['nombre_teatro'] . "
                                &nbsp&nbsp&nbsp<label>Nombre obra</label>" . $fila['nombre_obra'] . "</p>					
                                <p><label>sesion</label>
                                <select name='sesion'>
                                        " . $opciones . "
                                </select>
                                <label>Dia</label><input type='text' value='" . $dia . "' name='dia'/>
                                <input type='submit' value='cambiar sesion'/></p>
                                </form>";

                                $_SESSION['id_token_comprar'] = $_SESSION['id_token'];
                                
                                echo "<div id='teatro'>";
                                echo "<u>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspESCENARIO&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</u>";
                                echo "<table align='center'>";
                                for ($f = 1; $f <= $filas; $f++) {
                                    echo "<tr>";
                                    for ($a = 1; $a <= $asientos; $a++) {

                                        $compradas_otros = Entradas::getInstance()->compradas_otros($_REQUEST['id'], $sesion, $dia, $a, $f);
                                        $compradas_usuario = Entradas::getInstance()->compradas_usuario($_REQUEST['id'], $sesion, $dia, $a, $f);

                                        if (!$compradas_otros && !$compradas_usuario) {
                                            echo "						<td>
												<form action='cliente.php?operacion=comprar_exec&fila=" . $f . "&asiento=" . $a . "&dia=" . $dia . "&id=" . $Id . "&sesion=" . $sesion . "&id_token=" . $_SESSION['id_token'] . "' method='POST'>
												<input type='submit' value='' style='background-color:green'/>
												</form>
												</td>
												";
                                        } else {
                                            if ($compradas_otros) {
                                                $dia = $_REQUEST['dia'];

                                                echo "
													<td>
													<form action='cliente.php?operacion=comprar&id=$Id&sesion=$sesion&dia=" . $_REQUEST['dia'] . "' method='POST'>
							                                                <input type='submit' value='' style='background-color:red'/>
													</form>
													</td>
													";
                                            } else {
                                                echo "
													<td>
													<form action='cliente.php?operacion=descomprar&fila=" . $f . "&asiento=" . $a . "&dia=" . $dia . "&id=" . $Id . "&sesion=" . $sesion . "&id_token=" . $_SESSION['id_token'] . "' method='POST'>
							                                                <input type='submit' value='' style='background-color:blue'/>
													</form>
													</td>
													";
                                            }
                                        }
                                    }
                                    echo "</tr>";
                                }
                                echo "</table>";
                                echo "</div>";
                            }
                        }
                    } else if ($operacion == "comprar_exec") {
                        if ($_SESSION['id_token_comprar'] == $_REQUEST['id_token']) {
                            if (!Entradas::getInstance()->exec_comprar($_REQUEST['id'], $_REQUEST['sesion'], $_REQUEST['fila'], $_REQUEST['asiento'], $_REQUEST['dia'])) {
                                echo "error";
                            } else {
                                header('Location: cliente.php?operacion=comprar&id=' . $_REQUEST['id'] . '&sesion=' . $_REQUEST['sesion'] . '&dia=' . $_REQUEST['dia']);
                            }
                        } else {
                            echo "eres muy pirata tu";
                        }
                    } else if ($operacion == "descomprar") {
                        if ($_SESSION['id_token_comprar'] == $_REQUEST['id_token']) {
                            if (!Entradas::getInstance()->descomprar($_REQUEST['id'], $_REQUEST['sesion'], $_REQUEST['fila'], $_REQUEST['asiento'], $_REQUEST['dia'])) {
                                echo "error";
                            } else {
                                header('Location: cliente.php?operacion=comprar&id=' . $_REQUEST['id'] . '&sesion=' . $_REQUEST['sesion'] . '&dia=' . $_REQUEST['dia']);
                            }
                        } else {
                            echo "eres muy pirata tu";
                        }
                    } else if ($operacion == "ver_entradas") {
                        include_once "../model/teatro/Entradas.php";
                        include_once "../model/teatro/Teatro.php";

                        echo "<h1 style='font-size:medium'>Mis entradas</h1>";

                        $datos = Entradas::getInstance()->getEntradasCompradas();

                        if (!$datos) {
                            echo "No tiene ninguna entrada comprada";
                        } else {
                            echo "<table cellpadding=10>";
                            echo "<tr align=left>                                    
                                <td><label>Obra</label></td>
                                <td><label>Tatro</label></td>
                                <td><label>Sesion</label></td>
                                <td><label>Fecha</label></td>
                                <td><label>Fila</label></td>
                                <td><label>Butaca</label></td>
                                </tr>";
                            //$datos->hasNext()
                            while ($datos->hasNext()) {

                                $fila = $datos->getNext();
                                $fteatro = Teatro::getInstance()->recuperar_teatro($fila['id_teatro']);
                                $teatro = $fteatro->getNext();
                                $sesion = $teatro['sesion' . $fila['sesion']];

                                echo "<tr align=left>
                                    
                                <td>" . $teatro['nombre_obra'] . "</td>
                                <td>" . $teatro['nombre_teatro'] . "</td>
                                <td>" . $sesion . "</td>
                                <td>" . $fila["dia"] . "</td>
                                <td align=center>" . $fila["fila"] . "</td>
                                <td align=center>" . $fila["asiento"] . "</td>
                                </tr>";
                            }
                            echo "</table>";
                        }
                    }
                    /*
                     * OPCIONES DE USUARIO
                     */ else if ($operacion == "editar_perfil") {

                        $datos = Usuario::getInstance()->getDatos($_SESSION['usuario']);
                        while ($datos->hasNext()) {
                            $fila = $datos->getNext();
                            echo "
                            <form action='../model/usuarios/sesion.php?operacion=editar&id_token=" . $_SESSION['id_token'] . "' method='POST'>
                            <h1 style='font-size:medium'>Editar perfil</h1>
                            <p><label>Nombre&nbsp</label><input type='text' name='nombre' value='" . $fila['nombre'] . "'/></p>
                            <p><label>Apellidos&nbsp</label><input type='text' name='apellidos' value='" . $fila['apellidos'] . "'/></p>
                            <p><label>DNI&nbsp</label>" . $fila['DNI'] . "</p>
                            <p><label>email&nbsp</label><input type='text' name='email' value='" . $fila['email'] . "'/></p>
                            <p><label>Usuario&nbsp</label><input type='text' name='usuario' value='" . $fila['usuario'] . "'/></p>
                            <select name='rol'>
                            <option value='cliente'>" . $fila['rol'] . "</option>
                            <option value='admin'>admin</option>
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

                        $datos = Usuario::getInstance()->getDatos($_SESSION['usuario']);
                        while ($datos->hasNext()) {
                            $fila = $datos->getNext();
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
                    } else if ($operacion == "valorar") {

                        $datos = Teatro::getInstance()->recuperar_teatro($_REQUEST['id']);
                        while ($datos->hasNext()) {
                            $fila = $datos->getNext();
                            echo "
                            <form action='cliente.php?operacion=valorar_exec&id_obra=" . $_REQUEST['id'] . "' method='POST'>
                            <h1 style='font-size:medium'>Valorar " . $fila['nombre_obra'] . "</h1>
                            <p><label>Fecha&nbsp</label><input type='text' name='fecha' value='" . Teatro::getInstance()->dia . "'/></p>
                            <p><label>Puntuacion&nbsp</label>
                            <select name='puntuacion'>
                            <option value=5>*****</option>
                            <option value=4>****</option>
                            <option value=3>***</option>
                            <option value=2>**</option>
                            <option value=1>*</option>
                            </select>
                            </p>
                            <p><label>Comentario&nbsp</label></p>
                            <p><textarea name='comentario'>Breve comentario ...</textarea></p>
                            <input type='submit' value='valorar'/>
                            </form>";
                        }
                    } else if ($operacion == "valorar_exec") {
                        $valorable = Teatro::getInstance()->addValoracion($_REQUEST['id_obra'], $_REQUEST['fecha'], $_REQUEST['puntuacion'], $_REQUEST['comentario']);
                        if (is_string($valorable)) {
                            echo $valorable;
                        } else
                        if ($valorable) {
                            echo "valoracion realizada con exito";
                        } else {
                            echo "error en la valoracion";
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


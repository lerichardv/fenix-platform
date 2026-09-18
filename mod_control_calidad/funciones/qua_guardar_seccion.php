<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();

$codigo_cuarto_frio	= trim(($_POST['x1']));
$nombre_seccion  	= trim(utf8_decode($_POST['x2']));

$result = $CONTROL->qua_guardar_seccion($codigo_cuarto_frio,
										$nombre_seccion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

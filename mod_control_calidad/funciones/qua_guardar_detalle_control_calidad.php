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

$codigo_control_calidad	= trim(($_POST['x1']));
$cod_cuarto_frio  		= trim(utf8_decode($_POST['x2']));
$cod_seccion  			= trim(utf8_decode($_POST['x3']));
$tiempo_detalle  		= trim(utf8_decode($_POST['x4']));
$valor  				= trim(utf8_decode($_POST['x5']));
$observaciones			= trim(utf8_decode($_POST['x6']));

$result = $CONTROL->qua_guardar_detalle_control_calidad($codigo_control_calidad,
										$cod_cuarto_frio,
										$cod_seccion,
										$tiempo_detalle,
										$valor,
										$observaciones,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

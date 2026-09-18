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
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();

$codigo_detalle  				= trim(($_POST['x1']));
$cod_fertilizante  				= trim(($_POST['x2']));
$horas_aplicacion_calibrar  	= str_replace(',','',$_POST['x3']);
$observaciones_calibrar  		= trim(utf8_decode($_POST['x4']));
$codigo_plantacion  			= trim(($_POST['x5']));
$fecha_calibracion				= trim(($_POST['x6']));

$fecha_calibracion = DateTime::createFromFormat("m-d-Y" , $fecha_calibracion);

$fecha_calibracion = $fecha_calibracion->format('Y-m-d');



$result = $DB_PLANT->plan_ingresar_calibracion_plantacion($codigo_detalle,
										$cod_fertilizante,
										$horas_aplicacion_calibrar,
										$observaciones_calibrar,
										$codigo_plantacion,
										$fecha_calibracion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

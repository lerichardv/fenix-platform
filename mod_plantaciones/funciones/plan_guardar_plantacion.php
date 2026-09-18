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

$codigo_plantacion			= trim(($_POST['x1']));
$cod_info_empresa  			= trim(utf8_decode($_POST['x2']));
$anio_plantacion			= trim(utf8_decode($_POST['x3']));
$fecha_plantacion_planeada 	= trim(utf8_decode($_POST['x4']));
$num_plantacion				= trim(utf8_decode($_POST['x5']));
$acres_plantados			= trim(utf8_decode($_POST['x6']));
$cod_temporada				= trim(utf8_decode($_POST['x7']));
$fecha_plantacion_ejecutada	= trim(utf8_decode($_POST['x8']));

if($fecha_plantacion_planeada != NULL && $fecha_plantacion_planeada != '')
{
	$fecha_plantacion_planeada = DateTime::createFromFormat("m-d-Y" , $fecha_plantacion_planeada);

	$fecha_plantacion_planeada = $fecha_plantacion_planeada->format('Y-m-d');
}
else
{
	$fecha_plantacion_planeada = null;
}
if($fecha_plantacion_ejecutada != NULL)
{
	$fecha_plantacion_ejecutada = DateTime::createFromFormat("m-d-Y" , $fecha_plantacion_ejecutada);

	$fecha_plantacion_ejecutada = $fecha_plantacion_ejecutada->format('Y-m-d');
}
else
{
	$fecha_plantacion_ejecutada = null;
}



$result = $DB_PLANT->plan_guardar_plantacion($codigo_plantacion,
										$cod_info_empresa,
										$anio_plantacion,
										$fecha_plantacion_planeada,
										$num_plantacion,
										$acres_plantados,
										$cod_temporada,
										$fecha_plantacion_ejecutada,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

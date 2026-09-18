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

$codigo_plantacion			= trim(($_POST['x1']));;
$cod_plantacion  			= trim(utf8_decode($_POST['x2']));
$cod_bloques_plantacion2  	= implode(',',$_POST['x3']);
$cantidad					= str_replace(',','',$_POST['x4']);
$observacion_trasplantar	= trim(utf8_decode($_POST['x5']));
$cod_bloques_trasplante  	= implode(',',$_POST['x6']);
$numero_carga				= trim(utf8_decode($_POST['x7']));
$fecha_trasplante			= trim(utf8_decode($_POST['x8']));

$fecha_trasplante = DateTime::createFromFormat("m-d-Y" , $fecha_trasplante);

$fecha_trasplante = $fecha_trasplante->format('Y-m-d');



$result = $DB_PLANT->plan_guardar_trasplantar($codigo_plantacion,
										$cod_plantacion,
										$cod_bloques_plantacion2,
										$cantidad,
										$observacion_trasplantar,
										$cod_bloques_trasplante,
										$numero_carga,
										$fecha_trasplante,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

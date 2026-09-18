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

$codigo_detalle					= trim($_POST['x1']);
$harvest_date  					= trim($_POST['x2']);
$phi  							= trim($_POST['x3']);
$cellos							= trim($_POST['x4']);
$increment_bunch_cello  		= trim($_POST['x5']);
$area_finished  				= trim($_POST['x6']);
$acres_harvested				= str_replace(',', '', $_POST['x7']);
$orden_compra					= str_replace(',', '', $_POST['x8']);
$cantidad_cosechada				= str_replace(',', '', $_POST['x9']);
$commments_harvesting_worksheet = (utf8_decode($_POST['x10']) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$codigo_plantacion				= trim($_POST['x11']);
$cantidad_empacada				= str_replace(',', '', $_POST['x12']);
$crop_number					= trim($_POST['x13']);
$date_packed					= trim($_POST['x14']);
$totes_harvested				= trim($_POST['x15']);
$totes_packed					= trim($_POST['x16']);

$harvest_date = DateTime::createFromFormat("m-d-Y H:i:s" , $harvest_date);

$harvest_date = $harvest_date->format('Y-m-d H:i:s');

$date_packed = DateTime::createFromFormat("m-d-Y" , $date_packed);

$date_packed = $date_packed->format('Y-m-d');



$result = $DB_PLANT->plan_guardar_formulario_harvesting_worksheet($codigo_detalle,
										$harvest_date,
										$phi,
										$cellos,
										$increment_bunch_cello,
										$area_finished,
										$acres_harvested,
										$orden_compra,
										$cantidad_cosechada,
										$commments_harvesting_worksheet,
										$codigo_plantacion,
										$cantidad_empacada,
										$crop_number,
										$date_packed,
										$totes_harvested,
										$totes_packed,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

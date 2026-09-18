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

$harvest_date  					= trim($_POST['x1']);
$phi  							= trim($_POST['x2']);
$cellos							= trim($_POST['x3']);
$increment_bunch_cello  		= trim($_POST['x4']);
$area_finished  				= trim($_POST['x5']);
$acres_harvested				= str_replace(',', '', $_POST['x6']);
$orden_compra					= str_replace(',', '', $_POST['x7']);
$cantidad_cosechada				= str_replace(',', '', $_POST['x8']);
$cantidad_empacada				= str_replace(',', '', $_POST['x9']);
$commments_harvesting_worksheet = (utf8_decode($_POST['x10']) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$codigo_formulario				= trim($_POST['x11']);
$crop_number					= trim($_POST['x12']);
$date_packed					= trim($_POST['x13']);
$totes_harvested				= trim($_POST['x14']);
$totes_packed					= trim($_POST['x15']);

$harvest_date = DateTime::createFromFormat("m-d-Y H:i:s" , $harvest_date);

$harvest_date = $harvest_date->format('Y-m-d H:i:s');

$date_packed = DateTime::createFromFormat("m-d-Y" , $date_packed);

$date_packed = $date_packed->format('Y-m-d');


$result = $DB_PLANT->plan_actualizar_formulario_harvesting_worksheet($codigo_formulario,
										$harvest_date,
										$phi,
										$cellos,
										$increment_bunch_cello,
										$area_finished,
										$acres_harvested,
										$orden_compra,
										$cantidad_cosechada,
										$cantidad_empacada,
										$commments_harvesting_worksheet,
										$crop_number,
										$date_packed,
										$totes_harvested,
										$totes_packed,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

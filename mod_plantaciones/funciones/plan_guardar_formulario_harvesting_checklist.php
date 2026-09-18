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

$codigo_detalle			= trim($_POST['x1']);
$fecha_checklist  		= trim($_POST['x2']);
$loose_bunches  		= trim($_POST['x3']);
$conventional_organic	= trim($_POST['x4']);
$question1				= trim($_POST['x5']);
$question2				= trim($_POST['x6']);
$question3				= trim($_POST['x7']);
$question4				= trim($_POST['x8']);
$question5				= trim($_POST['x9']);
$question6				= trim($_POST['x10']);
$question7				= trim($_POST['x11']);
$question8				= trim($_POST['x12']);
$question9				= trim($_POST['x13']);
$question10				= trim($_POST['x14']);
$question11				= trim($_POST['x15']);
$question12				= trim($_POST['x16']);
$question13				= trim($_POST['x17']);
$question14				= trim($_POST['x18']);
$question15				= trim($_POST['x19']);
$question16				= trim($_POST['x20']);
$question17				= trim($_POST['x21']);
$question18				= trim($_POST['x22']);
$question19				= trim($_POST['x23']);
$question20				= trim($_POST['x24']);
$question21				= trim($_POST['x25']);
$question22				= trim($_POST['x26']);
$actions 				= utf8_decode($_POST['x27']);
$codigo_plantacion		= trim($_POST['x28']);


$fecha_checklist = DateTime::createFromFormat("m-d-Y" , $fecha_checklist);

$fecha_checklist = $fecha_checklist->format('Y-m-d');


$result = $DB_PLANT->plan_guardar_formulario_harvesting_checklist($codigo_detalle,
										$fecha_checklist,
										$loose_bunches,
										$conventional_organic,
										$question1,
										$question2,
										$question3,
										$question4,
										$question5,
										$question6,
										$question7,
										$question8,
										$question9,
										$question10,
										$question11,
										$question12,
										$question13,
										$question14,
										$question15,
										$question16,
										$question17,
										$question18,
										$question19,
										$question20,
										$question21,
										$question22,
										$actions,
										$codigo_plantacion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

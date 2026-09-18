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
$fecha_limpieza  		= trim($_POST['x2']);
$vez_limpieza  			= trim($_POST['x3']);
$equipo_limpieza		= trim($_POST['x4']);
$cleaning_tools  		= trim($_POST['x5']);
$cleaning_potable_water	= trim($_POST['x6']);
$cleaning_detergent  	= trim($_POST['x7']);
$scrubbing				= trim($_POST['x8']);
$rinse_potable_water  	= trim($_POST['x9']);
$sanitizing_chlorine	= trim($_POST['x10']);
$post_sanitizing		= trim($_POST['x11']);
$codigo_plantacion		= trim($_POST['x12']);

$fecha_limpieza = DateTime::createFromFormat("m-d-Y" , $fecha_limpieza);

$fecha_limpieza = $fecha_limpieza->format('Y-m-d');




$result = $DB_PLANT->plan_guardar_formulario_limpieza($codigo_detalle,
										$fecha_limpieza,
										$vez_limpieza,
										$equipo_limpieza,
										$cleaning_tools,
										$cleaning_potable_water,
										$cleaning_detergent,
										$scrubbing,
										$rinse_potable_water,
										$sanitizing_chlorine,
										$post_sanitizing,
										$codigo_plantacion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

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

$cod_aplicacion_quimico			= trim(($_POST['x1']));
/*$cod_inventario_quimico  		= trim(($_POST['x2']));
$cod_unidad_medida_origen  		= trim(($_POST['x3']));*/
//$cod_bloques_aplicar_quimico	= implode(',',$_POST['x4']);
/*$cantidad_sugerida  			= str_replace(',','',$_POST['x5']);
$cod_unidad_medida  			= trim(($_POST['x6']));*/
//$fecha_aplicacion_supervisor	= trim(($_POST['x7']));
//$cantidad_aplicada  			= str_replace(',','',$_POST['x8']);
$cod_tipo_aplicacion			= trim(($_POST['x9']));
$cod_maquinaria  				= trim(($_POST['x10']));
$fecha_aplicacion_operador		= (trim($_POST['x11']) == '' ? NULL:trim($_POST['x11']));
$hora_inicial					= (trim($_POST['x12']) == '' ? NULL:trim($_POST['x12']));
$hora_final						= (trim($_POST['x13']) == '' ? NULL:trim($_POST['x13']));
$viento							= (trim($_POST['x14']) == '' ? NULL:trim($_POST['x14']));
$temperatura					= (trim($_POST['x15']) == '' ? NULL:trim($_POST['x15']));
$descripcion_aplicar_quimico	= trim(utf8_decode($_POST['x16']));
$codigo_plantacion 				= trim(($_POST['x17']));

$fecha_aplicacion_operador = DateTime::createFromFormat("m-d-Y" , $fecha_aplicacion_operador);

$fecha_aplicacion_operador = $fecha_aplicacion_operador->format('Y-m-d');


$result = $DB_PLANT->plan_guardar_aplicar_quimico_offline($cod_aplicacion_quimico,
										/*$cod_inventario_quimico,
										$cod_unidad_medida_origen,*/
										//$cod_bloques_aplicar_quimico,
										/*$cantidad_sugerida,
										$cod_unidad_medida,*/
										//$fecha_aplicacion_supervisor,
										//$cantidad_aplicada,
										$cod_tipo_aplicacion,
										$cod_maquinaria,
										$fecha_aplicacion_operador,
										$hora_inicial,
										$hora_final,
										$viento,
										$temperatura,
										$descripcion_aplicar_quimico,
										$codigo_plantacion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

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

$cod_bloques_aplicar_quimico	= implode(',',$_POST['x4']);

$fecha_aplicacion_supervisor	= trim(($_POST['x7']));

$cod_tipo_aplicacion			= (trim($_POST['x9']) == '' || trim($_POST['x9']) == '-b' ? NULL:trim($_POST['x9']));

$cod_maquinaria  				= (trim($_POST['x10']) == '' || trim($_POST['x10']) == '-b'  ? NULL:trim($_POST['x10']));

$fecha_aplicacion_operador		= (trim($_POST['x11']) == '' ? NULL:trim($_POST['x11']));

$hora_inicial					= (trim($_POST['x12']) == '' ? NULL:trim($_POST['x12']));

$hora_final						= (trim($_POST['x13']) == '' ? NULL:trim($_POST['x13']));

$viento							= (trim($_POST['x14']) == '' ? NULL:trim($_POST['x14']));

$temperatura					= (trim($_POST['x15']) == '' ? NULL:trim($_POST['x15']));

$descripcion_aplicar_quimico	= trim(utf8_decode($_POST['x16']));

$codigo_plantacion 				= trim(($_POST['x17']));

$cod_operador	 				= (trim($_POST['x18']) == '' || trim($_POST['x18']) == '-b'  ? NULL:trim($_POST['x18']));

$codigo_plantacion 				= trim(($_POST['x17']));

$usuario_finca_recomendado 		= trim(($_POST['x19']));

if($usuario_finca_recomendado == 0){
	$usuario_finca_recomendado = $_SESSION['cod_usuario'];
}


$fecha_aplicacion_supervisor = DateTime::createFromFormat("m-d-Y" , $fecha_aplicacion_supervisor);



$fecha_aplicacion_supervisor = $fecha_aplicacion_supervisor->format('Y-m-d');



if($fecha_aplicacion_operador != NULL)

{

	$fecha_aplicacion_operador = DateTime::createFromFormat("m-d-Y" , $fecha_aplicacion_operador);

	$fecha_aplicacion_operador = $fecha_aplicacion_operador->format('Y-m-d');

}





$result = $DB_PLANT->plan_guardar_aplicar_quimico($cod_aplicacion_quimico,

										$cod_bloques_aplicar_quimico,

										$fecha_aplicacion_supervisor,

										$cod_tipo_aplicacion,

										$cod_maquinaria,

										$fecha_aplicacion_operador,

										$hora_inicial,

										$hora_final,

										$viento,

										$temperatura,

										$descripcion_aplicar_quimico,

										$codigo_plantacion,

										$cod_operador,

										$usuario_finca_recomendado);
										// $_SESSION['cod_usuario']);

echo utf8_encode($result[0]['mensaje']);

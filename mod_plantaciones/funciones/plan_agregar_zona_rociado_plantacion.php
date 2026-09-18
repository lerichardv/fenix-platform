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


$codigo_plantacion		= trim($_POST['x1']);

$cod_zona_rociado  		= implode(',',$_POST['x2']);

$cod_tipo_zona			= implode(',',$_POST['x3']);

$cod_quimico_rociadores	= trim($_POST['x4']);

$cantidad_quimico		= trim(str_replace(',','',$_POST['x5']) == '' ? NULL:str_replace(',','',$_POST['x5']));

$cod_unidad_medida2		= trim($_POST['x6']);

$fecha_rociado			= trim($_POST['x7']);

$cod_bloques_rociado  	= implode(',',$_POST['x8']);


$fecha_rociado = DateTime::createFromFormat("m-d-Y H:i:s" , $fecha_rociado);


$fecha_rociado = $fecha_rociado->format('Y-m-d H:i:s');


$result = $DB_PLANT->plan_agregar_zona_rociado_plantacion($codigo_plantacion,

										$cod_zona_rociado,

										$cod_bloques_rociado,

										$cod_tipo_zona,

										$cod_quimico_rociadores,

										$cantidad_quimico,

										$cod_unidad_medida2,

										$fecha_rociado,

										$_SESSION['cod_usuario']);


echo utf8_encode($result[0]['mensaje']);

?>


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

$codigo_zona	= trim(($_POST['x1']));
$cod_info_empresa  	= trim(utf8_decode($_POST['x2']));
$zona				= trim(utf8_decode($_POST['x3']));
$abreviatura 		= trim(utf8_decode($_POST['x4']));
$ubicacion			= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$bloque_inicial		= trim(utf8_decode($_POST['x6']));
$bloque_final		= trim(utf8_decode($_POST['x7']));
$cantidad_acres		= trim(utf8_decode($_POST['x8']));

$result = $DB_PLANT->bw_guardar_zona($codigo_zona,
										$cod_info_empresa,
										$zona,
										$abreviatura,
										$ubicacion,
										$bloque_inicial,
										$bloque_final,
										$cantidad_acres,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

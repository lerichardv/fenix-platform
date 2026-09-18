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

$codigo_zona			= trim(($_POST['x1']));
//$nombre_bloque  = trim(utf8_decode($_POST['x2']));
$modal_bloque_inicial  	= trim(utf8_decode($_POST['x2']));
$modal_bloque_final  	= trim(utf8_decode($_POST['x3']));
$num_acres				= trim(utf8_decode($_POST['x4']));
$clave_bloque			= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));

$result = $DB_PLANT->bw_guardar_bloque($codigo_zona,
										//$nombre_bloque,
										$modal_bloque_inicial,
										$modal_bloque_final,
										$num_acres,
										$clave_bloque,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

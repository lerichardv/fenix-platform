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
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_CONF = new db_configuracion();

$codigo_formulario_sa  	= trim(($_POST['x1']));
$id_item				= trim(utf8_decode($_POST['x2']));
$nombre_item  			= trim(utf8_decode($_POST['x3']));
$cod_tipo_item  		= trim(($_POST['x4']));
$descripcion_item 		= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$codigo_item		  	= trim(($_POST['x6']));
$orden			 		= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));
$flag_alerta			= (trim(utf8_decode($_POST['x8'])) == '' ? NULL:trim(utf8_decode($_POST['x8'])));
$caracteres_max			= (trim(utf8_decode($_POST['x9'])) == '' ? NULL:trim(utf8_decode($_POST['x9'])));
$cod_tipo_mascara		= (trim(utf8_decode($_POST['x10'])) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$requerido				= (trim(utf8_decode($_POST['x11'])) == '' ? NULL:trim(utf8_decode($_POST['x11'])));

$result = $DB_CONF->conf_guardar_item_formulario_sa($codigo_formulario_sa,
													$id_item,
													$nombre_item,
													$cod_tipo_item,
													$descripcion_item,
													$codigo_item,
													$orden,
													$flag_alerta,
													$caracteres_max,
													$cod_tipo_mascara,
													$requerido,
													$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

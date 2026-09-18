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

$codigo_formulario_sa		= trim(($_POST['x1']));
$nombre_formulario  		= trim(utf8_decode($_POST['x2']));
$cod_modulo					= (trim(utf8_decode($_POST['x3'])) == '' ? NULL:trim(utf8_decode($_POST['x3'])));
$cod_menu 					= (trim(utf8_decode($_POST['x4'])) == '' ? NULL:trim(utf8_decode($_POST['x4'])));
$descripcion_formulario		= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$cod_info_empresa			= (trim(utf8_decode($_POST['x6'])) == '' ? NULL:trim(utf8_decode($_POST['x6'])));
$cod_periodo_notificacion 	= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));

$result = $DB_CONF->conf_guardar_formulario_sa($codigo_formulario_sa,
										$nombre_formulario,
										$cod_modulo,
										$cod_menu,
										$descripcion_formulario,
										$cod_info_empresa,
										$cod_periodo_notificacion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

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

$cod_formulario			= trim(($_POST['x1']));
$cod_detalle  			= trim(($_POST['x2']));
$cod_detalle_item  		= trim(($_POST['x3']));
$valor_respuesta 		= trim(utf8_decode($_POST['x4']));
$prioridad		  		= trim(($_POST['x5']));

$result = $DB_CONF->conf_guardar_item_respuesta_formulario_sa($cod_formulario,
													$cod_detalle,
													$cod_detalle_item,
													$valor_respuesta,
													$prioridad,
													$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

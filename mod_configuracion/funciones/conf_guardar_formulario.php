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

$codigo_formulario	= trim(($_POST['x1']));
$nombre_formulario  	= trim(utf8_decode($_POST['x2']));
$puntuacion_minima		= (trim(utf8_decode($_POST['x3'])) == '' ? NULL:trim(utf8_decode($_POST['x3'])));
$puntuacion_maxima 		= (trim(utf8_decode($_POST['x4'])) == '' ? NULL:trim(utf8_decode($_POST['x4'])));
$cod_estado_plantacion	= trim(utf8_decode($_POST['x5']));
$descripcion_formulario	= (trim(utf8_decode($_POST['x6'])) == '' ? NULL:trim(utf8_decode($_POST['x6'])));

$result = $DB_CONF->conf_guardar_formulario($codigo_formulario,
										$nombre_formulario,
										$puntuacion_minima,
										$puntuacion_maxima,
										$cod_estado_plantacion,
										$descripcion_formulario,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

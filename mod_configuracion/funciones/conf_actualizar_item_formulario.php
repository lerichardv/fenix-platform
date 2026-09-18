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

$codigo_item	= trim(($_POST['x1']));
$texto_item		= trim(utf8_decode($_POST['x2']));

$result = $DB_CONF->conf_actualizar_item_formulario($codigo_item,
										$texto_item,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

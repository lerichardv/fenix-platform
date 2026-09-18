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

$cod_item  	= trim(($_POST['x1']));
$flag_activo  		= trim(($_POST['x2']));

$result = $DB_CONF->conf_cambiar_activo_item_formulario_sa($cod_item,
										$flag_activo,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

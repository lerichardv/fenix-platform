<?PHP
/*
 * Permite guardar un nuevo test ambiental.
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

$codigo_environmental_test	= trim(($_POST['x1']));
$nombre_adjunto  			= trim(($_POST['x2']));

$result = $DB_CONF->conf_guardar_adjunto_environmental_test($codigo_environmental_test,
										$nombre_adjunto,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

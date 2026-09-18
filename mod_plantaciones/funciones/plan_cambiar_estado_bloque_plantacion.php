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

$cod_detalle  	= trim(($_POST['x1']));
$flag_activo  	= trim(($_POST['x2']));
$motivo  		= trim(utf8_decode($_POST['x3']));

$result = $DB_PLANT->plan_cambiar_estado_bloque_plantacion($cod_detalle,
										$flag_activo,
										$motivo,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

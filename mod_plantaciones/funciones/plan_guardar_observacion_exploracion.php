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

$flag_exploracion  	= trim(($_POST['x1']));
$cod_exploracion  	= trim(($_POST['x2']));
$observacion_exploracion  = trim(utf8_decode($_POST['x3']));

$result = $DB_PLANT->plan_guardar_observacion_exploracion($flag_exploracion,
										$cod_exploracion,
										$observacion_exploracion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

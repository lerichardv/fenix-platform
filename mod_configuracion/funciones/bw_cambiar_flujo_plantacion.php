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

$codigo_plantacion  = trim(($_POST['x1']));
$flag_estado  		= trim(utf8_decode($_POST['x2']));
$flag_flujo 		= trim(utf8_decode($_POST['x3']));

$result = $DB_PLANT->bw_cambiar_flujo_plantacion($codigo_plantacion,
										$flag_estado,
										$flag_flujo,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

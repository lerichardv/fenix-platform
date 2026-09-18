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

$cod_detalle = trim(($_POST['x1']));

$result = $DB_PLANT->plan_eliminar_quimico_aplicacion_quimico($cod_detalle,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

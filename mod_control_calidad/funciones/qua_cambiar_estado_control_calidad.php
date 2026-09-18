<?PHP
/*
 * Cambia el estado de un producto.
 * @author      Jairo Bonilla
 * @date        2019-01-10
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$DB_CONTROL = new db_control_calidad();

$cod_control_calidad  	= trim(($_POST['x1']));
$flag_activo  		= trim(($_POST['x2']));

$result = $DB_CONTROL->qua_cambiar_estado_control_calidad($cod_control_calidad,
										$flag_activo,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

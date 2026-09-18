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
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();

$codigo_producto	= trim(($_POST['x1']));
$nombre_producto  	= trim(utf8_decode($_POST['x2']));
$cod_pais  			= trim(utf8_decode($_POST['x3']));
$cod_departamento  	= trim(utf8_decode($_POST['x4']));

$result = $CONTROL->qua_guardar_producto($codigo_producto,
										$nombre_producto,
										$cod_pais,
										$cod_departamento,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

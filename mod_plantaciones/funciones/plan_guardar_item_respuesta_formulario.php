<?PHP
/*
 * Permite guardar un item de un formulario con su respuesta.
 * @author      Jairo Bonilla
 * @date        2018-12-25
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

$codigo_plantacion		= trim(($_POST['x1']));
$cod_info_empresa  		= trim(utf8_decode($_POST['x2']));
$codigo_formulario		= trim(utf8_decode($_POST['x3']));
$codigo_item 			= trim(utf8_decode($_POST['x4']));
$codigo_item_checklist 	= trim(utf8_decode($_POST['x5']));
$valor_item				= trim(utf8_decode($_POST['x6']));

$result = $DB_PLANT->plan_guardar_item_respuesta_formulario($codigo_plantacion,
										$cod_info_empresa,
										$codigo_formulario,
										$codigo_item,
										$codigo_item_checklist,
										$valor_item,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$codigo_inventario_vario	= trim(($_POST['x1']));
$cod_info_empresa  			= trim(utf8_decode($_POST['x2']));
$codigo_producto			= str_replace(',','',trim(utf8_decode($_POST['x3'])));
$nombre_producto 			= trim(utf8_decode($_POST['x4']));
$cantidad_producto			= str_replace(',','',trim(utf8_decode($_POST['x5'])));
$cod_unidad_medida			= trim(utf8_decode($_POST['x6']));
$orden_compra				= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));
$fecha_inventario			= (trim(utf8_decode($_POST['x8'])) == '' ? NULL:trim(utf8_decode($_POST['x8'])));

$fecha_inventario = DateTime::createFromFormat("m-d-Y" , $fecha_inventario);

$fecha_inventario = $fecha_inventario->format('Y-m-d');

$result = $DB_INV->inv_guardar_inventario_vario($codigo_inventario_vario,
										$cod_info_empresa,
										$codigo_producto,
										$nombre_producto,
										$cantidad_producto,
										$cod_unidad_medida,
										$orden_compra,
										$fecha_inventario,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

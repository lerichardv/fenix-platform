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

$codigo_orden_compra		= trim(($_POST['x1']));
$cod_detalle  				= trim(utf8_decode($_POST['x2']));
$cod_producto_recibido		= trim(utf8_decode($_POST['x3']));
$cod_unidad_medida_recibido	= trim(utf8_decode($_POST['x4']));
$cantidad_recibido			= str_replace(',','',trim($_POST['x5']));
$fecha_entrega_recibido		= trim(utf8_decode($_POST['x6']));
$observaciones_recibido		= trim(utf8_decode($_POST['x7']));

if($fecha_entrega_recibido != NULL)
{
	$fecha_entrega_recibido = DateTime::createFromFormat("m-d-Y H:i:s" , $fecha_entrega_recibido);
	$fecha_entrega_recibido = $fecha_entrega_recibido->format('Y-m-d H:i:s');
}

$result = $DB_INV->inv_guardar_producto_recibido_orden_compra($codigo_orden_compra,
										$cod_detalle,
										$cod_producto_recibido,
										$cod_unidad_medida_recibido,
										$cantidad_recibido,
										$fecha_entrega_recibido,
										$observaciones_recibido,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

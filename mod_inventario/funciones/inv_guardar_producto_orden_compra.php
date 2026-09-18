<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
 * @editor      Edwin Olivera
 * @update        2023-09-06
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

$codigo_orden_compra	= trim(($_POST['x1']));
$cod_producto  			= trim(utf8_decode($_POST['x2']));
$cantidad				= str_replace(',','',trim($_POST['x3']));
$cod_detalle  			= trim(utf8_decode($_POST['x4']));
$cod_unidad_medida		= trim(utf8_decode($_POST['x5']));
// echo  "codigo_orden_compra= ".$codigo_orden_compra. ", cod_producto= ".$cod_producto. ", cantidad= ".$cantidad. ", cod_detalle= ". $cod_detalle. ", cod_unidad_medida= ".$cod_unidad_medida;
// die();
$result = $DB_INV->inv_guardar_producto_orden_compra($codigo_orden_compra,
										$cod_producto,
										$cantidad,
										$cod_detalle,
										$cod_unidad_medida,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);

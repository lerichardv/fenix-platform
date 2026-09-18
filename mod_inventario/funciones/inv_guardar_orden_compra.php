<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
 * @editor      Edwin Olivera
 * @date        2023-09-06
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$codigo_orden_compra	= trim(($_POST['x1']));
$cod_info_empresa  		= trim(utf8_decode($_POST['x2']));
$cod_proveedor			= trim(utf8_decode($_POST['x3']));
$fecha_orden 			= trim(utf8_decode($_POST['x4']));
$fecha_recibido_pedido	= trim(utf8_decode($_POST['x5'])) == '' ? NULL : trim(utf8_decode($_POST['x5']));
$observaciones			= trim(utf8_decode($_POST['x6'])) == '' ? NULL : trim(utf8_decode($_POST['x6']));
$ext_adjunto			= trim(utf8_decode($_POST['x7'])) == '' ? NULL : trim(utf8_decode($_POST['x7']));
$flag_orden_compra		= (trim(utf8_decode($_POST['x8'])) == '' ? 0 : trim(utf8_decode($_POST['x8'])));
$num_orden_compra		= trim(utf8_decode($_POST['x9']));



$fecha_orden = DateTime::createFromFormat("m-d-Y", $fecha_orden);
$fecha_orden = $fecha_orden->format('Y-m-d');

if ($fecha_recibido_pedido != NULL) {
	$fecha_recibido_pedido = DateTime::createFromFormat("m-d-Y", $fecha_recibido_pedido);
	$fecha_recibido_pedido = $fecha_recibido_pedido->format('Y-m-d');
}

try {
	$result = $DB_INV->inv_guardar_orden_compra(
		$codigo_orden_compra,
		$cod_info_empresa,
		$cod_proveedor,
		$fecha_orden,
		$fecha_recibido_pedido,
		$observaciones,
		$ext_adjunto,
		$flag_orden_compra,
		$num_orden_compra,
		$_SESSION['cod_usuario']
	);
	// codigo_orden_compra
	echo utf8_encode($result[0]['mensaje']);
	// echo json_encode($result);
	//code...
} catch (\Throwable $th) {
	throw $th;
	echo $th;
}

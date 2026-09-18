<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Edwin Olivera
 * @date        2023-09-26
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

$codigo_orden_compra		= trim(($_POST['x1']));
$cod_detalle  				= trim(utf8_decode($_POST['x2']));
$cod_producto_recibido		= trim(utf8_decode($_POST['x3']));
$cod_unidad_medida_recibido	= trim(utf8_decode($_POST['x4']));
$cantidad_recibido			= str_replace(',', '', trim($_POST['x5']));
$fecha_entrega_recibido		= trim(utf8_decode($_POST['x6']));
$observaciones_recibido		= trim(utf8_decode($_POST['x7']));
$datosProductos				= $_POST['x8'];
$cod_info_empresa			= $_POST['x9'];



$cod_detalle_encontrado = [];

if ($fecha_entrega_recibido != NULL) {
	$fecha_entrega_recibido = DateTime::createFromFormat("m-d-Y H:i:s", $fecha_entrega_recibido);
	$fecha_entrega_recibido = $fecha_entrega_recibido->format('Y-m-d H:i:s');
}


for ($index = 0; $index < count($datosProductos) / 5; $index++) {
	$cod_detalle_encontrado = $DB_INV->inv_buscar_cod_detalle_de_producto_orden_de_compra(
		$codigo_orden_compra,
		$datosProductos["codigo_producto_inicial_" . $index], //$cod_detalle_producto,
		$datosProductos["codigo_unida_medida_inicial_" . $index]
	);
	
	$result = $DB_INV->inv_guardar_producto_recibido_orden_compra(
		$codigo_orden_compra,
		$cod_detalle_encontrado[0]["cod_detalle"],
		$datosProductos["codigo_producto_inicial_" . $index], // $cod_producto, //Parametro enviado, pero no usado en el procedimiento almacenado
		$datosProductos["codigo_unida_medida_inicial_" . $index], // $cod_unidad_medida,
		$datosProductos["cantidad_inicial_" . $index], // $cantidad,
		$fecha_entrega_recibido,
		$observaciones_recibido,
		$_SESSION['cod_usuario']
		// 73
	);
	$cod_inventario_encontrado = $DB_INV->inv_buscar_cod_inventario_en_productos_proveedores(
		$datosProductos["codigo_producto_inicial_" . $index]// $cod_detalle
	);
	$codigo_inventario_semilla = $cod_inventario_encontrado[0]["cod_inventario"];

	$DB_INV->inv_actualizar_semilla_por_empresa(
		$codigo_inventario_semilla,
		$datosProductos["cantidad_inicial_" . $index]
	);
	$result = $DB_INV->inv_datos_semilla_de_empresa_por_codigo_de_inventario(
		$codigo_inventario_semilla
	);

	$sumatoria_semillas = $DB_INV->inv_cantidad_total_semilla_por_cod_inventario_semilla($result[0]["cod_inventario_semilla"]);
	$result = $DB_INV->inv_actualizar_cantidad_semilla_consolidad($result[0]["cod_inventario_semilla"], $sumatoria_semillas[0]["cantidad_semilla_consolidad"]);
}

// echo  print_r($cod_detalle_encontrado);
// echo utf8_encode($result[0]['mensaje']);
echo $result;
die();

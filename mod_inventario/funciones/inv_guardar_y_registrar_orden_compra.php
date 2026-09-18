<?PHP
/*
 * Guardar y registra las ordenes de compra. Al mismo tiempo suma la cantidad de producto recibo.
 * @author      Edwin olivera
 * @date        2023-10-05
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
$cod_info_empresa  		= trim($_POST['x2']);
$cod_proveedor			= trim($_POST['x3']);
$fecha_orden 			= trim($_POST['x4']);
$fecha_recibido_pedido	= trim($_POST['x5']) == '' ? NULL : trim($_POST['x5']);
$observaciones			= trim(utf8_decode($_POST['x6'])) == '' ? NULL : trim(utf8_decode($_POST['x6']));
$ext_adjunto			= trim(utf8_decode($_POST['x7'])) == '' ? NULL : trim(utf8_decode($_POST['x7']));
$flag_orden_compra		= (trim($_POST['x8']) == '' ? 0 : trim($_POST['x8']));
$num_orden_compra		= trim($_POST['x9']);


$cod_detalle  				= trim($_POST['x11']);
$cod_producto_recibido		= trim($_POST['x12']);
$cod_unidad_medida_recibido	= trim($_POST['x13']);
$cantidad_recibido			= str_replace(',', '', trim($_POST['x14']));
$fecha_entrega_recibido		= trim(($_POST['x15']));
$observaciones_recibido		= trim(($_POST['x16']));
$datosProductos				= $_POST['x17'];

$fecha_orden = DateTime::createFromFormat("m-d-Y", $fecha_orden);
$fecha_orden = $fecha_orden->format('Y-m-d');
$fecha_sumar_restar = date("Y-m-d H:i:s");

if ($fecha_recibido_pedido != NULL) {
	$fecha_recibido_pedido = DateTime::createFromFormat("m-d-Y", $fecha_recibido_pedido);
	$fecha_recibido_pedido = $fecha_recibido_pedido->format('Y-m-d');
}
$flag_orden_compra = 2; //Guardar la orden (no sumar la cantidad de producto)
$cantidadPropiedadesDiferentes = 9;
try {
	// Guardarmos inicialmente la orde de compras
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

	$cadena = $result[0]["mensaje"]; // '0|Purchase order has been entered correctly.',COD_ORDEN,"|",COD_ORDEN
	$partes = explode("|", $cadena);
	// print_r($partes);
	$codigo_orden_compra = $partes[2];

	// Guardamos los productos y marcandolos como recibidos, haciendo referencia a la orden de Compra
	if ($codigo_orden_compra != "" && $codigo_orden_compra != 0) {
		$flag_orden_compra = 3; //Marcar la orden como recibida (sumar la cantidad de producto)

		// Guardando los productos, sin sumar sus cantidades
		for ($index = 0; $index < count($datosProductos) / $cantidadPropiedadesDiferentes; $index++) {
			if ($datosProductos["producto_activo_" . $index] == 1) {
				$result = $DB_INV->inv_guardar_producto_orden_compra(
					$codigo_orden_compra,
					$datosProductos["codigo_producto_inicial_" . $index], // $cod_producto,
					$datosProductos["cantidad_inicial_" . $index], // $cantidad,
					0, //Se envia esté valor para crear un nuevo registro en la tabla: bw_detalle_ordenes_compra
					$datosProductos["codigo_unida_medida_inicial_" . $index], // $cod_unidad_medida,
					$datosProductos["precio_actual_unidad_inicial_" . $index], // $precio_semilla,
					$datosProductos["codigo_tipo_semilla_inicial_" . $index], // $cod_tipo_semilla,
					$datosProductos["monto_a_pagar_inicial_" . $index], // $monto_pago,	
					$_SESSION['cod_usuario']
				);
			}
		}
		$cod_detalle_encontrado = [];

		if ($fecha_entrega_recibido != NULL) {
			$fecha_entrega_recibido = DateTime::createFromFormat("m-d-Y H:i:s", $fecha_entrega_recibido);
			$fecha_entrega_recibido = $fecha_entrega_recibido->format('Y-m-d H:i:s');
		}


		for ($index = 0; $index < count($datosProductos) / $cantidadPropiedadesDiferentes; $index++) {
			if ($datosProductos["producto_activo_" . $index] == 1) {
				$cod_detalle_encontrado = $DB_INV->inv_buscar_cod_detalle_de_producto_orden_de_compra(
					$codigo_orden_compra,
					$datosProductos["codigo_producto_inicial_" . $index], //$cod_detalle_producto,
					$datosProductos["codigo_unida_medida_inicial_" . $index]
				);
				//Indicamos que hemos recibido los productos y sumamos sus cantidades
				$result = $DB_INV->inv_guardar_producto_recibido_orden_compra(
					$codigo_orden_compra,
					$cod_detalle_encontrado[0]["cod_detalle"],
					$datosProductos["codigo_producto_inicial_" . $index], // $cod_producto,
					$datosProductos["codigo_unida_medida_inicial_" . $index], // $cod_unidad_medida,
					$datosProductos["cantidad_inicial_" . $index], // $cantidad,
					$fecha_entrega_recibido,
					$observaciones_recibido,
					$_SESSION['cod_usuario']
				);

				$cod_inventario_encontrado = $DB_INV->inv_buscar_cod_inventario_en_productos_proveedores(
					$datosProductos["codigo_producto_inicial_" . $index] // $cod_detalle
				);
				$codigo_inventario_semilla = $cod_inventario_encontrado[0]["cod_inventario"];

				$DB_INV->inv_actualizar_semilla_por_empresa_en_orden_de_compra(
					$codigo_inventario_semilla,
					$cod_info_empresa,
					$datosProductos["cantidad_inicial_" . $index]
				);
				$result = $DB_INV->inv_datos_semilla_de_empresa_por_codigo_de_inventario(
					$codigo_inventario_semilla
				);

				$sumatoria_semillas = $DB_INV->inv_cantidad_total_semilla_por_cod_inventario_semilla($result[0]["cod_inventario_semilla"]);
				// $result = $DB_INV->inv_actualizar_cantidad_semilla_consolidad($result[0]["cod_inventario_semilla"], $sumatoria_semillas[0]["cantidad_semilla_consolidad"]);
				$DB_INV->inv_guardar_inventario_semilla_sumar_restar(
					$codigo_inventario_semilla, // cod_inventario de la tabla "bw_inventario_semilla"
					$fecha_sumar_restar,
					$datosProductos["cantidad_inicial_" . $index],
					0,	//$cantidad_restar,
					"Orden de compra #" . $num_orden_compra,
					$_SESSION['cod_usuario']
				);
			}
		}

		// -----------------------------------------------------------------------------
		// Registramos la orden compra como entregada
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
	}
	echo utf8_encode($result[0]['mensaje']);
} catch (\Throwable $th) {
	throw $th;
	echo $th;
}

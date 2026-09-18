<?PHP
/*
 * Actualiza un trasplante y todas las tablas involucradas.
 * @author      Edwin Olivera
 * @update      2023-10-03
*/

use function PHPSTORM_META\type;

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



$numero_ticket_previo		= trim(($_POST['x0']));
$numero_ticket				= trim(($_POST['x1']));
$cod_info_empresa  			= $_POST['x2'];
$fecha_entrega  			= $_POST['x3'];
$datosSemillas				= $_POST['x4'];
$cantidadDeSemillasRegistradas		= $_POST['x5'];
$cod_localizacion				= $_POST['x6'];
$fecha_recibo				= $_POST['x7'];


$resultProductoRegistrado = 0;
if ($fecha_orden != NULL) {
	$fecha_orden = DateTime::createFromFormat("m-d-Y", $fecha_orden);
	$fecha_orden = $fecha_orden->format('Y-m-d');
}
if ($fecha_recibo != NULL) {
	$fecha_recibo = DateTime::createFromFormat("m-d-Y", $fecha_recibo);
	$fecha_recibo = $fecha_recibo->format('Y-m-d');
}
$bandera = " ";

for ($index = 0; $index < $cantidadDeSemillasRegistradas; $index++) {
	$actualizacionNumero = $DB_INV->inv_actulizar_line_item(
		$datosSemillas["cod_inventario_" . $index], 		//$cod_inventario,
		$datosSemillas["cod_plantacion_" . $index], 		//$cod_plantacion,
		$datosSemillas["numero_orden_planta_" . $index],	//$numero_orden,
		$datosSemillas["item_planta_" . $index]
	);


	$resultProductoRegistrado = $DB_INV->inv_buscar_producto_en_trasplante(
		$numero_ticket_previo,
		$datosSemillas["numero_orden_planta_" . $index], //$numero_orden,
		$datosSemillas["cod_inventario_" . $index], //$cod_inventario,
		$datosSemillas["cod_plantacion_" . $index], //$cod_plantacion,
		$datosSemillas["cod_trasplante_" . $index] //$cod_trasplante
	);
	if (isset($resultProductoRegistrado[0]["existe_registro"])) {

		if (isset($datosSemillas["cod_inventario_" . $index]) && $datosSemillas["producto_activo_" . $index] == 1) {
			$result = $DB_INV->inv_actualizar_trasplante(
				$datosSemillas["tray_planta_" . $index], // $tray,
				$datosSemillas["cantidad_de_plantas_" . $index], // $cantidad_plantas,
				$datosSemillas["numero_germinacion_planta_" . $index], // $germinacion,
				$datosSemillas["total_trays_plantas_0"], // $total_trays,
				$datosSemillas["total_plantas_" . $index], // $total_plantas,
				$fecha_recibo, // $fecha_recibo,
				$datosSemillas["cod_trasplante_" . $index], //$cod_trasplante

			);

			$datosPlantacion = $DB_INV->inv_datos_plantancion(
				$datosSemillas["cod_plantacion_" . $index]
			);
			$datosMovimientoTrasplante = $DB_INV->inv_datos_movimiento_trasplante_especifico(
				$datosSemillas["cod_trasplante_" . $index], //$cod_trasplante
				$datosSemillas["cod_plantacion_" . $index],
				$datosSemillas["cod_inventario_" . $index]
			);

			$cantidad_sobrante = 0;
			$nuevo_sobrante = 0;
			$nuevo_sobrante = $datosMovimientoTrasplante[0]["cantidad_reducida"] - $datosSemillas["cantidad_de_plantas_" . $index]; // 20 - 10 = 10
			$cantidad_sobrante = $datosMovimientoTrasplante[0]["cantidad_sobrante"] + $nuevo_sobrante; //20 + 10 = 30 // 20 - 10 = 10
			// echo  $datosSemillas["cantidad_de_plantas_" . $index] . " <> " . $datosMovimientoTrasplante[0]["cantidad_reducida"] . " = " . $nuevo_sobrante;
			// die();
			$resultActua = $DB_INV->inv_actualizar_movimiento_trasplante(
				$datosMovimientoTrasplante[0]["cod_movimiento"],
				$datosSemillas["cod_trasplante_" . $index], //$cod_trasplante
				$datosSemillas["cod_inventario_" . $index], //$cod_inventario,
				$datosSemillas["cod_plantacion_" . $index], //$cod_plantacion,
				$datosSemillas["cantidad_de_plantas_" . $index], // $cantidad_plantas,
				$cantidad_sobrante
			);
		} else {
			// BORRAR
			$codigosMovimientosTrasplantes = $DB_INV->inv_buscar_codigos_movimientos_trasplantes(
				$datosSemillas["cod_trasplante_" . $index]
			);
			$DB_INV->inv_eliminar_movimientos_trasplante(
				$codigosMovimientosTrasplantes[0]['codigos_movimientos'],
			);
			$resultadoBorrado =	$DB_INV->inv_borrar_trasplanacion(
				$datosSemillas["cod_trasplante_" . $index]
			);
		}
	} else {
		if ($fecha_entrega != NULL) {
			$fecha_entrega = DateTime::createFromFormat("m-d-Y", $fecha_entrega);
			$fecha_entrega = $fecha_entrega->format('Y-m-d');
		}
		$result = $DB_INV->inv_guardar_trasplante(
			$datosSemillas["cod_inventario_" . $index], //$cod_inventario,
			$datosSemillas["cod_plantacion_" . $index], //$cod_plantacion,
			$cod_localizacion, //$cod_estado,
			$cod_info_empresa, //$cod_info_empresa,
			$fecha_entrega,
			$numero_ticket,
			$datosSemillas["tray_planta_" . $index], //$tray,
			$datosSemillas["cantidad_de_plantas_" . $index], //$cantidad_plantas,
			$datosSemillas["numero_germinacion_planta_" . $index], //$germinacion,
			$datosSemillas["total_trays_plantas_0"], //$total_trays,
			$datosSemillas["total_plantas_0"], //$total_plantas,
			$datosSemillas["numero_orden_planta_" . $index], //$numero_orden,
			$_SESSION['cod_usuario']
		);
		$cadena = $result; // "0|It has been entered successfully.|" . $this->db_conexion->lastInsertId()

		$partes = explode("|", $cadena);
		$cod_trasplante = $partes[2]; //cod_trasplante

		$datosPlantacion = $DB_INV->inv_datos_plantancion(
			$datosSemillas["cod_plantacion_" . $index]
		);
		$datosMovimientoTrasplante = $DB_INV->inv_datos_ultimo_movimiento_trasplante(
			$datosSemillas["cod_plantacion_" . $index],
			$datosSemillas["cod_inventario_" . $index]
		);
		if (!isset($datosMovimientoTrasplante[0]["cantidad_sobrante"])) {
			$datosMovimientoTrasplante[0]["cantidad_sobrante"] = $datosPlantacion[0]['total']; //110
		}
		$cantidad_sobrante = 0;
		$cantidad_sobrante = ($datosMovimientoTrasplante[0]["cantidad_sobrante"] - $datosSemillas["cantidad_de_plantas_" . $index]);  // 60 - 50= 10
		$result = $DB_INV->inv_guardar_movimiento_trasplante(
			$cod_trasplante,
			$datosSemillas["cod_plantacion_" . $index],
			$datosSemillas["cod_inventario_" . $index],
			$datosSemillas["numero_orden_planta_" . $index],
			$datosPlantacion[0]['total'],
			$datosSemillas["cantidad_de_plantas_" . $index],
			$cantidad_sobrante,
			$_SESSION['cod_usuario']
		);
	}
}

// echo utf8_encode($result);
echo $result;
// echo print_r($datosMovimientoTrasplante);

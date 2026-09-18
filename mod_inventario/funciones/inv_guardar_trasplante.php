<?PHP
/*
 * Guarda un trasplante de una plantación especifica.
 * @author      Edwin Olivera
 * @update      2023-10-03
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



$numero_ticket					= trim(($_POST['x1']));
$cod_info_empresa  				= $_POST['x2'];
$fecha_entrega  				= $_POST['x3'];
$datosSemillas					= $_POST['x4'];
$cantidadDeSemillasRegistradas	= $_POST['x5'];
$cod_localizacion				= $_POST['x6'];


if ($fecha_entrega != NULL) {
	$fecha_entrega = DateTime::createFromFormat("m-d-Y", $fecha_entrega);
	$fecha_entrega = $fecha_entrega->format('Y-m-d');
}

for ($index = 0; $index <  $cantidadDeSemillasRegistradas; $index++) {



	if (isset($datosSemillas["cod_plantacion_" . $index]) && $datosSemillas["producto_activo_" . $index] == 1) {
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
		$DB_INV->inv_actulizar_line_item(
			$datosSemillas["cod_inventario_" . $index], //$cod_inventario,
			$datosSemillas["cod_plantacion_" . $index], //$cod_plantacion,
			$datosSemillas["numero_orden_planta_" . $index], //$numero_orden,
			$datosSemillas["item_planta_" . $index]
		);
	}
}

echo utf8_encode($result);
// echo $datosPlantacion[0]['total'] . " </> " . $datosSemillas["cantidad_de_plantas_0"] . " </> " . $datosMovimientoTrasplante[0]["cantidad_sobrante"];
// echo 	$cod_trasplante . " </> " .
// 	$datosSemillas["cod_plantacion_0"] . " </> " .
// 	$datosSemillas["cod_inventario_0"];
// echo utf8_encode("cod_info_empresa: ".$cod_info_empresa);

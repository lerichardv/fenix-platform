<?PHP
/*
 * Listado de todas las semillas registradas como completadas, que se han plantado en bloques.
 * @author      Edwin Olivera
 * @date        2024-04-21
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	// header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();
$codigos_registros = trim($_POST["x1"]);

$cod_unificacion 				= trim($_POST["x2"]);
$cod_semilla 					= trim($_POST["x3"]);
$cod_bloque_implementado 		= trim($_POST["x4"]);
$edad 							= trim($_POST["x5"]);
$cod_farm 						= trim($_POST["x6"]);
$codigos_bloque 				= trim($_POST["x7"]);
$codigos_bloque_implementado 	= trim($_POST["x8"]);
// $cod_unificacion, $cod_semilla, $cod_bloque_implementado

$data = [];
$idSemillaPendiente = 0;
$cantidadSemilasPendiente = 1;
if ($codigos_registros > 0) {
	$SEMILLAS = $DB_FARM->farm_listado_semillas_registradas_completadas($codigos_registros);
	//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
	foreach ($SEMILLAS as &$semilla) {
		$semilla["completada"] = 1;
		$data[] = array_map('utf8_encode', $semilla);
	}

	$SEMILLAS_EN_PLANTACION = $DB_FARM->farm_listado_datos_semillas_en_plantacion(
		$cod_semilla,
		$edad,
		$cod_farm,
		$semilla["cod_bloque"]
	);
	if (isset($SEMILLAS_EN_PLANTACION) && count($SEMILLAS_EN_PLANTACION)) {
		$idSemillaPendiente = $SEMILLAS_EN_PLANTACION[0]["cod_semilla_bloque"];

		foreach ($SEMILLAS_EN_PLANTACION as &$semilla_en_plantacion) {
			if ($cantidadSemilasPendiente > 1) {
				if ($idSemillaPendiente != $semilla_en_plantacion["cod_semilla_bloque"]) {
					$idSemillaPendiente = $semilla_en_plantacion["cod_semilla_bloque"];
					$semilla_en_plantacion["completada"] = 0;
					$data[] = array_map('utf8_encode', $semilla_en_plantacion);
				}
			} else {
				$idSemillaPendiente = $semilla_en_plantacion["cod_semilla_bloque"];
				$semilla_en_plantacion["completada"] = 0;
				$data[] = array_map('utf8_encode', $semilla_en_plantacion);
			}
			$cantidadSemilasPendiente++;
		}
		$cantidadSemilasPendiente = 1;
	}
} else {
	$SEMILLAS_EN_PLANTACION = $DB_FARM->farm_listado_datos_semillas_en_plantacion_en_proceso(
		$cod_unificacion,
		$cod_semilla,
		$cod_bloque_implementado,
		$edad,
		$cod_farm,
		$codigos_bloque,
		$codigos_bloque_implementado
	);
	// echo "cod_unificacion: " . $cod_unificacion . ", cod_semilla: " . $cod_semilla . ", cod_bloque_implementado: " . $cod_bloque_implementado . ", edad:" . $edad;
	// echo json_encode($SEMILLAS_EN_PLANTACION);
	// //1020,1021
	// die;
	if (isset($SEMILLAS_EN_PLANTACION) && count($SEMILLAS_EN_PLANTACION)) {
		$idSemillaPendiente = $SEMILLAS_EN_PLANTACION[0]["cod_semilla_bloque"];

		foreach ($SEMILLAS_EN_PLANTACION as &$semilla_en_plantacion) {
			if ($cantidadSemilasPendiente > 1) {
				if ($idSemillaPendiente != $semilla_en_plantacion["cod_semilla_bloque"]) {
					$idSemillaPendiente = $semilla_en_plantacion["cod_semilla_bloque"];
					$semilla_en_plantacion["completada"] = 0;
					$data[] = array_map('utf8_encode', $semilla_en_plantacion);
				}
			} else {
				$idSemillaPendiente = $semilla_en_plantacion["cod_semilla_bloque"];
				$semilla_en_plantacion["completada"] = 0;
				$data[] = array_map('utf8_encode', $semilla_en_plantacion);
			}
			$cantidadSemilasPendiente++;
		}
		$cantidadSemilasPendiente = 1;
	}
	if (isset($SEMILLAS_EN_PLANTACION) && count($SEMILLAS_EN_PLANTACION)) {
		$cantidadSemilasPendiente = 1;

		$idSemillaPendiente = $SEMILLAS_EN_PLANTACION[0]["cod_semilla_bloque"];

		foreach ($SEMILLAS_EN_PLANTACION as &$semilla_en_plantacion) {
			if ($cantidadSemilasPendiente > 1) {
				if ($idSemillaPendiente != $semilla_en_plantacion["cod_semilla_bloque"]) {
					$idSemillaPendiente = $semilla_en_plantacion["cod_semilla_bloque"];
					$SEMILLAS_EN_PLANTACION_COMPLETADA = $DB_FARM->farm_lista_semillas_completadas_asociadas_a_bloques_no_completadas(
						$semilla_en_plantacion["cod_unificacion"],
						$semilla_en_plantacion["cod_inventario"],
						$semilla_en_plantacion["cod_bloque_implementado"],
						$semilla_en_plantacion["edad"],
						$semilla_en_plantacion["cod_farm"]
					);
					foreach ($SEMILLAS_EN_PLANTACION_COMPLETADA as &$semila_completada) {
						$semila_completada["completada"] = 1;
						$data[] = array_map('utf8_encode', $semila_completada);
					}
				}
			} else {
				$SEMILLAS_EN_PLANTACION_COMPLETADA = $DB_FARM->farm_lista_semillas_completadas_asociadas_a_bloques_no_completadas(
					$semilla_en_plantacion["cod_unificacion"],
					$semilla_en_plantacion["cod_inventario"],
					$semilla_en_plantacion["cod_bloque_implementado"],
					$semilla_en_plantacion["edad"],
					$semilla_en_plantacion["cod_farm"]
				);
				foreach ($SEMILLAS_EN_PLANTACION_COMPLETADA as &$semila_completada) {
					$semila_completada["completada"] = 1;
					$data[] = array_map('utf8_encode', $semila_completada);
				}
			}
			$cantidadSemilasPendiente++;
		}
		$cantidadSemilasPendiente = 1;
	}
}
echo json_encode($data);

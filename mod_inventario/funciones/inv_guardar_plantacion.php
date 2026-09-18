<?PHP
/*
 * Guarda una plantación.
 * @author      Edwin Olivera
 * @update      2023-09-26
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	// header('Location: index.php'); //Descomentar cuando se pase a Producción
}


/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();



$numero_orden				= trim(($_POST['x1']));
$cod_info_empresa  			= $_POST['x2'];
$fecha_orden  				= $_POST['x3'];
$datosSemillas				= $_POST['x4'];
$cantidadDeSemillasRegistradas		= $_POST['x5'];
$cod_estados				= $_POST['x6'];



$cantidadSemillas[0]["cantidad_semilla"] = 0;
$fecha_sumar_restar = date("Y-m-d H:i:s");
$varAlgo = [];
if ($fecha_orden != NULL) {
	$fecha_orden = DateTime::createFromFormat("m-d-Y", $fecha_orden);
	$fecha_orden = $fecha_orden->format('Y-m-d');
}

for ($index = 0; $index <  $cantidadDeSemillasRegistradas; $index++) {

	if (isset($datosSemillas["cod_inventario_semilla_" . $index])) {

		if ($datosSemillas["fecha_inicial_" . $index] != NULL) {
			$datosSemillas["fecha_inicial_" . $index] = DateTime::createFromFormat("m-d-Y", $datosSemillas["fecha_inicial_" . $index]);
			$datosSemillas["fecha_inicial_" . $index] = $datosSemillas["fecha_inicial_" . $index]->format('Y-m-d');
		}
		if ($datosSemillas["fecha_final_" . $index] != NULL) {
			$datosSemillas["fecha_final_" . $index] = DateTime::createFromFormat("m-d-Y", $datosSemillas["fecha_final_" . $index]);
			$datosSemillas["fecha_final_" . $index] = $datosSemillas["fecha_final_" . $index]->format('Y-m-d');
		}
		if (isset($datosSemillas["cod_inventario_semilla_" . $index]) && $datosSemillas["producto_activo_" . $index] == 1) {
			$result = $DB_INV->inv_guardar_plantacion(
				$datosSemillas["cod_inventario_semilla_" . $index], // $cod_inventario,
				$numero_orden,
				$cod_info_empresa,
				$datosSemillas["cantidad_semilla_" . $index], //$cantidad,
				(int)$datosSemillas["overseed_semilla_" . $index], //$overseed,
				$datosSemillas["total_semilla_" . $index], //$total,
				$datosSemillas["cantidad_por_plantacion_semillas_" . $index], //$per_planting,
				$fecha_orden,
				$datosSemillas["item_semilla_" . $index], //número de  ÍTEM
				$datosSemillas["fecha_inicial_" . $index], //$fecha_inicial,
				$datosSemillas["fecha_final_" . $index], //$fecha_final,
				$datosSemillas["edad_" . $index], // Ahora se agrego para que se pueda asignar o editar la edad directamente,
				$cod_estados,
				$_SESSION['cod_usuario']
			);

			//Se tiene activo la reducción de cantidad del inventario
			if ($datosSemillas["habilitar_reducir_de_inventario_" . $index] == 1) {

				// Buscamos la cantidad actual de la semilla de la nueva plantación
				$cantidadSemillas = $DB_INV->inv_buscar_cantidad_semillas(
					$datosSemillas["cod_inventario_semilla_" . $index]
				);
				if (isset($cantidadSemillas[0]["cantidad_semilla"])) {
					// Se reduce la cantidad de semillas disponibles.
					$DB_INV->inv_reducir_cantidad_semillas(
						$datosSemillas["cod_inventario_semilla_" . $index],
						$datosSemillas["cantidad_semilla_" . $index]
					);
					$varAlgo =	$DB_INV->inv_bitacora_de_movimientos_de_cantidad_de_semillas(
						$datosSemillas["cod_inventario_semilla_" . $index],
						$fecha_sumar_restar,
						0,
						$datosSemillas["cantidad_semilla_" . $index],
						"New Planting - Order number " . $numero_orden,
						$_SESSION['cod_usuario']
					);


					// Reducimo y consolidamos la cantidad en las 2 tablas respectivas
					$cod_inventario_encontrado = $DB_INV->inv_buscar_cod_inventario_en_inventario_semilla_empresa(
						$datosSemillas["cod_inventario_semilla_" . $index],
						$cod_info_empresa
					);
					$codigo_inventario_semilla = $cod_inventario_encontrado[0]["cod_inventario"];

					$DB_INV->inv_actualizar_semilla_por_empresa(
						$codigo_inventario_semilla,
						$datosSemillas["cantidad_semilla_" . $index] * (-1)
					);
					$result = $DB_INV->inv_datos_semilla_de_empresa_por_codigo_de_inventario(
						$codigo_inventario_semilla
					);

					$sumatoria_semillas = $DB_INV->inv_cantidad_total_semilla_por_cod_inventario_semilla($result[0]["cod_inventario_semilla"]);
					$result = $DB_INV->inv_actualizar_cantidad_semilla_consolidad($result[0]["cod_inventario_semilla"], $sumatoria_semillas[0]["cantidad_semilla_consolidad"]);
				}
			}
		}
	}
}


// Comentamos esta sección cuando se decidió que la edad se va a asignar manualmente en la UI
// Queda para futuro en caso que decidan que vuelva a ser automático
// Este mismo algoritmo se encuentra en inv_actualizar_plantacion.php y inv_guardar_plantacion.php
// Comentado por: lerichard
//PROCESO PARA CALCULAR LA EDAD DE LA PLANTACIÓN RECIEN REGISTRADA
// $plantacionesRegistradas = $DB_INV->inv_buscar_plantacion_por_numero_orden($numero_orden);

// if (isset($plantacionesRegistradas) && !empty($plantacionesRegistradas)) {
// 	// Contador de antigüedad
// 	$edad = 1;

// 	$numeroOrdenActual = $plantacionesRegistradas[0]["numero_orden"];

// 	$codigoSemillaActual = $plantacionesRegistradas[0]["cod_inventario"];

// 	$fechaActual = $plantacionesRegistradas[0]["fecha_final"];

// 	foreach ($plantacionesRegistradas as &$plantacion) {
// 		if ($numeroOrdenActual != $plantacion["numero_orden"]) {
// 			$edad = 1;
// 			$fechaActual = $plantacion["fecha_final"];
// 			$codigoSemillaActual = $plantacion["cod_inventario"];
// 			$numeroOrdenActual = $plantacion["numero_orden"];

// 		} elseif ($codigoSemillaActual != $plantacion["cod_inventario"]) {
// 			$edad = 1;
// 			$fechaActual = $plantacion["fecha_final"];
// 			$codigoSemillaActual = $plantacion["cod_inventario"];

// 		} elseif ($fechaActual != $plantacion["fecha_final"]) {
// 			$edad++;
// 			$fechaActual = $plantacion["fecha_final"];
// 		}

// 		$plantacion['edad'] = $edad;
// 	}
// 	foreach ($plantacionesRegistradas as &$plantacion) {
// 		$plantacion["cod_plantacion"];
// 		$plantacion["edad"];
// 		$DB_INV->inv_guardar_edad_plantacion($plantacion["cod_plantacion"], $plantacion["edad"]);
// 	}
// }


$json_resultado = json_encode($plantacionesRegistradas, JSON_PRETTY_PRINT);


// Imprimir el JSON resultante
// echo $json_resultado;
// echo print_r($datosSemillas);
// die;
echo utf8_encode($result);
// echo json_encode ($varAlgo );

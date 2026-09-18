<?PHP
/*
 * Actualiza una plantación.
 * @author      Edwin Olivera
 * @update      2023-10-01
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



$numero_orden_previo		= trim(($_POST['x0']));
$numero_orden				= trim(($_POST['x1']));
$cod_sembrador  			= $_POST['x2'];
$fecha_orden  				= $_POST['x3'];
$cantidadDeSemillasRegistradas = $_POST['x4'];
$cod_estados				= $_POST['x5'];
$cod_info_empresa 			= $_POST['x6'];
$datosSemillas				= $_POST['x7'];



$puntoDeCorte = "";
$resultProductoRegistrado = 0;
if ($fecha_orden != NULL) {
	$fecha_orden = DateTime::createFromFormat("m-d-Y", $fecha_orden);
	$fecha_orden = $fecha_orden->format('Y-m-d');
}

for ($index = 0; $index < $cantidadDeSemillasRegistradas; $index++) {

	if (isset($datosSemillas["cod_inventario_semilla_" . $index])) {


		if ($datosSemillas["fecha_inicial_" . $index] != NULL) {
			$datosSemillas["fecha_inicial_" . $index] = DateTime::createFromFormat("m-d-Y", $datosSemillas["fecha_inicial_" . $index]);
			$datosSemillas["fecha_inicial_" . $index] = $datosSemillas["fecha_inicial_" . $index]->format('Y-m-d');
		}
		if ($datosSemillas["fecha_final_" . $index] != NULL) {
			$datosSemillas["fecha_final_" . $index] = DateTime::createFromFormat("m-d-Y", $datosSemillas["fecha_final_" . $index]);
			$datosSemillas["fecha_final_" . $index] = $datosSemillas["fecha_final_" . $index]->format('Y-m-d');
		}
		$resultProductoRegistrado = $DB_INV->inv_buscar_producto_en_plantacion($numero_orden_previo, $datosSemillas["cod_inventario_semilla_" . $index], $datosSemillas["cod_plantacion_" . $index]);

		// $resultProductoRegistrado[0]["cod_plantacion"];
		if (isset($resultProductoRegistrado[0]["cantidad"])) {
			if (isset($datosSemillas["cod_inventario_semilla_" . $index]) && $datosSemillas["producto_activo_" . $index] == 1) {


				$resultActualizarPlantacion = $DB_INV->inv_actualizar_plantacion(
					$numero_orden_previo,
					$datosSemillas["cod_plantacion_" . $index],
					$datosSemillas["cod_inventario_semilla_" . $index], // $cod_inventario,
					$numero_orden,
					$cod_sembrador,
					$datosSemillas["cantidad_semilla_" . $index], //$cantidad,
					$datosSemillas["overseed_semilla_" . $index], //$overseed,
					$datosSemillas["total_semilla_" . $index], //$total,
					$datosSemillas["cantidad_por_plantacion_semillas_" . $index], //$per_planting,
					$fecha_orden,
					$datosSemillas["item_semilla_" . $index], //número de  ÍTEM
					$datosSemillas["fecha_inicial_" . $index], //$fecha_inicial,
					$datosSemillas["fecha_final_" . $index], //$fecha_final,
          $datosSemillas["edad_" . $index], // Ahora se agrego para que se pueda asignar o editar la edad directamente,
					$cod_estados
				);
			} else {
				// BORRA UNA FILA ASOCIADA A LA PLANTACIÓN QUE SE ESTA ACTUALIZANDO
				$DB_INV->inv_borrar_plantacion(
					$numero_orden_previo,
					$datosSemillas["cod_inventario_semilla_" . $index],
					$datosSemillas["cod_plantacion_" . $index]
				);
				// echo "cod_plantacion a eliminar: " . $datosSemillas["cod_plantacion_" . $index];
				// die;
			}
			$entro .= " >" . $resultProductoRegistrado[0]["cantidad"];
		} else {


			$puntoDeCorte = json_encode($datosSemillas);
			// echo "Se intento crear una con los siguientes criterios= "
			// 	. "numero_orden_previo: " . $numero_orden_previo
			// 	. ", cod_inventario_semilla_" . $index . ": " . $datosSemillas["cod_inventario_semilla_" . $index]
			// 	. ", cod_plantacion_" . $index . ": " . $datosSemillas["cod_plantacion_" . $index]
			// 	. ", line item" . $index . ": " . $datosSemillas["item_semilla_" . $index];
			// die;
			$resultGuardarNuevaPlantancion = $DB_INV->inv_guardar_plantacion(
				$datosSemillas["cod_inventario_semilla_" . $index], // $cod_inventario,
				$numero_orden,
				$cod_sembrador,
				$datosSemillas["cantidad_semilla_" . $index], //$cantidad,
				$datosSemillas["overseed_semilla_" . $index], //$overseed,
				$datosSemillas["total_semilla_" . $index], //$total,
				$datosSemillas["cantidad_por_plantacion_semillas_" . $index], //$per_planting,
				$fecha_orden,
				$datosSemillas["item_semilla_" . $index], //número de  ÍTEM
				$datosSemillas["fecha_inicial_" . $index], //$fecha_inicial,
				$datosSemillas["fecha_final_" . $index], //$fecha_final,
        $datosSemillas["edad_" . $index], // Ahora se agrego para que se pueda asignar o editar la edad directamente,
				$cod_estados,
				$_SESSION['cod_usuario'],
			);
		}
	}
}

// Comentamos esta sección cuando se decidió que la edad se va a asignar manualmente en la UI
// Queda para futuro en caso que decidan que vuelva a ser automático
// Este mismo algoritmo se encuentra en inv_actualizar_plantacion.php y inv_guardar_plantacion.php
// Comentado por: lerichard
//PROCESO PARA CALCULAR LA EDAD DE LA PLANTACIÓN YA REGISTRADA
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


echo json_encode($resultActualizarPlantacion);


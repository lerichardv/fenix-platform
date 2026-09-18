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

// echo print_r($datosSemillas);
// die;
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
			$entro = "Entro";
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
				$cod_estados,
				$_SESSION['cod_usuario']
			);
		}
	}
}

// echo json_encode($datosSemillas);
// echo $puntoDeCorte;
echo json_encode($resultActualizarPlantacion);
// echo "0|" . json_encode($resultGuardarNuevaPlantancion);
// echo "0|" . $entro;
// echo json_encode($result);

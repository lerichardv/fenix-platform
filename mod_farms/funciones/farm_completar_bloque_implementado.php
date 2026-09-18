<?PHP
/*
 * Limpia y completa los bloques implementados. Por lo que elimina las semillas asociadas.
 * @author      Edwin Olivera
 * @date        2024-04-14
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

$cod_bloque_implementado = trim(($_POST['x1']));
$cod_bloque 			 = trim(($_POST['x2']));

$SEMILLAS_ASOCIADAS = $DB_FARM->farm_listado_de_semillas_asociadas_a_bloques($cod_bloque_implementado);
$todoRegistroExitoso = false;
if (isset($SEMILLAS_ASOCIADAS) && !empty($SEMILLAS_ASOCIADAS)) {

	foreach ($SEMILLAS_ASOCIADAS as $semilla_asociada) {
		try {
			$cod_semilla_bloque 					= $semilla_asociada["cod_semilla_bloque"];
			$cod_bloque_implementado 				= $semilla_asociada["cod_bloque_implementado"];
			$cod_inventario 						= $semilla_asociada["cod_inventario"];
			$cod_unificacion 						= $semilla_asociada["cod_unificacion"];
			$cod_plantacion 						= $semilla_asociada["cod_plantacion"];
			$cod_trasplante 						= $semilla_asociada["cod_trasplante"];
			$cod_bloque 							= $semilla_asociada["cod_bloque"];
			$cod_field 								= $semilla_asociada["cod_field"];
			$cod_farm 								= $semilla_asociada["cod_farm"];
			$acres_usados 							= $semilla_asociada["acres_usados"];
			$porcentaje_acre_usado_en_semilla 		= $semilla_asociada["porcentaje_acre_usado_en_semilla"];
			$fecha_plantacion 						= $semilla_asociada["fecha_plantacion"];
			$user_insert_semilla_asociada 			= $semilla_asociada["user_insert_semilla_asociada"];
			$usuario_registro_semilla 				= $semilla_asociada["usuario_registro_semilla"];
			$date_insert_semilla_asociada 			= $semilla_asociada["date_insert_semilla_asociada"];
			$ini_acres 								= $semilla_asociada["ini_acres"];
			$use_acres 								= $semilla_asociada["use_acres"];
			$acres_disponibles 						= $semilla_asociada["acres_disponibles"];
			$porcentaje_acre_usado 					= $semilla_asociada["porcentaje_acre_usado"];
			$block_creator_user 					= $semilla_asociada["block_creator_user"];
			$block_creation_date 					= $semilla_asociada["block_creation_date"];
			$nombre_semilla 						= $semilla_asociada["nombre_semilla"];
			$abreviatura_semilla 					= $semilla_asociada["abreviatura_semilla"];
			$codigo_semilla 						= $semilla_asociada["codigo_semilla"];
			$numero_orden_plantacion 				= $semilla_asociada["numero_orden_plantacion"];
			$edad_semilla_en_plantacion 			= $semilla_asociada["edad_semilla_en_plantacion"];
			$fecha_inicial_plantacion 				= $semilla_asociada["fecha_inicial_plantacion"];
			$fecha_final_plantacion 				= $semilla_asociada["fecha_final_plantacion"];
			$fecha_de_entrega_plantacion 			= $semilla_asociada["fecha_de_entrega_plantacion"];
			$numero_ticket 							= $semilla_asociada["numero_ticket"];
			$fecha_entrega_trasplante 				= $semilla_asociada["fecha_entrega_trasplante"];
			$fecha_recibo_trasplante 				= $semilla_asociada["fecha_recibo_trasplante"];
			$nombre_bloque_usado 					= $semilla_asociada["nombre_bloque_usado"];
			$cod_temporada 							= $semilla_asociada["cod_temporada"];

			//Creamos el registro permanente de la semilla
			$idCreadoRegistroSemilla = $DB_FARM->farm_crear_registro_permanente_semilla_implementada(
				$cod_semilla_bloque,
				$cod_bloque_implementado,
				$cod_inventario,
				$cod_unificacion,
				$cod_plantacion,
				$cod_trasplante,
				$cod_bloque,
				$cod_field,
				$cod_farm,
				$acres_usados,
				$porcentaje_acre_usado_en_semilla,
				$fecha_plantacion,
				$user_insert_semilla_asociada,
				$usuario_registro_semilla,
				$date_insert_semilla_asociada,
				$ini_acres,
				$use_acres,
				$acres_disponibles,
				$porcentaje_acre_usado,
				$block_creator_user,
				$block_creation_date,
				$nombre_semilla,
				$abreviatura_semilla,
				$codigo_semilla,
				$numero_orden_plantacion,
				$edad_semilla_en_plantacion,
				$fecha_inicial_plantacion,
				$fecha_final_plantacion,
				$fecha_de_entrega_plantacion,
				$numero_ticket,
				$fecha_entrega_trasplante,
				$fecha_recibo_trasplante,
				$nombre_bloque_usado,
				$cod_temporada,
				$_SESSION['cod_usuario'] ?? 1

			);

			if (isset($idCreadoRegistroSemilla)  && $idCreadoRegistroSemilla != null && $idCreadoRegistroSemilla != 0) {

				$DB_FARM->farm_actualizar_unificacion_semilla_bloque($cod_unificacion);
				$DB_FARM->farm_actualizar_estado_de_implementado_unificacion_semilla_bloque($cod_unificacion);

				$DB_FARM->farm_eliminar_semilla_de_plantacion($cod_semilla_bloque);

				$DB_FARM->farm_incrementar_acres_disponibles_en_bloque_implementado(
					$cod_bloque_implementado,
					$acres_usados,
					$porcentaje_acre_usado_en_semilla
				);

				$DB_FARM->farm_marcar_trasplante_como_implementado($cod_trasplante);

				$todoRegistroExitoso = true;
			}
			//code...
		} catch (\Throwable $th) {
			//throw $th;
			$todoRegistroExitoso = false;
			echo "1|Failed to mark block as completed";
			die();
		}
	}

} else {
	echo "1|The block has no associated seeds";
	die();
}

if ($todoRegistroExitoso) {
	// TODO: actualizar cantidad de acres asociadas al bloque
	$datosDeBloque = $DB_FARM->farm_buscar_datos_bloque(
		$cod_bloque
	);
	if (isset($datosDeBloque) && !empty($datosDeBloque)) {
		$acres_originales =	$datosDeBloque[0]["acres_originales"];

		$DB_FARM->farm_actualizar_cantidad_acres_en_bloque_implementado(
			$cod_bloque_implementado,
			$acres_originales
		);
	}
	echo "0|The current block has been marked as completed.|" . $idCreadoRegistroSemilla;
} else {
	echo "1|It has been partially marked";
	die();
}

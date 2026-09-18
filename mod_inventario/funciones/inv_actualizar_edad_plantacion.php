<?PHP
/*
 * Actuliza la edad de todos los registro de las plantanciones existentes.
 * @author      Edwin Olivera
 * @update      2024-03-10
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
// if (!isset($_SESSION['cod_usuario'])) {
// 	header('Location: index.php');
// }


/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();


//PROCESO PARA CALCULAR LA EDAD DE LA PLANTACIÓN YA REGISTRADA

$plantacionesRegistradas = $DB_INV->inv_listado_plantacion_para_actualizar_edad();

if (isset($plantacionesRegistradas) && !empty($plantacionesRegistradas)) {
	// Contador de antigüedad
	$edad = 1;

	$numeroOrdenActual = $plantacionesRegistradas[0]["numero_orden"];

	$codigoSemillaActual = $plantacionesRegistradas[0]["cod_inventario"];

	$fechaActual = $plantacionesRegistradas[0]["fecha_final"];

	foreach ($plantacionesRegistradas as &$plantacion) {
		if ($numeroOrdenActual != $plantacion["numero_orden"]) {
			$edad = 1;
			$fechaActual = $plantacion["fecha_final"];
			$codigoSemillaActual = $plantacion["cod_inventario"];
			$numeroOrdenActual = $plantacion["numero_orden"];
		} elseif ($codigoSemillaActual != $plantacion["cod_inventario"]) {
			$edad = 1;
			$fechaActual = $plantacion["fecha_final"];
			$codigoSemillaActual = $plantacion["cod_inventario"];
		} elseif ($fechaActual != $plantacion["fecha_final"]) {
			$edad++;
			$fechaActual = $plantacion["fecha_final"];
		}

		$plantacion['edad'] = $edad;
	}
	foreach ($plantacionesRegistradas as &$plantacion) {
		$DB_INV->inv_guardar_edad_plantacion($plantacion["cod_plantacion"], $plantacion["edad"]);
	}
}





// Actualizar los registros permanentementes en la base de datos
if (isset($plantacionesRegistradas) && !empty($plantacionesRegistradas)) {
	foreach ($plantacionesRegistradas as &$plantacion) {
		$DB_INV->inv_guardar_edad_plantacion_en_registro_permanente($plantacion["cod_plantacion"], $plantacion["edad"]);
	}
}

$json_resultado = json_encode($plantacionesRegistradas, JSON_PRETTY_PRINT);

// Imprimir el JSON resultante
echo "$json_resultado";
die;

<?PHP
/*
 * Crea o actualiza un registro de una Plantación en las granjas.
 * @author      Edwin Olivera
 * @date        2024-02-17
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();

$codigo_granjas_plantacion	= $_POST['x1'];
$numero_orden = trim($_POST["x2"]);
$cod_semilla = trim($_POST["x3"]);
$planting_date = trim($_POST["x4"]);
$cod_estado = trim($_POST["x5"]);
$cod_granja = trim($_POST["x6"]);
$codigos_campos = $_POST["x7"];
$codigos_bloques = $_POST["x8"];
$cod_temporada = trim($_POST["x9"]);
$nombre_plantacion_granja = trim(utf8_decode($_POST["x10"]));

$datosBloques = $_POST["x11"];
$arrIndicesBloque = $_POST["x12"];
$indiceBloque = $_POST["x13"];
$numero_orden_ticket = $_POST["x14"];
$cod_field = 1;
foreach ($codigos_campos as $cod_campo) {
	$cod_field = $cod_campo;
	var_dump($cod_campo);
}
foreach ($codigos_bloques as $cod_bloque) {
	// var_dump($cod_bloque);
}
$numero_orden_extraido = explode(" - ", $numero_orden_ticket)[0];
$numero_ticket_extraido = explode(" - ", $numero_orden_ticket)[1];
$datos_trasplante = $DB_FARM->farm_buscar_datos_trasplantes($numero_orden_extraido, $numero_ticket_extraido, $cod_semilla);
$cod_plantacion = $datos_trasplante[0]["cod_plantacion"];
$cod_trasplante = $datos_trasplante[0]["cod_trasplante"];

$datos_campo = $DB_FARM->farm_buscar_datos_campo($cod_field);
$cod_farm = $datos_campo[0]["cod_farm"];

// foreach ($datosBloques as $bloque) {
// 	$cod_bloque = $bloque["cod_bloque"];
// 	$datos_bloque = $DB_FARM->farm_buscar_datos_bloque($cod_bloque);
// 	var_dump($datos_bloque);
// }
// var_dump($cod_farm);

// die;
//03-04-2024 a 2023-08-31

$fecha_original = $planting_date;
$fecha = DateTime::createFromFormat('d-m-Y', $fecha_original);
$fecha_formateada = $fecha->format('Y-m-d');


if ($codigo_granjas_plantacion > 0) {
	// $result = $DB_FARM->farm_actualizar_plantaciones_granja(
	// 	$codigo_granjas_plantacion, //$cod_rotations,
	// 	$cod_plantacion, //$cod_plantacion,
	// 	$cod_transplante, //$cod_transplante,
	// 	$cod_field, //$cod_field,
	// 	$cod_semilla, //$cod_semilla,
	// 	$cod_farm, //$cod_farm,
	// 	$cod_temporada, //$cod_temporada,
	// 	$planting_date, //$planting_date,
	// 	$nombre_plantacion_granja, //$comentarios,
	// 	0 //$completado
	// );
	//	echo "Adentro";
} else {
	//echo $fecha_formateada;
	$result = $DB_FARM->farm_crear_plantaciones_granja(
		$cod_plantacion,
		$cod_trasplante,
		$cod_field,
		$cod_semilla,
		$cod_farm,
		$cod_temporada,
		$fecha_formateada,
		$nombre_plantacion_granja,
		$_SESSION['cod_usuario']
	);
	$partesRespuesta = explode("|", $result);
	$codigoRespuesta = $partesRespuesta[0];

	if ($codigoRespuesta == 0) {
		$cod_rotations_nuevo = $partesRespuesta[2];
		foreach ($datosBloques as $bloque) {
			$cod_bloque = $bloque["cod_bloque"];
			$datos_bloque = $DB_FARM->farm_buscar_datos_bloque($cod_bloque);
			$result = $DB_FARM->farm_crear_registro_bloques_en_plantaciones_granja(
				$cod_rotations_nuevo,
				$datos_bloque[0]["cod_farm"],
				$datos_bloque[0]["cod_field"],
				$cod_bloque,
				$datos_bloque[0]["bloque"],
				$datos_bloque[0]["acres_originales"],
				$bloque["acres_por_usar"],
				$datos_bloque[0]["acres_originales"] - $bloque["acres_por_usar"], //$pra_acres,
				$datos_bloque[0]["acres_originales"] - $bloque["acres_por_usar"], //$teo_acres,
				$_SESSION['cod_usuario']
			);
			// var_dump($datos_bloque);
		}
	}
}
// $result = $DB_FARM->inv_guardar_grupo_de_siembra(
// 	$codigo_temporada,
// 	$nombre,
// 	$nota,
// 	$activo,
// 	$_SESSION['cod_usuario']
// );
echo $result;

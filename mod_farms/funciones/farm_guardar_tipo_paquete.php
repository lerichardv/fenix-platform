<?PHP
/*
 * Crea o actualiza un registro de un tipo de paquete.
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

$codigo_tipo_paquete	= $_POST['x1'];
$cod_granja = trim($_POST["x2"]);
$cods_localizaciones = $_POST["x3"];
$activo = trim($_POST["x4"]);
$nombre_tipo_bloque = trim($_POST["x5"]);
$cods_categorias =  $_POST["x6"];
$precio_pieza =  $_POST["x7"];



if ($codigo_tipo_paquete > 0) {

	$datosTipoPaquete = $DB_FARM->farm_obtener_datos_un_tipo_paquete_especifico($codigo_tipo_paquete, $cod_granja);

	$cods_categorias_regstradas_previamente = $datosTipoPaquete[0]['cod_categoria'];
	$cods_categorias_regstradas_previamente = explode(",", $cods_categorias_regstradas_previamente);


	$cods_lacaciones_regstradas_previamente = $datosTipoPaquete[0]['cod_location'];
	$cods_lacaciones_regstradas_previamente = explode(",", $cods_lacaciones_regstradas_previamente);
	// Comprobamos si se ha eliminado alguna categoria
	foreach ($cods_categorias_regstradas_previamente as $cod_categoria_registrada_previamente) {
		if (!in_array($cod_categoria_registrada_previamente, $cods_categorias)) {
			$result = $DB_FARM->farm_eliminar_categoria_vinculada_tipo_paquete($codigo_tipo_paquete, $cod_granja, $cod_categoria_registrada_previamente);
		}
	}

	// Comprobamos si se ha eliminado alguna localizacion
	foreach ($cods_lacaciones_regstradas_previamente as $cod_lacacion_registrada_previamente) {
		if (!in_array($cod_lacacion_registrada_previamente, $cods_localizaciones)) {
			$result = $DB_FARM->farm_eliminar_lacacion_vinculada_tipo_paquete($codigo_tipo_paquete, $cod_granja, $cod_lacacion_registrada_previamente);
		}
	}

	// Comprobamos si se ha agregado alguna categoria
	foreach ($cods_categorias as $cod_categoria) {
		if (!in_array($cod_categoria, $cods_categorias_regstradas_previamente)) {
			foreach ($cods_localizaciones as $cod_localizacion) {
				// $cod_bloque = $cod_localizacion;
				// $datos_bloque = $DB_FARM->farm_buscar_datos_bloque($cod_bloque);
				$result = $DB_FARM->farm_crear_vinculaciones_con_tipo_paquete(
					$codigo_tipo_paquete,
					$cod_granja,
					$cod_localizacion,
					$cod_categoria,
					1
				);
			}
		}
	}

	// Comprobamos si se ha agregado alguna localizacion
	foreach ($cods_localizaciones as $cod_localizacion) {
		if (!in_array($cod_localizacion, $cods_lacaciones_regstradas_previamente)) {
			foreach ($cods_categorias as $cod_categoria) {
				$result = $DB_FARM->farm_crear_vinculaciones_con_tipo_paquete(
					$codigo_tipo_paquete,
					$cod_granja,
					$cod_localizacion,
					$cod_categoria,
					1
				);
			}
		}
	}
	$result = $DB_FARM->farm_actualizar_tipo_paquete(
		$codigo_tipo_paquete,
		1,//$cantidad,
		$nombre_tipo_bloque,
    $precio_pieza,
	);
} else {
	//echo $fecha_formateada;
	$result = $DB_FARM->farm_crear_tipo_paquete(
		$nombre_tipo_bloque,
		$activo,
		$_SESSION['cod_usuario'],
		$precio_pieza
	);
	$partesRespuesta = explode("|", $result);
	$codigoRespuesta = $partesRespuesta[0];

	if ($codigoRespuesta == 0) {
		$codigo_tipo_paquete_nuevo = $partesRespuesta[2];
		//Siclo para recorrer las localizaciones y categorias para vincularlas con el tipo de paquete
		foreach ($cods_categorias as $cod_categoria) {
			foreach ($cods_localizaciones as $cod_localizacion) {
				// $cod_bloque = $cod_localizacion;
				// $datos_bloque = $DB_FARM->farm_buscar_datos_bloque($cod_bloque);
				$result = $DB_FARM->farm_crear_vinculaciones_con_tipo_paquete(
					$codigo_tipo_paquete_nuevo,
					$cod_granja,
					$cod_localizacion,
					$cod_categoria,
					0
				);
				// var_dump($datos_bloque);
			}
		}
	}
}

echo $result;

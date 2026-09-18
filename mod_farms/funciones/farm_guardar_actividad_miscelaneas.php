<?PHP
/*
 * Crea o actualiza un registro de una actividad miscelaneas.
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

$codigo_actividad_miscelanea	= $_POST['x1'];
$cod_granja = trim($_POST["x2"]);
$cods_localizaciones = $_POST["x3"];
$activo = trim($_POST["x4"]);
$codigo_identificacion_actividad = trim($_POST["x5"]);
$nombre_actividad =  $_POST["x6"];
$precio_pieza =  $_POST["x7"];



if ($codigo_actividad_miscelanea > 0) {
	// echo $codigo_actividad_miscelanea;
	// echo $codigo_identificacion_actividad;
	// echo $codigo_identificacion_actividad;
	// die();
	$result = $DB_FARM->farm_actualizar_actividad_miscelanea(
		$codigo_actividad_miscelanea,
		$cod_granja,
		$cods_localizaciones,
		$codigo_identificacion_actividad,
		$nombre_actividad,
		$precio_pieza
	);
} else {
	foreach ($cods_localizaciones as $cod_localizacion) {
		$result = $DB_FARM->farm_crear_actividad_miscelaneas(
			$cod_granja,
			$cod_localizacion,
			$codigo_identificacion_actividad,
			$nombre_actividad,
			$precio_pieza,
			$_SESSION['cod_usuario']
		);
		// var_dump($datos_bloque);
	}
}

echo $result;

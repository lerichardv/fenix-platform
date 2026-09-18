<?PHP
/*
 * Crea o actualiza un registro de una locación.
 * @author      Edwin Olivera
 * @date        2024-09-29
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

$codigo_locacion	= $_POST['x1'];
$cods_granjas 		= $_POST["x2"];
$abreviacion 		= trim($_POST["x3"]);
$activo 			= trim($_POST["x4"]);
$nombre_locacion 	= trim($_POST["x5"]);
$cost_center 	= trim($_POST["x6"]);
$labor_phase 	= trim($_POST["x7"]);




if ($codigo_locacion > 0) {

	$result = $DB_FARM->farm_actualizar_locacion(
		$cods_granjas,
		$nombre_locacion,
		$abreviacion,
		$codigo_locacion,
    $cost_center,
    $labor_phase
	);
} else {
	foreach ($cods_granjas as $cod_granja) {
		$result = $DB_FARM->farm_crear_locacion(
			$cod_granja,
			$nombre_locacion,
			$abreviacion,
			$_SESSION['cod_usuario']
		);
	}
}

echo $result;

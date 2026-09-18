<?PHP
/*
 * Listado de locaciones fisicas en una granja.
 * @author      Edwin Olivera
 * @date        2024-02-29
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

try {
	/*INSTANCIAMIENTOS*/
	$DB_FARM 	= new db_farms();
	$cod_farm = $_POST["x1"];
	// $cod_farm = implode(',', $cod_farm);
	// echo   ($cod_farm);
	// die();
	if (is_array($cod_farm)) {
		$cod_farm = implode(',', $cod_farm);
		$LOCACIONES = $DB_FARM->farm_listado_localizaciones_en_diferentes_granjas($cod_farm);

	} else {
		$LOCACIONES = $DB_FARM->farm_listado_localizaciones_en_granja($cod_farm);
	}
	
	
	
	//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
	foreach ($LOCACIONES as $locacion) {
		$data[] = array_map('utf8_encode', $locacion);
	}
	// echo  json_encode($_POST["x1"]);
	echo json_encode($data);
} catch (Exception $e) {
	// echo 'Error: ' . $e->getMessage();
	throw new Exception('An error occurred while fetching the locations by farms. Error: ' . $e->getMessage());
}

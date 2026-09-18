<?PHP
/*
 * Listado de campos asociados a un estado o una granja especifica.
 * @author      Edwin Olivera
 * @date        2024-02-28
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
$cod_estado = $_POST["x1"];
$cod_granja = $_POST["x2"];
if ($cod_estado != '-b' && $cod_granja != '-b' && $cod_estado != '' && $cod_granja != '') {

	$CAMPOS = $DB_FARM->farm_listado_campos_por_estados_granjas($cod_estado, $cod_granja);

	//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
	foreach ($CAMPOS as $campo) {
		$data[] = array_map('utf8_encode', $campo);
	}
	echo json_encode($data);
} else {
	echo json_encode([]);
}

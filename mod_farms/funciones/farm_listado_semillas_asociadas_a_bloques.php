<?PHP
/*
 * Listado de todas las semillas asociadas a un bloque en las plantaciones.
 * @author      Edwin Olivera
 * @date        2024-04-04
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	// header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();
$cod_bloque = trim($_POST["x1"]);

$SEMILLAS = $DB_FARM->farm_listado_semillas_asociadas_a_bloques($cod_bloque);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($SEMILLAS as $semilla){
	$data[]=array_map('utf8_encode', $semilla);
}
echo json_encode($data);
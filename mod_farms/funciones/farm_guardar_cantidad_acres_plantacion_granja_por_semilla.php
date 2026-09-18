<?PHP
/*
 * Guarda la cantidad de acres ingresados en la pantalla de Plantaciones en granjas.
 * @author      Edwin Olivera
 * @date        2024-03-05
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	echo "1|Expired Session|";
	die();
	// header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();

$cod_semila_bloque 		= trim(($_POST['x1']));
$cantidad_acres  	= $_POST['x2'];
$porcentaje_acres  	= $_POST['x3'];

$result = $DB_FARM->farm_guardar_cantidad_acres_en_plantacion_granja_por_semilla(
	$cod_semila_bloque,
	$cantidad_acres,
	$porcentaje_acres
);
echo $result;

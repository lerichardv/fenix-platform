<?PHP
/*
 * Guarda una fecha ingresada en el listado de las plantaciones hechas en la pantalla de Plantaciones en granjas.
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

$cod_rotations 		= trim(($_POST['x1']));
$fecha_ingresada  	= trim(($_POST['x2']));

$result = $DB_FARM->farm_guardar_fecha_seleccionada_plantacion_granja(
	$cod_rotations,
	$fecha_ingresada
);
echo $result;

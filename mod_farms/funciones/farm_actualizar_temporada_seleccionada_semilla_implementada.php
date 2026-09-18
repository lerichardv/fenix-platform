<?PHP
/*
 * Actualiza la temporada seleccionada en un semilla implementada.
 * @author      Edwin Olivera
 * @date        2024-04-29
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

$cod_temporada 		 = trim(($_POST['x1']));
$cod_semilla_bloque  = trim(($_POST['x2']));

$result = $DB_FARM->farm_actualizar_temporada_seleccionada_semilla_implementada(
	$cod_semilla_bloque,
	$cod_temporada
);
echo $result;

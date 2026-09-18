<?PHP
/*
 * Cambia el estado de activacion de un campo especifico.
 * @author      Edwin Olivera
 * @date        2024-02-05
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$cod_bloque 		= trim(($_POST['x1']));
$cantidad_acres  	= trim(($_POST['x2']));

$result = $DB_INV->inv_guardar_cantidad_acres_actuales(
	$cod_bloque,
	$cantidad_acres
);
echo utf8_encode($result);

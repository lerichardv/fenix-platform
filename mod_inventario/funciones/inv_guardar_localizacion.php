<?PHP
/*
 * Guarda o actualiza una localización.
 * @author      Edwin Olivera
 * @date        2023-10-08
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

$codigo_localizacion	= $_POST['x1'];
$nombre			= trim(utf8_decode($_POST['x2']));
$abreviacion 	= trim(utf8_decode($_POST['x3']));
$descripcion 	= trim(utf8_decode($_POST['x4']));
$activo			= $_POST['x5'];

$result = $DB_INV->inv_guardar_localizacion(
	$codigo_localizacion,
	$nombre,
	$abreviacion,
	$descripcion,
	$activo,
	$_SESSION['cod_usuario']
);
echo utf8_encode($result[0]['mensaje']);
// print_r ($result);
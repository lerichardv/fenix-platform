<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Edwin Olivera
 * @date        2023-09-11
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

$codigo_categoria	= $_POST['x1'];
$nombre				= trim(utf8_decode($_POST['x2']));
$descripcion 		= trim(utf8_decode($_POST['x3']));
$activo				= $_POST['x4'];

$result = $DB_INV->inv_guardar_categoria_semillas(
	$codigo_categoria,
	$nombre,
	$descripcion,
	$activo,
	$_SESSION['cod_usuario']
);
echo utf8_encode($result[0]['mensaje']);
// print_r ($result);
<?PHP
/*
 * Crea o actualiza un registro de un campo.
 * @author      Edwin Olivera
 * @date        2023-02-05
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

$codigo_campo	= $_POST['x1'];
$nombre			= trim(utf8_decode($_POST['x2']));
$cod_farm 		= trim(utf8_decode($_POST['x3']));
$activo			= $_POST['x4'];

if ($codigo_campo > 0) {
	$result = $DB_INV->inv_actualizar_campo(
		$cod_farm,
		$codigo_campo,
		$nombre,
		$activo
	);
} else {
	$result = $DB_INV->inv_crear_campo(
		$cod_farm,
		$nombre,
		$activo,
		$_SESSION['cod_usuario']
	);
}

echo $result;
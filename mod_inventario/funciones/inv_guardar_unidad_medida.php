<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$codigo_unidad_medida	= trim(($_POST['x1']));
$unidad_medida  		= trim(utf8_decode($_POST['x2']));
$abreviatura_medida		= (trim(utf8_decode($_POST['x3'])) == '' ? NULL:trim(utf8_decode($_POST['x3'])));

$result = $DB_INV->inv_guardar_unidad_medida($codigo_unidad_medida,
										$unidad_medida,
										$abreviatura_medida,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

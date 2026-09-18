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

$codigo_inventario_maquinaria	= trim(($_POST['x1']));
$cod_info_empresa  				= trim(utf8_decode($_POST['x2']));
$codigo_maquinaria				= trim(utf8_decode($_POST['x3']));
$nombre_maquinaria 				= trim(utf8_decode($_POST['x4']));
$cod_tipo_aplicacion			= trim(utf8_decode($_POST['x5']));
$precio_unidad		  			= str_replace(',','',trim($_POST['x6']));
$anio_vencimiento				= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));
$cod_estado_plantacion			= trim(utf8_decode($_POST['x8']));

$result = $DB_INV->inv_guardar_inventario_maquinaria($codigo_inventario_maquinaria,
										$cod_info_empresa,
										$codigo_maquinaria,
										$nombre_maquinaria,
										$cod_tipo_aplicacion,
										$precio_unidad,
										$anio_vencimiento,
										$cod_estado_plantacion,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

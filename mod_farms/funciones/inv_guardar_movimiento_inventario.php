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

$codigo_movimiento			= trim(($_POST['x1']));
$cod_info_empresa_envia  	= trim(utf8_decode($_POST['x2']));
$cod_info_empresa_recibe	= trim(utf8_decode($_POST['x3']));
$fecha_envia  				= trim(utf8_decode($_POST['x4']));
$fecha_recibe				= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$cod_tipo_inventario  		= trim(utf8_decode($_POST['x6']));
$cod_inventario  			= trim(utf8_decode($_POST['x7']));
$cod_unidad_medida  		= trim(utf8_decode($_POST['x8']));
$num_lote  					= (trim(utf8_decode($_POST['x9'])) == '' ? NULL:trim(utf8_decode($_POST['x9'])));
$cantidad_enviada			= str_replace(',','',trim($_POST['x10']));
$cantidad_recibida			= (str_replace(',','',trim($_POST['x11'])) == '' ? NULL:str_replace(',','',trim($_POST['x11'])));
$motivo_perdida  			= (trim(utf8_decode($_POST['x12'])) == '' ? NULL:trim(utf8_decode($_POST['x12'])));
$cantidad_perdida			= (str_replace(',','',trim($_POST['x13'])) == '' ? NULL:str_replace(',','',trim($_POST['x13'])));


$fecha_envia = DateTime::createFromFormat("m-d-Y" , $fecha_envia);

$fecha_envia = $fecha_envia->format('Y-m-d');

if($fecha_recibe != NULL)
{
	$fecha_recibe = DateTime::createFromFormat("m-d-Y" , $fecha_recibe);
	$fecha_recibe = $fecha_recibe->format('Y-m-d');
}

$result = $DB_INV->inv_guardar_movimiento_inventario($codigo_movimiento,
										$cod_info_empresa_envia,
										$cod_info_empresa_recibe,
										$fecha_envia,
										$fecha_recibe,
										$cod_tipo_inventario,
										$cod_inventario,
										$cod_unidad_medida,
										$num_lote,
										$cantidad_enviada,
										$cantidad_recibida,
										$motivo_perdida,
										$cantidad_perdida,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

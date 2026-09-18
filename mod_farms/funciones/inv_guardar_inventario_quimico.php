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

$codigo_inventario_quimico		= trim(($_POST['x1']));
$cod_info_empresa  				= trim(utf8_decode($_POST['x2']));
$cod_quimico					= trim(utf8_decode($_POST['x3']));
$nombre_quimico  				= trim(utf8_decode($_POST['x4']));
$cod_unidad_medida				= trim(utf8_decode($_POST['x5']));
$cantidad_quimico  				= str_replace(',','',trim(utf8_decode($_POST['x6'])));
$cantidad_fisica_quimico		= str_replace(',','',trim(utf8_decode($_POST['x7'])));
$precio_quimico  				= (str_replace(',','',trim(utf8_decode($_POST['x8']))) == '' ? NULL:str_replace(',','',trim(utf8_decode($_POST['x8']))));
$cod_ingrediente_activo			= trim(utf8_decode($_POST['x9']));
$registro_ambiental  			= (trim(utf8_decode($_POST['x10'])) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$periodo_reingreso  			= (trim(utf8_decode($_POST['x11'])) == '' ? NULL:trim(utf8_decode($_POST['x11'])));
$cod_tipo_periodo_reingreso  	= (trim(utf8_decode($_POST['x12'])) == '-b' ? NULL:trim(utf8_decode($_POST['x12'])));
$periodo_precosecha				= (trim(utf8_decode($_POST['x13'])) == '' ? NULL:trim(utf8_decode($_POST['x13'])));
$cod_tipo_periodo_precosecha	= (trim(utf8_decode($_POST['x14'])) == '-b' ? NULL:trim(utf8_decode($_POST['x14'])));
$dosis_minima					= str_replace(',','',trim(utf8_decode($_POST['x15'])));
$dosis_maxima  					= str_replace(',','',trim(utf8_decode($_POST['x16'])));
$cod_tipo_quimico				= trim(utf8_decode($_POST['x17']));
$cantidad_minima_alerta  		= str_replace(',','',trim(utf8_decode($_POST['x18'])));
$razon_aplicacion				= trim(utf8_decode($_POST['x19']));
/*$etiqueta						= trim(utf8_decode($_POST['x20']));*/
$ext_adjunto					= trim(utf8_decode($_POST['x21']));
$ext_label						= trim(utf8_decode($_POST['x22']));
$cantidad_sumar 				= str_replace(',','',trim(utf8_decode($_POST['x23'])));
$cantidad_restar 				= str_replace(',','',trim(utf8_decode($_POST['x24'])));
$razon_sumar_restar				= trim(utf8_decode($_POST['x25']));
$fecha_sumar_restar				= trim(($_POST['x26'])) == '' ? NULL:trim(($_POST['x26']));

if($fecha_sumar_restar != NULL)
{
	$fecha_sumar_restar = DateTime::createFromFormat("m-d-Y H:i:s" , $fecha_sumar_restar);
	$fecha_sumar_restar = $fecha_sumar_restar->format('Y-m-d H:i:s');
}

$result = $DB_INV->inv_guardar_inventario_quimico($codigo_inventario_quimico,
										$cod_info_empresa,
										$cod_quimico,
										$nombre_quimico,
										$cod_unidad_medida,
										$cantidad_quimico,
										$cantidad_fisica_quimico,
										$precio_quimico,
										$cod_ingrediente_activo,
										$registro_ambiental,
										$periodo_reingreso,
										($cod_tipo_periodo_reingreso == '-b' ? null:$cod_tipo_periodo_reingreso),
										$periodo_precosecha,
										($cod_tipo_periodo_precosecha == '-b' ? null:$cod_tipo_periodo_precosecha),
										$dosis_minima,
										$dosis_maxima,
										$cod_tipo_quimico,
										$cantidad_minima_alerta,
										$razon_aplicacion,
										$ext_adjunto,
										$ext_label,
										$_SESSION['cod_usuario']);

if($cantidad_sumar > 0 || $cantidad_restar > 0)
{
	$result = $DB_INV->inv_guardar_inventario_quimico_sumar_restar($codigo_inventario_quimico,
										$fecha_sumar_restar,
										$cantidad_sumar,
										$cantidad_restar,
										$razon_sumar_restar,
										$_SESSION['cod_usuario']);
}
echo utf8_encode($result[0]['mensaje']);
?>

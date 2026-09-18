<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$CONTROL = new db_control_calidad();

$codigo_control_calidad		= trim(($_POST['x1']));
$cod_pais  					= trim(utf8_decode($_POST['x2']));
$cod_departamento  			= trim(utf8_decode($_POST['x3']));
$cod_producto  				= trim(utf8_decode($_POST['x4']));
$fecha  					= trim(utf8_decode($_POST['x5']));
$num_orden_compra  			= trim(utf8_decode($_POST['x6']));
$tiempo  					= trim(utf8_decode($_POST['x7']));
$temperatura_actual  		= (trim(utf8_decode($_POST['x8'])) == '' ? NULL:trim(utf8_decode($_POST['x8'])));
$temperatura_establecida  	= (trim(utf8_decode($_POST['x9'])) == '' ? NULL:trim(utf8_decode($_POST['x9'])));
$temperatura_minima  		= (trim(utf8_decode($_POST['x10'])) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$temperatura_maxima  		= (trim(utf8_decode($_POST['x11'])) == '' ? NULL:trim(utf8_decode($_POST['x11'])));
$temperatura_media  		= (trim(utf8_decode($_POST['x12'])) == '' ? NULL:trim(utf8_decode($_POST['x12'])));
$tiempo_preshipment  		= (trim(utf8_decode($_POST['x13'])) == '' ? NULL:trim(utf8_decode($_POST['x13'])));
$num_lote			  		= (trim(utf8_decode($_POST['x14'])) == '' ? NULL:trim(utf8_decode($_POST['x14'])));
$dias				  		= (trim(utf8_decode($_POST['x15'])) == '' ? NULL:trim(utf8_decode($_POST['x15'])));
$middle_temp1		  		= (trim(utf8_decode($_POST['x16'])) == '' ? NULL:trim(utf8_decode($_POST['x16'])));
$middle_temp2		  		= (trim(utf8_decode($_POST['x17'])) == '' ? NULL:trim(utf8_decode($_POST['x17'])));
$middle_temp3		  		= (trim(utf8_decode($_POST['x18'])) == '' ? NULL:trim(utf8_decode($_POST['x18'])));
$middle_temp4		  		= (trim(utf8_decode($_POST['x19'])) == '' ? NULL:trim(utf8_decode($_POST['x19'])));

if($fecha != NULL)
{
	$fecha = DateTime::createFromFormat("m-d-Y" , $fecha);

	$fecha = $fecha->format('Y-m-d');
}

$result = $CONTROL->qua_guardar_control_calidad($codigo_control_calidad,
										$cod_pais,
										$cod_departamento,
										$cod_producto,
										$fecha,
										$num_orden_compra,
										$tiempo,
										$temperatura_actual,
										$temperatura_establecida,
										$temperatura_minima,
										$temperatura_maxima,
										$temperatura_media,
										$tiempo_preshipment,
										$num_lote,
										$dias,
										$middle_temp1,
										$middle_temp2,
										$middle_temp3,
										$middle_temp4,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

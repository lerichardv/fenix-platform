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
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_CONF = new db_configuracion();

$codigo_periodo_fiscal	= trim(($_POST['x1']));
$anio_periodo  			= trim(($_POST['x2']));
$num_periodo	  		= trim(utf8_decode($_POST['x3']));
$fecha_inicio	 		= trim($_POST['x4']);
$fecha_final	  		= trim($_POST['x5']);

$fecha_inicio = DateTime::createFromFormat("m-d-Y" , $fecha_inicio);
$fecha_final = DateTime::createFromFormat("m-d-Y" , $fecha_final);

$fecha_inicio = $fecha_inicio->format('Y-m-d');
$fecha_final = $fecha_final->format('Y-m-d');

$result = $DB_CONF->conf_guardar_periodo_fiscal($codigo_periodo_fiscal,
										$anio_periodo,
										$num_periodo,
										$fecha_inicio,
										$fecha_final,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje'].' /// '.$fecha_inicio.' /// '.$fecha_final);
?>

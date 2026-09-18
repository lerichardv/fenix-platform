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

$cod_temporada 		= trim(($_POST['x1']));
$cod_info_empresa  	= trim(($_POST['x2']));
$cod_tipo_temporada = trim(utf8_decode($_POST['x3']));
$codigo_temporada 	= trim(utf8_decode($_POST['x4']));
$fecha_inicio  		= trim(utf8_decode($_POST['x5']));
$fecha_final  		= trim(utf8_decode($_POST['x6']));

$fecha_inicio = DateTime::createFromFormat("m-d-Y" , $fecha_inicio);
$fecha_final = DateTime::createFromFormat("m-d-Y" , $fecha_final);

$fecha_inicio = $fecha_inicio->format('Y-m-d');
$fecha_final = $fecha_final->format('Y-m-d');

$result = $DB_CONF->conf_guardar_temporada($cod_temporada,
										$cod_info_empresa,
										$cod_tipo_temporada,
										$codigo_temporada,
										$fecha_inicio,
										$fecha_final,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

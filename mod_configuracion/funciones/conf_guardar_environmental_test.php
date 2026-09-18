<?PHP
/*
 * Permite guardar un nuevo test ambiental.
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

$codigo_environmental_test	= trim(($_POST['x1']));
$cod_pais  					= trim(($_POST['x2']));
$cod_departamento  			= trim(utf8_decode($_POST['x3']));
$cod_municipio 				= trim(utf8_decode($_POST['x4']));
$cod_info_empresa  			= trim(utf8_decode($_POST['x5']));
$cod_location  				= trim(utf8_decode($_POST['x6']));
$cod_type_test  			= trim(utf8_decode($_POST['x7']));
$cod_source_phase  			= trim(utf8_decode($_POST['x8']));
$cod_sample  				= trim(utf8_decode($_POST['x9']));
$result						= str_replace(',','',$_POST['x10']);
$sample_id  				= trim(utf8_decode(str_replace(',','',$_POST['x11'])));
$sample_date  				= trim(utf8_decode($_POST['x12']));
$sample_time  				= trim(utf8_decode($_POST['x13']));

$sample_date = DateTime::createFromFormat("m-d-Y" , $sample_date);

$sample_date = $sample_date->format('Y-m-d');

$result = $DB_CONF->conf_guardar_environmental_test($codigo_environmental_test,
										$cod_pais,
										$cod_departamento,
										$cod_municipio,
										$cod_info_empresa,
										$cod_location,
										$cod_type_test,
										$cod_source_phase,
										$cod_sample,
										$result,
										$sample_id,
										$sample_date,
										$sample_time,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

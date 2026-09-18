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

$codigo_granja			= trim(($_POST['x1']));
$cod_gerencia  			= trim(($_POST['x2']));
$nombre_empresa  		= trim(utf8_decode($_POST['x3']));
$telefono_empresa 		= (trim(utf8_decode($_POST['x4'])) == '' ? NULL:trim(utf8_decode($_POST['x4'])));
$correo_empresa  		= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$lema_empresa  			= (trim(utf8_decode($_POST['x6'])) == '' ? NULL:trim(utf8_decode($_POST['x6'])));
$fax_empresa  			= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));
$direccion_linea_1  	= (trim(utf8_decode($_POST['x8'])) == '' ? NULL:trim(utf8_decode($_POST['x8'])));
$direccion_linea_2  	= (trim(utf8_decode($_POST['x9'])) == '' ? NULL:trim(utf8_decode($_POST['x9'])));
$descripcion_empresa	= (trim(utf8_decode($_POST['x10'])) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$ext_logo  			= trim(utf8_decode($_POST['x11']));

$result = $DB_CONF->conf_guardar_granja($codigo_granja,
										$cod_gerencia,
										$nombre_empresa,
										$telefono_empresa,
										$correo_empresa,
										$lema_empresa,
										$fax_empresa,
										$direccion_linea_1,
										$direccion_linea_2,
										$descripcion_empresa,
										$ext_logo,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

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

$codigo_formulario  = trim(($_POST['x1']));
$nombre_item  		= trim(utf8_decode($_POST['x2']));
$cod_tipo_item  	= trim(($_POST['x3']));
$descripcion_item 	= (trim(utf8_decode($_POST['x4'])) == '' ? NULL:trim(utf8_decode($_POST['x4'])));
$opcion1  			= (trim(utf8_decode($_POST['x5'])) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$opcion2  			= (trim(utf8_decode($_POST['x6'])) == '' ? NULL:trim(utf8_decode($_POST['x6'])));
$opcion3  			= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));
$opcion4  			= (trim(utf8_decode($_POST['x8'])) == '' ? NULL:trim(utf8_decode($_POST['x8'])));
$opcion5  			= (trim(utf8_decode($_POST['x9'])) == '' ? NULL:trim(utf8_decode($_POST['x9'])));
$opcion6  			= (trim(utf8_decode($_POST['x10'])) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$opcion7  			= (trim(utf8_decode($_POST['x11'])) == '' ? NULL:trim(utf8_decode($_POST['x11'])));
$opcion8  			= (trim(utf8_decode($_POST['x12'])) == '' ? NULL:trim(utf8_decode($_POST['x12'])));
$opcion9  			= (trim(utf8_decode($_POST['x13'])) == '' ? NULL:trim(utf8_decode($_POST['x13'])));
$opcion10  			= (trim(utf8_decode($_POST['x14'])) == '' ? NULL:trim(utf8_decode($_POST['x14'])));
$valor1  			= (trim(utf8_decode($_POST['x15'])) == '' ? '0':trim(utf8_decode($_POST['x15'])));
$valor2  			= (trim(utf8_decode($_POST['x16'])) == '' ? '0':trim(utf8_decode($_POST['x16'])));
$valor3  			= (trim(utf8_decode($_POST['x17'])) == '' ? '0':trim(utf8_decode($_POST['x17'])));
$valor4  			= (trim(utf8_decode($_POST['x18'])) == '' ? '0':trim(utf8_decode($_POST['x18'])));
$valor5  			= (trim(utf8_decode($_POST['x19'])) == '' ? '0':trim(utf8_decode($_POST['x19'])));
$valor6  			= (trim(utf8_decode($_POST['x20'])) == '' ? '0':trim(utf8_decode($_POST['x20'])));
$valor7  			= (trim(utf8_decode($_POST['x21'])) == '' ? '0':trim(utf8_decode($_POST['x21'])));
$valor8  			= (trim(utf8_decode($_POST['x22'])) == '' ? '0':trim(utf8_decode($_POST['x22'])));
$valor9  			= (trim(utf8_decode($_POST['x23'])) == '' ? '0':trim(utf8_decode($_POST['x23'])));
$valor10  			= (trim(utf8_decode($_POST['x24'])) == '' ? '0':trim(utf8_decode($_POST['x24'])));

$result = $DB_CONF->conf_guardar_item_formulario($codigo_formulario,
										$nombre_item,
										$cod_tipo_item,
										$descripcion_item,
										$opcion1,
										$opcion2,
										$opcion3,
										$opcion4,
										$opcion5,
										$opcion6,
										$opcion7,
										$opcion8,
										$opcion9,
										$opcion10,
										$valor1,
										$valor2,
										$valor3,
										$valor4,
										$valor5,
										$valor6,
										$valor7,
										$valor8,
										$valor9,
										$valor10,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

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
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();

$cod_aplicacion_quimico			= trim(($_POST['x1']));
$cod_tipo_quimico  				= trim(($_POST['x2']));
$cod_inventario_quimico  		= trim(($_POST['x3']));
$cod_unidad_medida_origen  		= trim(($_POST['x4']));
$cod_unidad_medida_destino 		= trim(($_POST['x5']));
$cantidad_sugerida				= str_replace(',','',$_POST['x6']);


$result = $DB_PLANT->plan_agregar_quimico_aplicacion_quimico($cod_aplicacion_quimico,
										$cod_tipo_quimico,
										$cod_inventario_quimico,
										$cod_unidad_medida_origen,
										$cod_unidad_medida_destino,
										$cantidad_sugerida,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

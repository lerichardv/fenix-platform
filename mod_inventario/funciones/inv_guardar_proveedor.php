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

$codigo_proveedor	= trim(($_POST['x1']));
$cod_info_empresa  	= trim(utf8_decode($_POST['x2']));
$nombre_empresa		= trim(utf8_decode($_POST['x3']));
$nombre_contacto 	= trim(utf8_decode($_POST['x4']));
$correo_contacto	= trim(utf8_decode($_POST['x5']));
$telefono_contacto	= (trim(utf8_decode($_POST['x6'])) == '' ? NULL:trim(utf8_decode($_POST['x6'])));
$observaciones		= (trim(utf8_decode($_POST['x7'])) == '' ? NULL:trim(utf8_decode($_POST['x7'])));

$result = $DB_INV->inv_guardar_proveedor($codigo_proveedor,
										$cod_info_empresa,
										$nombre_empresa,
										$nombre_contacto,
										$correo_contacto,
										$telefono_contacto,
										$observaciones,
										$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

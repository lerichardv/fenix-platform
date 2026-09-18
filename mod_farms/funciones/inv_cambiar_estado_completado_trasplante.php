<?PHP
/*
 * Marca como completado o no un trasplante especifico.
 * @author      Edwin Olivera
 * @date        2023-10-03
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$cod_trasplante  	= trim(($_POST['x1']));
$flag_activo  		= trim(($_POST['x2']));

$result = $DB_INV->inv_cambiar_estado_de_completado_trasplante(
	$cod_trasplante,
	$flag_activo
);
echo $result ;

<?PHP
/*
 * Obtiene la ultima fecha de actualización del inventario por greenhouse.
 * @author      Dan Urquia
 * @date        2023-12-26
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_reportes_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV         = new db_rep_inv();
$cod_greenhouse = $_POST['x1'];
$FECHAS         = $DB_INV->get_fecha_greenhouse($cod_greenhouse);

echo $FECHAS[0]["FECHA"]; 
?>

<?PHP
/*
 * Marca como completado o no un trasplante especifico.
 * @author      Edwin Olivera
 * @date        2023-10-03*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$fechaInicial    	= trim(($_POST['x1']));
$fechaFinal  		= trim(($_POST['x2']));
$cod_inventario  	= trim(($_POST['x3']));
$item  		        = trim(($_POST['x4']));
$flag_completado  	= trim(($_POST['x5']));
$comp           	= trim(($_POST['x6']));


$trans = $DB_INV->inv_arr_transplantes($fechaInicial, $fechaFinal, $cod_inventario, $item, $flag_completado);

$result = $DB_INV->inv_cambiar_estado_trasplante_varios($comp,
														$trans[0]['TRANSPLANTES']);

echo utf8_encode("0|Transplant updated");
?>
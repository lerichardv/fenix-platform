<?PHP
/*
 * Cambia el estado de activacion de una temporada.
 * @author      Edwin Olivera
 * @date        2023-09-11
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

$cod_grupo_siembra  	= trim(($_POST['x1']));
$flag_activo  	= trim(($_POST['x2']));

$result = $DB_INV->inv_cambiar_estado_temporada(
	$cod_grupo_siembra,
	$flag_activo
);
echo utf8_encode($result);

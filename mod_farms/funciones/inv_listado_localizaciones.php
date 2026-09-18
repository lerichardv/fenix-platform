<?PHP
/*
 * Listado de todas las localizaciones en los que se enviaran los trasplantes4.
 * @author      Edwin Olivera
 * @date        2023-10-06
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
$LOCALIZACIONES       = $DB_INV->inv_listado_localizaciones_activas();

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($LOCALIZACIONES as $localizacion) {
	$data[] = array_map('utf8_encode', $localizacion);
}
echo json_encode($data);

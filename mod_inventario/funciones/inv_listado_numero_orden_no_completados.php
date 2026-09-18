<?PHP
/*
 * Listado de todos los número activos y que todavía no están completados en listado de Trasplante.
 * @author      Edwin Olivera
 * @date        2023-11-04
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	// header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV 	= new db_inventario();
$codigos_plantaciones_completadas	= $DB_INV->inv_codigos_plantaciones_completadas();


$bloquesUsados = [];
foreach ($codigos_plantaciones_completadas as $bloque) {
	$bloquesUsados[] = $bloque["cod_plantacion"];
}
$convertedArray = implode(",", $bloquesUsados);

$NUMEROS_ORDEN	= $DB_INV->inv_listado_numero_de_orden_no_completados($convertedArray);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($NUMEROS_ORDEN as $numero_orden) {
	$data[] = array_map('utf8_encode', $numero_orden);
}
echo json_encode($data);

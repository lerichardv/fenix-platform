<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Edwin Olivera
 * @date        2023-09-30
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

$cod_plantacion = "";
$numero_orden = "";

$cod_plantacion = $_POST["x1"];
$numero_orden   = $_POST["x2"];
if ($numero_orden != "") {
	$DATOSPLANTACION = $DB_INV->inv_buscar_plantacion_por_numero_de_orden($numero_orden);
}

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($DATOSPLANTACION as $TIPO) {
	$data[] = array_map('utf8_encode', $TIPO);
}
echo json_encode($data);

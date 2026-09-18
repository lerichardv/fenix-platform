<?PHP
/*
 * Listado de todas semillas que este asociados a un número de orden especifico.
 *Este archivo es para realizar pruebas y no afectar lo que hay en producción
 *  @author      Edwin Olivera
 * @date        2023-10-02
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
$numero_orden = $_POST["x1"];
$CODIGOS_PLANTACIONES	= $DB_INV->inv_codigos_plantaciones_en_trasplante($numero_orden);
if(!isset($CODIGOS_PLANTACIONES[0]["codigos_plantaciones"])){
	$CODIGOS_PLANTACIONES[0]["codigos_plantaciones"] = 0;
}

$SEMILLAS	= $DB_INV->inv_listado_semillas_por_numero_de_orden($numero_orden,$CODIGOS_PLANTACIONES[0]["codigos_plantaciones"]);
$cantidadElementos = 0;
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($SEMILLAS as $semilla) {
	$data[] = array_map('utf8_encode', $semilla);
	$cantidadElementos = $cantidadElementos + 1;
}
// echo json_encode($CODIGOS_PLANTACIONES);


// echo $CODIGOS_PLANTACIONES[0]["codigos_plantaciones"];
echo json_encode($SEMILLAS);
// echo $cantidadElementos;

<?PHP
/*
 * Listado de todos los número activos que se encuentra en la base de datos.
 * @author      Edwin Olivera
 * @date        2023-10-02
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
$DB_INV 	= new db_inventario();
$NUMEROS_ORDEN	= $DB_INV->inv_listado_numero_de_orden_activos();

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($NUMEROS_ORDEN as $numero_orden) {
	$data[] = array_map('utf8_encode', $numero_orden);
}
echo json_encode($data);

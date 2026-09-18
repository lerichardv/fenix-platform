<?PHP
/*
 * Listado de todos los invernaderos, seleccionado por semilla en especifico.
 * @author      Edwin Olivera
 * @date        2023-10-14
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
$codigo_inventario_semilla_para_modal = $_POST["x1"];
$INVERNADEROS	= $DB_INV->inv_listado_invernaderos_por_semilla($codigo_inventario_semilla_para_modal);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($INVERNADEROS as $invernadero) {
	$data[] = array_map('utf8_encode', $invernadero);
}
echo json_encode($data);

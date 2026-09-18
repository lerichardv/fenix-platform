<?PHP
/*
 * Listado de campos activos.
 * @author      Edwin Olivera
 * @date        2024-02-05
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV 	= new db_inventario();
$CAMPOS    = $DB_INV->inv_listado_campos_activos();

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($CAMPOS as $campo){
	$data[]=array_map('utf8_encode', $campo);
}
echo json_encode($data);
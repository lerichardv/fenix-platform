<?PHP
/*
 * Listado de las familias de las semillas.
 * @author      Edwin Olivera
 * @date        2023-10-03
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
$DB_INV = new db_inventario();
$FAMILIAS       = $DB_INV->inv_listado_familia_de_semillas_activas();

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($FAMILIAS as $familia){
	$data[]=array_map('utf8_encode', $familia);
}
echo json_encode($data);
?>

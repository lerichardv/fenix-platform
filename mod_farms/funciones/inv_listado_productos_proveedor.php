<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
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
$cod_proveedor = $_POST['x1'];
$PRODUCTOS       = $DB_INV->inv_obtener_listado_productos_por_proveedor($cod_proveedor);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($PRODUCTOS as $PRODUCTO){
	$data[]=array_map('utf8_encode', $PRODUCTO);
}
echo json_encode($data);
?>

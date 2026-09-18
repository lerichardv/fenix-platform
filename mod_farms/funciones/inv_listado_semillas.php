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
$cod_info_empresa = $_POST['x1'];
$SEMILLAS       = $DB_INV->inv_listado_semillas_por_finca($cod_info_empresa);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($SEMILLAS as $SEMILLA){
	$data[]=array_map('utf8_encode', $SEMILLA);
}
echo json_encode($data);
?>

<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
*/

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();
$codigo_detalle  	= trim(($_POST['codigo_detalle']));
$HARVESTING       = $DB_PLANT->plan_obtener_info_harvesting_worksheet($codigo_detalle);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($HARVESTING as $harvesting){
	$data[]=array_map('utf8_encode', $harvesting);
}
echo json_encode($data);
?>

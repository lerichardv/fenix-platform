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
$cod_aplicacion  	= trim(($_POST['x1']));
$APLICACION       = $DB_PLANT->plan_obtener_info_aplicacion_quimicos($cod_aplicacion);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($APLICACION as $aplicacion){
	$data[]=array_map('utf8_encode', $aplicacion);
}
echo json_encode($data);
?>

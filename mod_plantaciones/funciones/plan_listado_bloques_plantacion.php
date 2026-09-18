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
$cod_plantacion  	= trim(($_POST['x1']));
$BLOQUES       = $DB_PLANT->plan_listado_bloques_plantacion($cod_plantacion);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($BLOQUES as $BLOQUE){
	$data[]=array_map('utf8_encode', $BLOQUE);
}
echo json_encode($data);
?>

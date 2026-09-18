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
$PLANTACIONES       = $DB_PLANT->bw_listado_plantaciones();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($PLANTACIONES as $PLANTACION){
	$data[]=array_map('utf8_encode', $PLANTACION);
}
echo json_encode($data);
?>

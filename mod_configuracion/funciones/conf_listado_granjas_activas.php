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
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_CONG = new db_configuracion();
$GRANJAS       = $DB_CONG->conf_listado_granjas_activas();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($GRANJAS as $GRANJA){
	$data[]=array_map('utf8_encode', $GRANJA);
}
echo json_encode($data);
?>

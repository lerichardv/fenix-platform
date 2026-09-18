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
$TIPOS       = $DB_CONG->conf_listado_tipos_items_formularios();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($TIPOS as $TIPO){
	$data[]=array_map('utf8_encode', $TIPO);
}
echo json_encode($data);
?>

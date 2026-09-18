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
$DB_USU = new db_configuracion();
$ITEMS       = $DB_USU->conf_listado_formulario_tipo_items();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($ITEMS as $ITEM){
	$data[]=array_map('utf8_encode', $ITEM);
}
echo json_encode($data);
?>
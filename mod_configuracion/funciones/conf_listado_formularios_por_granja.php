<?PHP
/*
 * Listado de los formularios activos según código de granja/finca.
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
$DB_CONG 			= new db_configuracion();
$cod_info_empresa 	= $_POST['x1'];
$FORMULARIOS       	= $DB_CONG->conf_listado_formularios_por_granja($cod_info_empresa);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($FORMULARIOS as $FORMULARIO){
	$data[]=array_map('utf8_encode', $FORMULARIO);
}
echo json_encode($data);
?>

<?PHP
/*
 * Listado de los tipos de documentos actualmente activos.
 * @author      Dan Urquía
 * @date        2017-06-23
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_documentacion.php");
/*INSTANCIAMIENTOS*/
$DB_DOCUMENTOS = new db_documentacion();
$DOCUMENTOS    = $DB_DOCUMENTOS->get_tipo_documento_activos();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
if(count($DOCUMENTOS) > 0){
	foreach($DOCUMENTOS as $DOCUMENTO){
		$data[]=array_map('utf8_encode', $DOCUMENTO);
	}
} else {
	$data[]=array_map('utf8_encode', 'array(1) { [0]=> array(4) { ["cod_tipo_documento"]=> string(1) "-b" ["tipo_documento"]=> string(10) "No hay datos" ["descripcion"]=> string(24) "" ["color_tipo"]=> string(7) "#FFFFFF" } } ');
}
echo json_encode($data);
?>

<?PHP
/*
 * Llenado de autocomplete de los documentos en repositorio según parametro.
 * @author      Dan Urquía
 * @date        2017-06-28
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_documentacion.php");
/*INSTANCIAMIENTOS*/
$DB_DOUMENTOS = new db_documentacion();
$parametro	  = $_POST['x1'];
$DOCUMENTOS   = $DB_DOUMENTOS->get_documentos_autocomplete($parametro);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($DOCUMENTOS as $DOCUMENTO){
	$data[]=array_map('utf8_encode', $DOCUMENTO);
}
echo json_encode($data);
?>

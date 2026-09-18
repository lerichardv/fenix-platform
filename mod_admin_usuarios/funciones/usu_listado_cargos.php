<?PHP
/*
 * Listado de los cargos disponibles para los usuarios.
 * @author      Jairo Bonilla
 * @date        2016-06-12
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$cod_gerencia = trim(utf8_decode($_POST['x1']));
$CARGOS       = $DB_USUARIO->usu_listado_cargos($cod_gerencia);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($CARGOS as $CARGO){
	$data[]=array_map('utf8_encode', $CARGO);
}
echo json_encode($data);
?>

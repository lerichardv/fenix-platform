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
include_once("../../libs/db_classes/db_usuario.php");
/*INSTANCIAMIENTOS*/
$DB_USU = new db_usuario();
$MODULOS       = $DB_USU->get_listado_modulos();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($MODULOS as $MODULO){
	$data[]=array_map('utf8_encode', $MODULO);
}
echo json_encode($data);
?>
<?PHP
/*
 * Listado de las ciudades  para casos.
 * @author      Kevin Fúnez
 * @date        2017-03-26
 */

session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_geografia.php");
/*INSTANCIAMIENTOS*/
$DB_GEOGRAFIA      = new db_geografia();
$cod_pais	         = $_POST['x1'];
$cod_departamento  = $_POST['x2'];
$cod_municipio     = $_POST['x3'];
$CIUDADES          = $DB_GEOGRAFIA->get_ciudades($cod_pais,$cod_departamento,$cod_municipio);
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($CIUDADES as $CIUDAD){
	$data[]=array_map('utf8_encode', $CIUDAD);
}
echo json_encode($data);
?>

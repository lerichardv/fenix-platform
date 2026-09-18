<?PHP
/*
 * Listado de los paises  para casos.
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
$DB_GEOGRAFIA = new db_geografia();
$PAISES       = $DB_GEOGRAFIA->get_paises();
//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach($PAISES as $PAIS){
	$data[]=array_map('utf8_encode', $PAIS);
}
echo json_encode($data);
?>

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
include_once("../../libs/db_classes/db_plantaciones.php");
/*INSTANCIAMIENTOS*/
$DB_PLANT = new db_plantaciones();

$codigo_plantacion	= trim(($_POST['x1']));
$cod_zona  			= trim(utf8_decode($_POST['x2']));
$cod_bloque			= ($_POST['x3']);
$cantidad_acres		= (str_replace(',','',$_POST['x4']) == '' ? NULL:str_replace(',','',$_POST['x4']));


foreach($cod_bloque as $bloque) {
	$result = $DB_PLANT->plan_agregar_zona_plantacion($codigo_plantacion,
										$cod_zona,
										$bloque,
										$cantidad_acres,
										$_SESSION['cod_usuario']);
}

echo utf8_encode($result[0]['mensaje']);
?>

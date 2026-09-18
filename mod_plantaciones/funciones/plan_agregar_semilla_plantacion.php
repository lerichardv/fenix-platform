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

$codigo_plantacion			= trim(($_POST['x1']));
$cod_bloques_plantacion  	= $_POST['x2'];
$cod_inventario_semilla		= trim(utf8_decode($_POST['x3']));
$cantidad_usada				= str_replace(',','',trim($_POST['x4']));
$cod_inventario_maquinaria	= $_POST['x5'];
$descripcion_semilla		= trim(utf8_decode($_POST['x6']));
//echo 'entró';
$result = $DB_PLANT->plan_agregar_semilla_plantacion($codigo_plantacion,
									$cod_inventario_semilla,
									$cantidad_usada,
									$descripcion_semilla,
									$_SESSION['cod_usuario']);
//echo 'hizo SP1';
$cod_detalle = $result[0]['COD_DETALLE'];
//echo 'cod_detalle: '.$cod_detalle;

foreach ($cod_bloques_plantacion as $cod_bloque) {
	$DB_PLANT->plan_agregar_bloque_semilla_plantacion($cod_detalle,
													$cod_bloque,
													$_SESSION['cod_usuario']);
}

foreach ($cod_inventario_maquinaria as $cod_maquinaria) {
	$DB_PLANT->plan_agregar_maquinaria_semilla_plantacion($cod_detalle,
													$cod_maquinaria,
													$_SESSION['cod_usuario']);	
}

$DB_PLANT->plan_actualizar_acres_plantacion($codigo_plantacion);


echo utf8_encode($result[0]['mensaje']);
?>

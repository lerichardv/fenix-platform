<?PHP
/*
 * Listado de bloques que no se han usado en las plantaciones de granja.
 * @author      Edwin Olivera
 * @date        2024-03-30
*/
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_farms.php");
/*INSTANCIAMIENTOS*/
$DB_FARM 	= new db_farms();
$codigos_campos = $_POST["x1"];
$codigosBloquesUsados = $DB_FARM->farm_bloques_asociados_a_semillas();
$BLOQUES = $DB_FARM->farm_listado_bloques_libres($codigos_campos, $codigosBloquesUsados[0]["bloques_usados"]);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($BLOQUES as $bloque) {
	$data[] = array_map('utf8_encode', $bloque);
}
echo json_encode($data);

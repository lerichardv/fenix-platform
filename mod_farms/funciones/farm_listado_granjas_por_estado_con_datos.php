<?PHP
/*
 * Listado de granjas por uno o varios estados con datos.
 * @author      Edwin Olivera
 * @date        2024-03-05
*/

use PhpOffice\PhpSpreadsheet\Calculation\TextData\Trim;

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
$cod_campo = Trim($_POST["x1"]);
$GRANJAS = $DB_FARM->farm_listado_granjas_por_estado_con_datos($cod_campo);

//Recorre el arreglo para convertir caracteres especiales en simbolos legibles para el lenguaje.
foreach ($GRANJAS as $granja) {
	$data[] = array_map('utf8_encode', $granja);
}
echo json_encode($data);

<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Edwin Olivera
 * @date        2023-09-21
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();

$SEMILLAS       = $DB_INV->inv_listado_semillas_activas();
$numero = 1234567.89;
foreach($SEMILLAS as $SEMILLA){
	$SEMILLA["cantidad_semilla_sin_formato"] = $SEMILLA["cantidad_semilla"];
	$SEMILLA["cantidad_semilla"] = number_format($SEMILLA["cantidad_semilla"], 2, '.', ','); // "1.234.567,89"
	$data[]=array_map('utf8_encode', $SEMILLA);
}
echo json_encode($data);
// echo $numeroFormateado;

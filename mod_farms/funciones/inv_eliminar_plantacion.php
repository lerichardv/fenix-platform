<?PHP
/*
 * Elimina una plantancion. Todos los registros por medio de su número de orden
 * @author      Edwin Olivera
 * @update      2023-10-08
*/

ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
	header('Location: index.php');
}


/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_inventario.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();



$numero_orden				= trim(($_POST['x1']));

$codsPlantanciones = [];
$codsPlantanciones = $DB_INV->inv_buscar_cod_plantacion_por_numero_de_orden(
	$numero_orden
);
if (isset($codsPlantanciones[0])) {
	for ($index = 0; $index < count($codsPlantanciones); $index++) {

		$result = $DB_INV->inv_eliminar_plantacion(
			$codsPlantanciones[$index]["cod_plantacion"]
		);
	}
}


echo json_encode($result);
// echo json_encode ($varAlgo );

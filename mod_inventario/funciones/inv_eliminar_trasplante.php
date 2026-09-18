<?PHP
/*
 * Elimina todo un trasplante
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

$numero_ticket	= trim(($_POST['x1']));

$codsPlantanciones = [];
$codsPlantanciones = $DB_INV->inv_buscar_cod_trasplante_por_numero_ticket(
	$numero_ticket
);

if (isset($codsPlantanciones[0])) {
	for ($index = 0; $index < count($codsPlantanciones); $index++) {
		$result = $DB_INV->inv_eliminar_trasplante(
			$codsPlantanciones[$index]["cod_trasplante"]
		);
	}
}


echo json_encode($result);

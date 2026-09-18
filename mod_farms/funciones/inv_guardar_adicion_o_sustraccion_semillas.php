<?PHP
/*
 * Guarda las cantidades a sumar o restar del inventario de semillas.
 * @author    Edwin Olivera
 * @date      2023-09-13
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

$codigo_inventario_semilla	= trim(($_POST['x1'])); //Esta
$cantidad_sumar 			= str_replace(',', '', trim(utf8_decode($_POST['x2']))); //Esta
$cantidad_restar 			= str_replace(',', '', trim(utf8_decode($_POST['x3']))); //Esta
$razon_sumar_restar			= trim(utf8_decode($_POST['x4'])); //Esta
$fecha_sumar_restar			= "";
$cantidadIngresada			= $_POST['x6'];


try {
	// if ($fecha_sumar_restar != NULL) {
	// 	$fecha_sumar_restar = DateTime::createFromFormat("m-d-Y H:i:s", $fecha_sumar_restar);
	// 	$fecha_sumar_restar = $fecha_sumar_restar->format('Y-m-d H:i:s');
	// }

	$fecha_sumar_restar = date("Y-m-d H:i:s");


	// Zona horaria específica (por ejemplo, 'America/New_York')
	$zonaHoraria = new DateTimeZone('America/New_York');

	// Obtener la fecha y hora actual en la zona horaria especificada
	$fechaActual = new DateTime('now', $zonaHoraria);

	// Formatear la fecha y hora según el formato deseado
	$formato = 'Y-m-d H:i:s';
	$fechaFormateada = $fechaActual->format($formato);
	$result = $DB_INV->inv_datos_semilla_de_empresa_por_codigo_de_inventario(
		$codigo_inventario_semilla
	);
	$codigosInventarios = $DB_INV->inv_codigos_invetario(
		$result[0]['cod_empresa'],
	);
	$codigoEmprea = $DB_INV->inv_actualizar_fecha_semilla_por_empresa(
		$codigosInventarios[0]['codigos_inventario'],
		$fechaFormateada
	);


	$result = $DB_INV->inv_actualizar_semilla_por_empresa_fecha_actualizacion(
		$codigo_inventario_semilla,
		$cantidadIngresada,
		$fechaFormateada
	);

	$DB_INV->inv_guardar_inventario_semilla_sumar_restar(
		$result[0]["cod_inventario_semilla"],
		$fecha_sumar_restar,
		$cantidad_sumar,
		$cantidad_restar,
		$razon_sumar_restar,
		$_SESSION['cod_usuario']
	);


	$sumatoria_semillas = $DB_INV->inv_cantidad_total_semilla_por_cod_inventario_semilla($result[0]["cod_inventario_semilla"]);
	$result = $DB_INV->inv_actualizar_cantidad_semilla_consolidad($result[0]["cod_inventario_semilla"], $sumatoria_semillas[0]["cantidad_semilla_consolidad"]);
	echo $result;
	// print_r($result);
} catch (\Throwable $th) {
	throw $th;
	echo $th;
	die();
}

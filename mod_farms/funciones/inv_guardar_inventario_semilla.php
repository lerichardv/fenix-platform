<?PHP
/*
 * Listado de los estados de plantaciones.
 * @author      Jairo Bonilla
 * @date        2018-10-25
 * @editor      Edwin Olivera
 * @update      2023-09-02
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

$codigo_inventario_semilla	= $_POST['x1'];
$cod_info_empresa  			= $_POST['x2'];
$codigo_semilla				= trim($_POST['x3']);
$nombre_semilla  			= trim(utf8_decode($_POST['x4']));
$cod_variedad				= trim($_POST['x5']);
$abreviatura_semilla  		= trim(utf8_decode($_POST['x6']));
$cantidad_semilla			= str_replace(',', '', trim($_POST['x7']));
$cod_unidad_medida  		= trim($_POST['x8']);
$cantidad_fisica_semilla	= (str_replace(',', '', trim($_POST['x9'])) == '' ? NULL : str_replace(',', '', trim($_POST['x9'])));
$precio_unidad  			= (str_replace(',', '', trim($_POST['x10'])) == '' ? NULL : str_replace(',', '', trim($_POST['x10'])));
$numero_lote	  			= (trim(utf8_decode($_POST['x11'])) == '' ? NULL : trim(utf8_decode($_POST['x11'])));
$flag_watercress  			= trim(utf8_decode($_POST['x12']));
$cantidad_sumar 			= str_replace(',', '', trim(utf8_decode($_POST['x13'])));
$cantidad_restar 			= str_replace(',', '', trim(utf8_decode($_POST['x14'])));
$razon_sumar_restar			= trim(utf8_decode($_POST['x15']));
$fecha_sumar_restar			= trim(utf8_decode($_POST['x16'])) == '' ? NULL : trim(utf8_decode($_POST['x16']));


$cod_categoria 					= $_POST['x17'];
$cod_grupo_siembra 				= $_POST['x18'];
$cod_rasgo 						= $_POST['x19'];
$plants_acre 					= $_POST['x20'];
$cod_familia 					= $_POST['x21'];
$red_zone 						= $_POST['x23'];
$over_seed						= $_POST['x24'];
$semillas_por_plantaciones 		= $_POST['x25'];
$paletizado 					= $_POST['x26'];
$semilla_activa					= $_POST['x27'];
$notas 							= $_POST['x28'];
$og_supply 						= $_POST['x29'];
$cod_vendedores 				= $_POST['x30'];
$flag_germinacion_automatica 	= $_POST['x31'];
$cod_tipo_semilla 				= $_POST['x32'];
if ($semillas_por_plantaciones < 1) {
	$semillas_por_plantaciones = 0;
}
if ($cantidad_fisica_semilla < 1) {
	$cantidad_fisica_semilla = 0;
}


try {

	$fecha_sumar_restar = date("Y-m-d H:i:s");

	$result = $DB_INV->inv_guardar_inventario_semilla(
		$codigo_inventario_semilla,
		$cod_info_empresa[0], // Asignamos predeterminadamente el primer invernadero en grupo
		$codigo_semilla,
		$nombre_semilla,
		$cod_variedad,
		$abreviatura_semilla,
		$cantidad_semilla,
		$cod_unidad_medida,
		$cantidad_fisica_semilla,
		$precio_unidad,
		$numero_lote,
		$flag_watercress,
		$cod_categoria,
		$cod_grupo_siembra,
		$cod_rasgo,
		$plants_acre,
		$cod_familia,
		$red_zone,
		$over_seed,
		$semillas_por_plantaciones,
		$paletizado,
		$semilla_activa,
		$notas,
		$og_supply,
		$cod_vendedores,
		$flag_germinacion_automatica,
		$cod_tipo_semilla,
		$_SESSION['cod_usuario']
		// 73
	);

	$cadena = $result[0]["mensaje"]; // '0|Inventory has been entered correctly.|',COD_INV
	$partes = explode("|", $cadena);

	//Para nuevos registros
	if ($codigo_inventario_semilla < 1) {

		$codigo_inventario_nuevo = $partes[2];
		$DB_INV->inv_guardar_inventario_semilla_sumar_restar(
			$codigo_inventario_nuevo, // cod_inventario de la tabla "bw_inventario_semilla"
			$fecha_sumar_restar,
			$cantidad_semilla,
			0,	//$cantidad_restar,
			"Registro nuevo de semilla",
			$_SESSION['cod_usuario']
		);
		if ($codigo_inventario_nuevo != "" && $codigo_inventario_nuevo != 0) {
			for ($indice = 0; $indice < count($cod_info_empresa); $indice++) {
				$DB_INV->inv_ingresar_semilla_por_empresa($codigo_inventario_nuevo, $cod_info_empresa[$indice], $cantidad_semilla, $_SESSION['cod_usuario']);
			}
		}
	} else {
		for ($indice = 0; $indice < count($cod_info_empresa); $indice++) {

			$cod_inventario_encontrado = $DB_INV->inv_buscar_cod_inventario_en_inventario_semilla_empresa($codigo_inventario_semilla, $cod_info_empresa[$indice]);
			if (!isset($cod_inventario_encontrado[0]["cod_inventario"]) || $cod_inventario_encontrado[0]["cod_inventario"] == "") {
				$DB_INV->inv_ingresar_semilla_por_empresa($codigo_inventario_semilla, $cod_info_empresa[$indice], 0, $_SESSION['cod_usuario']);

				// Reducimo y consolidamos la cantidad en las 2 tablas respectivas
				$sumatoria_semillas = $DB_INV->inv_cantidad_total_semilla_por_cod_inventario_semilla($codigo_inventario_semilla);
				$DB_INV->inv_actualizar_cantidad_semilla_consolidad($codigo_inventario_semilla, $sumatoria_semillas[0]["cantidad_semilla_consolidad"]);
			}
		}

		// * CASO EN EL QUE YA NO SE TIENE ASIGNADO UN INVERNADERO QUE PREVIAMENTE ESTABA SELECCIONADO
		$INVERNADEROS_ASOCIADOS = $DB_INV->inv_obtener_codigo_invernaderos_asociados($codigo_inventario_semilla);

		if (is_array($INVERNADEROS_ASOCIADOS) && !empty($INVERNADEROS_ASOCIADOS)) {
			foreach ($INVERNADEROS_ASOCIADOS as $invernadero) {
				$invernadero["cod_inventario"];
				$invernadero["cantidad_semilla"];
				$invernadero["cod_empresa"];
				if (!in_array($invernadero["cod_empresa"], $cod_info_empresa)) {
					//No esta entre las empresas que antes estaban seleccionadas, por lo que se debe borrar el registro de la tabla
					$INVERNADEROS_ASOCIADOS = $DB_INV->inv_eliminar_semillas_por_empresas(
						$invernadero["cod_inventario"]
					);
				}
			}
		}
	}

	if ($cantidad_sumar > 0 || $cantidad_restar > 0) {
		$DB_INV->inv_guardar_inventario_semilla_sumar_restar(
			$codigo_inventario_semilla,
			$fecha_sumar_restar,
			$cantidad_sumar,
			$cantidad_restar,
			$razon_sumar_restar,
			$_SESSION['cod_usuario']
		);
	}
	echo $result[0]['mensaje'];
} catch (\Throwable $th) {
	throw $th;
	echo $th;
	die();
}

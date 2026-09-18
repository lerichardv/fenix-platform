<?PHP

/*

	* Listado de los estados de plantaciones.

	* @author		Jairo Bonilla

	* @date			2018-10-25

	* @edit_for		Edwin Olivera
	* @update		2022-12-30

*/



session_start();

if (!isset($_SESSION['cod_usuario'])) {

	header('Location: index.php');
}



/*CONEXION CON BASE DE DATOS*/

include_once("../../libs/db_classes/db_mysql_conn.php");

include_once("../../libs/db_classes/db_plantaciones.php");

/*INSTANCIAMIENTOS*/

$DB_PLANT = new db_plantaciones();



$codigo_detalle  			= trim(($_POST['x1']));

$etapa_crecimiento 			= trim(($_POST['x2']));

$worms  					= (str_replace(',', '', $_POST['x3']) == '' ? NULL : str_replace(',', '', $_POST['x3']));

$eggs  						= (str_replace(',', '', $_POST['x4']) == '' ? NULL : str_replace(',', '', $_POST['x4']));

$leafhoppers  				= (str_replace(',', '', $_POST['x5']) == '' ? NULL : str_replace(',', '', $_POST['x5']));

$aphids  					= (str_replace(',', '', $_POST['x6']) == '' ? NULL : str_replace(',', '', $_POST['x6']));

$stink_bugs  				= (str_replace(',', '', $_POST['x7']) == '' ? NULL : str_replace(',', '', $_POST['x7']));

$gnats  					= (str_replace(',', '', $_POST['x8']) == '' ? NULL : str_replace(',', '', $_POST['x8']));

$flea_beetles  				= (str_replace(',', '', $_POST['x9']) == '' ? NULL : str_replace(',', '', $_POST['x9']));

$cyclaman_mites  			= (str_replace(',', '', $_POST['x10']) == '' ? NULL : str_replace(',', '', $_POST['x10']));

$cercospora_leaf_spot  		= (str_replace(',', '', $_POST['x11']) == '' ? NULL : str_replace(',', '', $_POST['x11']));

$pythium  					= (str_replace(',', '', $_POST['x12']) == '' ? NULL : str_replace(',', '', $_POST['x12']));

$rhizoctonia_aerial_blight  = (str_replace(',', '', $_POST['x13']) == '' ? NULL : str_replace(',', '', $_POST['x13']));

$bacteria  					= (str_replace(',', '', $_POST['x14']) == '' ? NULL : str_replace(',', '', $_POST['x14']));

$sclerotinia  				= (str_replace(',', '', $_POST['x15']) == '' ? NULL : str_replace(',', '', $_POST['x15']));

$alternaria_specks  		= (str_replace(',', '', $_POST['x16']) == '' ? NULL : str_replace(',', '', $_POST['x16']));

$mildew  					= (str_replace(',', '', $_POST['x17']) == '' ? NULL : str_replace(',', '', $_POST['x17']));

$virus  					= (str_replace(',', '', $_POST['x18']) == '' ? NULL : str_replace(',', '', $_POST['x18']));

$dollarweed  				= (str_replace(',', '', $_POST['x19']) == '' ? NULL : str_replace(',', '', $_POST['x19']));

$frogs_bit  				= (str_replace(',', '', $_POST['x20']) == '' ? NULL : str_replace(',', '', $_POST['x20']));

$mud_plantain  				= (str_replace(',', '', $_POST['x21']) == '' ? NULL : str_replace(',', '', $_POST['x21']));

$tube_weed  				= (str_replace(',', '', $_POST['x22']) == '' ? NULL : str_replace(',', '', $_POST['x22']));

$grass  					= (str_replace(',', '', $_POST['x23']) == '' ? NULL : str_replace(',', '', $_POST['x23']));

$damaged_leaves  			= (str_replace(',', '', $_POST['x24']) == '' ? NULL : str_replace(',', '', $_POST['x24']));

$purple_stem  				= (str_replace(',', '', $_POST['x25']) == '' ? NULL : str_replace(',', '', $_POST['x25']));

$watercress_rooter  		= (str_replace(',', '', $_POST['x26']) == '' ? NULL : str_replace(',', '', $_POST['x26']));

$observaciones_exploracion  = (trim(utf8_decode($_POST['x27'])) == '' ? NULL : trim(utf8_decode($_POST['x27'])));

$codigo_plantacion  		= trim(($_POST['x28']));

$fecha_exploracion  		= (trim(($_POST['x29'])) == '' ? NULL : trim(utf8_decode($_POST['x29'])));
// Nuevas propiedades
$spidermites  				= (str_replace(',', '', $_POST['x30']) == '' ? NULL : str_replace(',', '', $_POST['x30']));

$white_rust  				= (str_replace(',', '', $_POST['x31']) == '' ? NULL : str_replace(',', '', $_POST['x31']));

$salt_accumulation  		= (str_replace(',', '', $_POST['x32']) == '' ? NULL : str_replace(',', '', $_POST['x32']));

$nutsedge  					= (str_replace(',', '', $_POST['x33']) == '' ? NULL : str_replace(',', '', $_POST['x33']));

$buds  						= (str_replace(',', '', $_POST['x34']) == '' ? NULL : str_replace(',', '', $_POST['x34']));

$zigzag_stems  				= (str_replace(',', '', $_POST['x35']) == '' ? NULL : str_replace(',', '', $_POST['x35']));

$nutrient_deficiency  		= (str_replace(',', '', $_POST['x36']) == '' ? NULL : str_replace(',', '', $_POST['x36']));

$round_up  					= (str_replace(',', '', $_POST['x37']) == '' ? NULL : str_replace(',', '', $_POST['x37']));

$light_color  				= (str_replace(',', '', $_POST['x38']) == '' ? NULL : str_replace(',', '', $_POST['x38']));

$mealybugs  				= (str_replace(',', '', $_POST['x39']) == '' ? NULL : str_replace(',', '', $_POST['x39']));

$thrips  					= (str_replace(',', '', $_POST['x40']) == '' ? NULL : str_replace(',', '', $_POST['x40']));



$fecha_exploracion = DateTime::createFromFormat("m-d-Y", $fecha_exploracion);



$fecha_exploracion = $fecha_exploracion->format('Y-m-d');



$result = $DB_PLANT->plan_guardar_exploracion(
	$codigo_detalle,

	$etapa_crecimiento,

	$worms,

	$eggs,

	$leafhoppers,

	$aphids,

	$stink_bugs,

	$gnats,

	$flea_beetles,

	$cyclaman_mites,

	$cercospora_leaf_spot,

	$pythium,

	$rhizoctonia_aerial_blight,

	$bacteria,

	$sclerotinia,

	$alternaria_specks,

	$mildew,

	$virus,

	$dollarweed,

	$frogs_bit,

	$mud_plantain,

	$tube_weed,

	$grass,

	$damaged_leaves,

	$purple_stem,

	$watercress_rooter,

	$observaciones_exploracion,

	$codigo_plantacion,

	$fecha_exploracion,

	$spidermites,

	$white_rust,

	$salt_accumulation,

	$nutsedge,

	$buds,

	$zigzag_stems,

	$nutrient_deficiency,

	$round_up,

	$light_color,

	$mealybugs,

	$thrips,

	$_SESSION['cod_usuario']
);

echo utf8_encode($result[0]['mensaje']);

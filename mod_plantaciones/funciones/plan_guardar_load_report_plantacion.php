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

$codigo_plantacion						= trim($_POST['x1']);
$cod_bloques_load_report				= implode(',',$_POST['x2']);
$cod_inventario_load_report				= trim($_POST['x3']);
$num_orden_compra  						= (trim($_POST['x4']) == '' ? NULL:trim(utf8_decode($_POST['x4'])));
$fecha_orden							= (trim($_POST['x5']) == '' ? NULL:trim(utf8_decode($_POST['x5'])));
$num_lote  								= (trim($_POST['x6']) == '' ? NULL:trim(utf8_decode($_POST['x6'])));
$num_lote_ranch  						= (trim($_POST['x7']) == '' ? NULL:trim(utf8_decode($_POST['x7'])));
$inicial_size_harvest					= (trim($_POST['x8']) == '' ? NULL:trim(utf8_decode($_POST['x8'])));
$final_size_harvest						= (trim($_POST['x9']) == '' ? NULL:trim(utf8_decode($_POST['x9'])));
$dark_green_color						= (trim($_POST['x10']) == '' ? NULL:trim(utf8_decode($_POST['x10'])));
$yellow_leaves							= (trim($_POST['x11']) == '' ? NULL:trim(utf8_decode($_POST['x11'])));
$weeds									= (trim($_POST['x12']) == '' ? NULL:trim(utf8_decode($_POST['x12'])));
$optimal_soil_water_capacity			= (trim($_POST['x13']) == '' ? NULL:trim(utf8_decode($_POST['x13'])));
$right_density							= (trim($_POST['x14']) == '' ? NULL:trim(utf8_decode($_POST['x14'])));
$other_defects							= (utf8_decode($_POST['x15']) == '' ? NULL:trim(utf8_decode($_POST['x15'])));
$size_range_porcentage					= (trim($_POST['x16']) == '' ? NULL:trim(utf8_decode($_POST['x16'])));
$inicial_size_range						= (trim($_POST['x17']) == '' ? NULL:trim(utf8_decode($_POST['x17'])));
$final_size_range						= (trim($_POST['x18']) == '' ? NULL:trim(utf8_decode($_POST['x18'])));
$select_size_range						= (utf8_decode($_POST['x19']) == '-b' ? NULL:trim(utf8_decode($_POST['x19'])));
$size_range1							= (trim($_POST['x20']) == '' ? NULL:trim(utf8_decode($_POST['x20'])));
$select_size_range1						= (utf8_decode($_POST['x21']) == '-b' ? NULL:trim(utf8_decode($_POST['x21'])));
$size_range2							= (trim($_POST['x22']) == '' ? NULL:trim(utf8_decode($_POST['x22'])));
$select_size_range2						= (utf8_decode($_POST['x23']) == '-b' ? NULL:trim(utf8_decode($_POST['x23'])));
$other_defects_ha						= (trim($_POST['x24']) == '' ? NULL:trim(utf8_decode($_POST['x24'])));
$select_other_defects_ha				= (utf8_decode($_POST['x25']) == '-b' ? NULL:trim(utf8_decode($_POST['x25'])));
$dew_leaf								= (utf8_decode($_POST['x26']) == '-b' ? NULL:trim(utf8_decode($_POST['x26'])));
$inicial_time_harvest					= (trim($_POST['x27']) == '' ? NULL:trim(utf8_decode($_POST['x27'])));
$final_time_harvest						= (trim($_POST['x28']) == '' ? NULL:trim(utf8_decode($_POST['x28'])));
$temperature_product					= (trim($_POST['x29']) == '' ? NULL:trim(utf8_decode($_POST['x29'])));
$inicial_average_tote_weight_reported	= (trim($_POST['x30']) == '' ? NULL:trim(utf8_decode($_POST['x30'])));
$final_average_tote_weight_reported		= (trim($_POST['x31']) == '' ? NULL:trim(utf8_decode($_POST['x31'])));
$real_average_tote_weight				= (trim($_POST['x32']) == '' ? NULL:trim(utf8_decode($_POST['x32'])));
$time_receiving							= (trim($_POST['x33']) == '' ? NULL:trim(utf8_decode($_POST['x33'])));
$total_load_lbs_goal					= (trim($_POST['x34']) == '' ? NULL:trim(utf8_decode($_POST['x34'])));
$load_weight_received					= (trim($_POST['x35']) == '' ? NULL:trim(utf8_decode($_POST['x35'])));
$average_tote_weight					= (trim($_POST['x36']) == '' ? NULL:trim(utf8_decode($_POST['x36'])));
$temperature_receiving					= (trim($_POST['x37']) == '' ? NULL:trim(utf8_decode($_POST['x37'])));
$time_vacuum_cooler						= (trim($_POST['x38']) == '' ? NULL:trim(utf8_decode($_POST['x38'])));
$temperature_vacuum_cooler				= (trim($_POST['x39']) == '' ? NULL:trim(utf8_decode($_POST['x39'])));
$hydrocooling							= (utf8_decode($_POST['x40']) == '-b' ? NULL:trim(utf8_decode($_POST['x40'])));
$time_pickup							= (trim($_POST['x41']) == '' ? NULL:trim(utf8_decode($_POST['x41'])));
$tlc									= (trim($_POST['x42']) == '' ? NULL:trim(utf8_decode($_POST['x42'])));
$vacuum_cooler							= (trim($_POST['x43']) == '' ? NULL:trim(utf8_decode($_POST['x43'])));
$total_temperature						= (trim($_POST['x44']) == '' ? NULL:trim(utf8_decode($_POST['x44'])));
$pickup_truck_checkin					= (trim($_POST['x45']) == '' ? NULL:trim(utf8_decode($_POST['x45'])));
$number_cut								= (utf8_decode($_POST['x46']) == '-b' ? NULL:trim(utf8_decode($_POST['x46'])));
$codigo_reporte							= trim($_POST['x47']);

$fecha_orden = DateTime::createFromFormat("m-d-Y H:i:s" , $fecha_orden);

$fecha_orden = $fecha_orden->format('Y-m-d H:i:s');

if($inicial_time_harvest != NULL)
{
	$inicial_time_harvest = DateTime::createFromFormat("m-d-Y H:i:s" , $inicial_time_harvest);

	$inicial_time_harvest = $inicial_time_harvest->format('Y-m-d H:i:s');
}

if($final_time_harvest != NULL)
{
	$final_time_harvest = DateTime::createFromFormat("m-d-Y H:i:s" , $final_time_harvest);

	$final_time_harvest = $final_time_harvest->format('Y-m-d H:i:s');
}

if($time_receiving != NULL)
{
	$time_receiving = DateTime::createFromFormat("m-d-Y H:i:s" , $time_receiving);

	$time_receiving = $time_receiving->format('Y-m-d H:i:s');
}

if($time_vacuum_cooler != NULL)
{
	$time_vacuum_cooler = DateTime::createFromFormat("m-d-Y H:i:s" , $time_vacuum_cooler);

	$time_vacuum_cooler = $time_vacuum_cooler->format('Y-m-d H:i:s');
}

if($time_pickup != NULL)
{
	$time_pickup = DateTime::createFromFormat("m-d-Y H:i:s" , $time_pickup);

	$time_pickup = $time_pickup->format('Y-m-d H:i:s');
}

if($pickup_truck_checkin != NULL)
{
	$pickup_truck_checkin = DateTime::createFromFormat("m-d-Y H:i:s" , $pickup_truck_checkin);

	$pickup_truck_checkin = $pickup_truck_checkin->format('Y-m-d H:i:s');
}




$result = $DB_PLANT->plan_guardar_load_report_plantacion($codigo_plantacion,
																$cod_bloques_load_report,
																$cod_inventario_load_report,
																$num_orden_compra,
																$fecha_orden,
																$num_lote,
																$num_lote_ranch,
																$inicial_size_harvest,
																$final_size_harvest,
																$dark_green_color,
																$yellow_leaves,
																$weeds,
																$optimal_soil_water_capacity,
																$right_density,
																$other_defects,
																$size_range_porcentage,
																$inicial_size_range,
																$final_size_range,
																$select_size_range,
																$size_range1,
																$select_size_range1,
																$size_range2,
																$select_size_range2,
																$other_defects_ha,
																$select_other_defects_ha,
																$dew_leaf,
																$inicial_time_harvest,
																$final_time_harvest,
																$temperature_product,
																$inicial_average_tote_weight_reported,
																$final_average_tote_weight_reported,
																$real_average_tote_weight,
																$time_receiving,
																$total_load_lbs_goal,
																$load_weight_received,
																$average_tote_weight,
																$temperature_receiving,
																$time_vacuum_cooler,
																$temperature_vacuum_cooler,
																$hydrocooling,
																$time_pickup,
																$tlc,
																$vacuum_cooler,
																$total_temperature,
																$pickup_truck_checkin,
																$number_cut,
																$codigo_reporte,
																$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

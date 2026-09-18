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
include_once("../../libs/db_classes/db_control_calidad.php");
/*INSTANCIAMIENTOS*/
$DB_CONTROL = new db_control_calidad();

$codigo_reporte							= trim($_POST['x1']);
$cod_producto							= trim($_POST['x3']);
$num_orden_compra  						= (trim($_POST['x4']) == '' || trim($_POST['x4']) == '-b' ? NULL:trim(($_POST['x4'])));
$fecha_orden							= (trim($_POST['x5']) == '' || trim($_POST['x5']) == '-b' ? NULL:trim(($_POST['x5'])));
$num_lote  								= (trim($_POST['x6']) == '' || trim($_POST['x6']) == '-b' ? NULL:trim(($_POST['x6'])));
$num_lote_ranch  						= (trim($_POST['x7']) == '' || trim($_POST['x7']) == '-b' ? NULL:trim(($_POST['x7'])));
$inicial_size_harvest					= (trim($_POST['x8']) == '' || trim($_POST['x8']) == '-b' ? NULL:trim(($_POST['x8'])));
$final_size_harvest						= (trim($_POST['x9']) == '' || trim($_POST['x9']) == '-b' ? NULL:trim(($_POST['x9'])));
$dark_green_color						= (trim($_POST['x10']) == '' || trim($_POST['x10']) == '-b' ? NULL:trim(($_POST['x10'])));
$yellow_leaves							= (trim($_POST['x11']) == '' || trim($_POST['x11']) == '-b' ? NULL:trim(($_POST['x11'])));
$weeds									= (trim($_POST['x12']) == '' || trim($_POST['x12']) == '-b' ? NULL:trim(($_POST['x12'])));
$optimal_soil_water_capacity			= (trim($_POST['x13']) == '' || trim($_POST['x13']) == '-b' ? NULL:trim(($_POST['x13'])));
$right_density							= (trim($_POST['x14']) == '' || trim($_POST['x14']) == '-b' ? NULL:trim(($_POST['x14'])));
$other_defects							= (utf8_decode($_POST['x15']) == '' || utf8_decode($_POST['x15']) == '-b' ? NULL:trim(utf8_decode($_POST['x15'])));
$size_range_porcentage					= (trim($_POST['x16']) == '' || trim($_POST['x16']) == '-b' ? NULL:trim(($_POST['x16'])));
$inicial_size_range						= (trim($_POST['x17']) == '' || trim($_POST['x17']) == '-b' ? NULL:trim(($_POST['x17'])));
$final_size_range						= (trim($_POST['x18']) == '' || trim($_POST['x18']) == '-b' ? NULL:trim(($_POST['x18'])));
$select_size_range						= (($_POST['x19']) == '-b' || ($_POST['x19']) == '' ? NULL:trim(($_POST['x19'])));
$size_range1							= (trim($_POST['x20']) == '' || trim($_POST['x20']) == '-b' ? NULL:trim(($_POST['x20'])));
$select_size_range1						= (($_POST['x21']) == '-b' || ($_POST['x21']) == '' ? NULL:trim(($_POST['x21'])));
$size_range2							= (trim($_POST['x22']) == '' || trim($_POST['x22']) == '-b' ? NULL:trim(($_POST['x22'])));
$select_size_range2						= (($_POST['x23']) == '-b' || ($_POST['x23']) == '' ? NULL:trim(($_POST['x23'])));
$other_defects_ha						= (trim($_POST['x24']) == '' || trim($_POST['x24']) == '-b' ? NULL:trim(($_POST['x24'])));
$select_other_defects_ha				= (($_POST['x25']) == '-b' || ($_POST['x25']) == '' ? NULL:trim(($_POST['x25'])));
$dew_leaf								= (($_POST['x26']) == '-b' || ($_POST['x26']) == '' ? NULL:trim(($_POST['x26'])));
$inicial_time_harvest					= (trim($_POST['x27']) == '' || trim($_POST['x27']) == '-b' ? NULL:trim(($_POST['x27'])));
$final_time_harvest						= (trim($_POST['x28']) == '' || trim($_POST['x28']) == '-b' ? NULL:trim(($_POST['x28'])));
$temperature_product					= (trim($_POST['x29']) == '' || trim($_POST['x29']) == '-b' ? NULL:trim(($_POST['x29'])));
$inicial_average_tote_weight_reported	= NULL;//(trim($_POST['x30']) == '' || trim($_POST['x30']) == '-b' ? NULL:trim(($_POST['x30'])));
$final_average_tote_weight_reported		= NULL;//(trim($_POST['x31']) == '' || trim($_POST['x31']) == '-b' ? NULL:trim(($_POST['x31'])));
$real_average_tote_weight				= NULL;//(trim($_POST['x32']) == '' || trim($_POST['x32']) == '-b' ? NULL:trim(($_POST['x32'])));
$time_receiving							= (trim($_POST['x33']) == '' || trim($_POST['x33']) == '-b' ? NULL:trim(($_POST['x33'])));
$total_load_lbs_goal					= (trim($_POST['x34']) == '' || trim($_POST['x34']) == '-b' ? NULL:trim(($_POST['x34'])));
$load_weight_received					= (trim($_POST['x35']) == '' || trim($_POST['x35']) == '-b' ? NULL:trim(($_POST['x35'])));
$average_tote_weight					= (trim($_POST['x36']) == '' || trim($_POST['x36']) == '-b' ? NULL:trim(($_POST['x36'])));
$temperature_receiving					= (trim($_POST['x37']) == '' || trim($_POST['x37']) == '-b' ? NULL:trim(($_POST['x37'])));
$time_vacuum_cooler						= (trim($_POST['x38']) == '' || trim($_POST['x38']) == '-b' ? NULL:trim(($_POST['x38'])));
$temperature_vacuum_cooler				= (trim($_POST['x39']) == '' || trim($_POST['x39']) == '-b' ? NULL:trim(($_POST['x39'])));
$hydrocooling							= (($_POST['x40']) == '-b' || ($_POST['x40']) == '' ? NULL:trim(($_POST['x40'])));
$time_pickup							= (trim($_POST['x41']) == '' || trim($_POST['x41']) == '-b' ? NULL:trim(($_POST['x41'])));
$tlc									= (trim($_POST['x42']) == '' || trim($_POST['x42']) == '-b' ? NULL:trim(($_POST['x42'])));
$vacuum_cooler							= (trim($_POST['x43']) == '' || trim($_POST['x43']) == '-b' ? NULL:trim(($_POST['x43'])));
$total_temperature						= (trim($_POST['x44']) == '' || trim($_POST['x44']) == '-b' ? NULL:trim(($_POST['x44'])));
$pickup_truck_checkin					= NULL;//(trim($_POST['x45']) == '' || trim($_POST['x45']) == '-b' ? NULL:trim(($_POST['x45'])));
$number_cut								= (($_POST['x46']) == '-b' || ($_POST['x46']) == '' ? NULL:trim(($_POST['x46'])));
$acres_cosechados						= (($_POST['x47']) == '-b' || ($_POST['x47']) == '' ? NULL:trim(($_POST['x47'])));
$comentarios							= (utf8_decode($_POST['x48']) == '-b' || utf8_decode($_POST['x48']) == '' ? NULL:trim(utf8_decode($_POST['x48'])));
$select_yellow_leaves					= (($_POST['x49']) == '-b' || ($_POST['x49']) == '' ? NULL:trim(($_POST['x49'])));
$yellow_leaves2							= (trim($_POST['x50']) == '' || trim($_POST['x50']) == '-b' ? NULL:trim(($_POST['x50'])));

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


$result = $DB_CONTROL->qua_guardar_load_report($cod_producto,
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
												$acres_cosechados,
												$comentarios,
												$select_yellow_leaves,
												$yellow_leaves2,
												$_SESSION['cod_usuario']);
echo utf8_encode($result[0]['mensaje']);
?>

<?PHP
/*
 * Genera el listado de fichas para exportar a excel.
 * @author      Mariel Umanzor
 * @date        2014-12-15
 */
ob_end_clean();
ob_start();
session_start();
/*CONEXION CON BASE DE DATOS*/
include_once "../../libs/db_classes/db_mysql_conn.php";
include_once "../../libs/db_classes/db_control_calidad.php";
/*INSTANCIAMIENTOS*/
$DB_CONTROL             = new db_control_calidad();
$fecha_inicial       = $_GET['x1'];
$fecha_final         = $_GET['x2'];

$PLANTACIONES = $DB_CONTROL->qua_listado_reporte_load_report($fecha_inicial,$fecha_final);
/* EXCEL */
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
date_default_timezone_set('Europe/London');
if (PHP_SAPI == 'cli') {
    die('This example should only be run from a Web Browser');
}

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../libs/PHPExcel/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$worksheet = $objPHPExcel->getActiveSheet();
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B3', 'No.')
    ->setCellValue('C3', 'Product')
    ->setCellValue('D3', 'Order #')
    ->setCellValue('E3', 'Order Date')
    ->setCellValue('F3', 'Order Time')
    ->setCellValue('G3', 'Lot code')
    ->setCellValue('H3', 'Ranch lot code')
    ->setCellValue('I3', 'Initial Size')
    ->setCellValue('J3', 'Final Size')
    ->setCellValue('K3', 'Dark green color')
    ->setCellValue('L3', 'Yellow leaves')
    ->setCellValue('M3', 'Weeds')
    ->setCellValue('N3', 'Optimal soil water capacity')
    ->setCellValue('O3', 'Right density')
    ->setCellValue('P3', '% Other defects')
    ->setCellValue('Q3', 'Percentage Size Range')
    ->setCellValue('R3', 'On/Out Spec')
    ->setCellValue('S3', 'Initial size range')
    ->setCellValue('T3', 'FInal size range')
    ->setCellValue('U3', '% Above Size Range')
    ->setCellValue('V3', 'On/Out Spec')
    ->setCellValue('W3', '% Below Size Range')
    ->setCellValue('X3', 'On/Out Spec')
    ->setCellValue('Y3', 'Other defects')
    ->setCellValue('Z3', 'On/Out Spec')
    ->setCellValue('AA3', 'Yellow leaves')
    ->setCellValue('AB3', 'On/Out Spec')
    ->setCellValue('AC3', 'Comments')
    ->setCellValue('AD3', 'Dew of leaf')
    ->setCellValue('AE3', 'Initial Date of harvest')
    ->setCellValue('AF3', 'Initial Time of harvest')
    ->setCellValue('AG3', 'Final Date of harvest')
    ->setCellValue('AH3', 'Final Time of harvest')
    ->setCellValue('AI3', 'Temperature of product')
    ->setCellValue('AJ3', 'Date of receiving')
    ->setCellValue('AK3', 'Time of receiving')
    ->setCellValue('AL3', 'Total load lbs goal')
    ->setCellValue('AM3', 'Load weight receiving')
    ->setCellValue('AN3', 'Average tote weight')
    ->setCellValue('AO3', 'Temperature of receiving')
    ->setCellValue('AP3', 'Date of VC')
    ->setCellValue('AQ3', 'Time of VC')
    ->setCellValue('AR3', 'Temperature of VC')
    ->setCellValue('AS3', 'Hydrocooling')
    ->setCellValue('AT3', 'Date of pick Up')
    ->setCellValue('AU3', 'Time of pick Up')
    ->setCellValue('AV3', 'TLC')
    ->setCellValue('AW3', 'Vacuum Cooler')
    ->setCellValue('AX3', 'Total')
    ->setCellValue('AY3', 'Cut')
    ->setCellValue('AZ3', 'Acres Harvested')
    ->setCellValue('BA3', 'System Date')
    ->setCellValue('BB3', 'System Time');
// Titulo Dirección
$worksheet->mergeCells('B1:BB1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:BB1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:BB2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:BB2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: Load Report from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:BB3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:BB3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:BB3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:BB3')->getFont()->setBold(true);
$worksheet->getColumndimension('A')->setWidth(5);
$worksheet->getColumndimension('B')->setWidth(25);
$worksheet->getColumndimension('C')->setWidth(25);
$worksheet->getColumndimension('D')->setWidth(15);
$worksheet->getColumndimension('E')->setWidth(25);
$worksheet->getColumndimension('F')->setWidth(40);
$worksheet->getColumndimension('G')->setWidth(40);
$worksheet->getColumndimension('H')->setWidth(40);
$worksheet->getColumndimension('I')->setWidth(40);
$worksheet->getColumndimension('J')->setWidth(40);
$worksheet->getColumndimension('K')->setWidth(40);
$worksheet->getColumndimension('L')->setWidth(40);
$worksheet->getColumndimension('M')->setWidth(40);
$worksheet->getColumndimension('N')->setWidth(40);
$worksheet->getColumndimension('O')->setWidth(40);
$worksheet->getColumndimension('P')->setWidth(40);
$worksheet->getColumndimension('Q')->setWidth(40);
$worksheet->getColumndimension('R')->setWidth(40);
$worksheet->getColumndimension('S')->setWidth(40);
$worksheet->getColumndimension('T')->setWidth(40);
$worksheet->getColumndimension('U')->setWidth(40);
$worksheet->getColumndimension('V')->setWidth(40);
$worksheet->getColumndimension('W')->setWidth(40);
$worksheet->getColumndimension('X')->setWidth(40);
$worksheet->getColumndimension('Y')->setWidth(40);
$worksheet->getColumndimension('Z')->setWidth(40);
$worksheet->getColumndimension('AA')->setWidth(40);
$worksheet->getColumndimension('AB')->setWidth(40);
$worksheet->getColumndimension('AC')->setWidth(40);
$worksheet->getColumndimension('AD')->setWidth(40);
$worksheet->getColumndimension('AE')->setWidth(40);
$worksheet->getColumndimension('AF')->setWidth(40);
$worksheet->getColumndimension('AG')->setWidth(40);
$worksheet->getColumndimension('AH')->setWidth(40);
$worksheet->getColumndimension('AI')->setWidth(40);
$worksheet->getColumndimension('AJ')->setWidth(40);
$worksheet->getColumndimension('AK')->setWidth(40);
$worksheet->getColumndimension('AL')->setWidth(40);
$worksheet->getColumndimension('AM')->setWidth(40);
$worksheet->getColumndimension('AN')->setWidth(40);
$worksheet->getColumndimension('AO')->setWidth(40);
$worksheet->getColumndimension('AP')->setWidth(40);
$worksheet->getColumndimension('AQ')->setWidth(40);
$worksheet->getColumndimension('AR')->setWidth(40);
$worksheet->getColumndimension('AS')->setWidth(40);
$worksheet->getColumndimension('AT')->setWidth(40);
$worksheet->getColumndimension('AU')->setWidth(40);
$worksheet->getColumndimension('AV')->setWidth(40);
$worksheet->getColumndimension('AW')->setWidth(40);
$worksheet->getColumndimension('AX')->setWidth(40);
$worksheet->getColumndimension('AY')->setWidth(40);
$worksheet->getColumndimension('AZ')->setWidth(40);
$worksheet->getColumndimension('BA')->setWidth(40);
$worksheet->getColumndimension('BB')->setWidth(40);
$fila = 4;

if (count($PLANTACIONES) > 0) {
    $correlativo = 1;
    $loose_bunches = [ 0=>'Bunch',1=>'Loose',NULL=>''];
    $spec = [1=>'% on spec', 0=>'% out of spec',NULL=>''];
    $cut = [0=>'',1=>'First Cut', 2=>'Second Cut', 3=>'Third Cut',NULL=>''];
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($PLANTACIONES as $plantacion) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($plantacion['nombre_producto']))
            ->setCellValue('D' . $fila, utf8_encode($plantacion['num_orden_compra']))
            ->setCellValue('E' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['fecha_orden'])->format("m-d-Y")))
            ->setCellValue('F' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['fecha_orden'])->format("h:i:sa")))
            ->setCellValue('G' . $fila, utf8_encode($plantacion['num_lote']))
            ->setCellValue('H' . $fila, utf8_encode($plantacion['num_lote_ranch']))
            ->setCellValue('I' . $fila, utf8_encode($plantacion['inicial_size_harvest']))
            ->setCellValue('J' . $fila, utf8_encode($plantacion['final_size_harvest']))
            ->setCellValue('K' . $fila, utf8_encode($plantacion['dark_green_color']))
            ->setCellValue('L' . $fila, utf8_encode($plantacion['yellow_leaves']))
            ->setCellValue('M' . $fila, utf8_encode($plantacion['weeds']))
            ->setCellValue('N' . $fila, utf8_encode($plantacion['optimal_soil_water_capacity']))
            ->setCellValue('O' . $fila, utf8_encode($plantacion['right_density']))
            ->setCellValue('P' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', $plantacion['other_defects'])))))
            ->setCellValue('Q' . $fila, utf8_encode($plantacion['size_range_porcentage']))
            ->setCellValue('R' . $fila, utf8_encode($spec[$plantacion['select_size_range']]))
            ->setCellValue('S' . $fila, utf8_encode($plantacion['inicial_size_range']))
            ->setCellValue('T' . $fila, utf8_encode($plantacion['final_size_range']))
            ->setCellValue('U' . $fila, utf8_encode($plantacion['size_range1']))
            ->setCellValue('V' . $fila, utf8_encode($spec[$plantacion['select_size_range1']]))
            ->setCellValue('W' . $fila, utf8_encode($plantacion['size_range2']))
            ->setCellValue('X' . $fila, utf8_encode($spec[$plantacion['select_size_range2']]))
            ->setCellValue('Y' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', $plantacion['other_defects_ha'])))))
            ->setCellValue('Z' . $fila, utf8_encode($spec[$plantacion['select_other_defects_ha']]))
            ->setCellValue('AA' . $fila, utf8_encode($plantacion['yellow_leaves2']))
            ->setCellValue('AB' . $fila, utf8_encode($spec[$plantacion['select_yellow_leaves']]))
            ->setCellValue('AC' . $fila, utf8_encode($plantacion['comentarios']))
            ->setCellValue('AD' . $fila, utf8_encode($plantacion['dew_leaf'] == 1 ? 'Yes':'No'))
            ->setCellValue('AE' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['inicial_time_harvest'])->format("m-d-Y")))
            ->setCellValue('AF' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['inicial_time_harvest'])->format("h:i:sa")))
            ->setCellValue('AG' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['final_time_harvest'])->format("m-d-Y")))
            ->setCellValue('AH' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['final_time_harvest'])->format("h:i:sa")))
            ->setCellValue('AI' . $fila, utf8_encode($plantacion['temperature_product']))
            ->setCellValue('AJ' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['time_receiving'])->format("m-d-Y")))
            ->setCellValue('AK' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['time_receiving'])->format("h:i:sa")))
            ->setCellValue('AL' . $fila, utf8_encode($plantacion['total_load_lbs_goal']))
            ->setCellValue('AM' . $fila, utf8_encode($plantacion['load_weight_received']))
            ->setCellValue('AN' . $fila, utf8_encode($plantacion['average_tote_weight']))
            ->setCellValue('AO' . $fila, utf8_encode($plantacion['temperature_receiving']))
            ->setCellValue('AP' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['time_vacuum_cooler'])->format("m-d-Y")))
            ->setCellValue('AQ' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['time_vacuum_cooler'])->format("h:i:sa")))
            ->setCellValue('AR' . $fila, utf8_encode($plantacion['temperature_vacuum_cooler']))
            ->setCellValue('AS' . $fila, utf8_encode($plantacion['hydrocooling'] == 1 ? 'Yes':'No'))
            ->setCellValue('AT' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['time_pickup'])->format("m-d-Y")))
            ->setCellValue('AU' . $fila, utf8_encode(DateTime::createFromFormat("m-d-Y H:i:s", $plantacion['time_pickup'])->format("h:i:sa")))
            ->setCellValue('AV' . $fila, utf8_encode($plantacion['tlc']))
            ->setCellValue('AW' . $fila, utf8_encode($plantacion['vacuum_cooler']))
            ->setCellValue('AX' . $fila, utf8_encode($plantacion['tlc'] + $plantacion['vacuum_cooler']))
            ->setCellValue('AY' . $fila, utf8_encode($plantacion['number_cut'] == NULL || $plantacion['number_cut'] == '' ? '':$cut[$plantacion['number_cut']]))
            ->setCellValue('AZ' . $fila, utf8_encode($plantacion['acres_cosechados']))
            ->setCellValue('BA' . $fila, date('m-d-Y',strtotime($plantacion['date_insert'])))
            ->setCellValue('BB' . $fila, date("h:i:sa",strtotime($plantacion['date_insert'])));
        if ($plantacion['dark_green_color'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['dark_green_color'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['dark_green_color'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['yellow_leaves'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['yellow_leaves'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['yellow_leaves'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['weeds'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['weeds'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['weeds'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['optimal_soil_water_capacity'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['optimal_soil_water_capacity'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['optimal_soil_water_capacity'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('N' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['right_density'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['right_density'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['right_density'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('O' . $fila)->getFont()->setBold(true);            
        }
        $fila += 1;
        $correlativo += 1;
    }
}
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$fila = $fila - 1;
$objPHPExcel->getActiveSheet()->getStyle('B1:BB' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B1:BB'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:BB'.$fila)->getAlignment()->setWrapText(true);

/*Establece formatos de número a celdas*/
$worksheet->getStyle('I4:O'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('Q4:Q'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('S4:S'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('U4:U'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('W4:W'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('Y4:Y'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('AF4:AF'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('AI4:AL'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('AO4:AO'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('AS4:AU'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('AW4:AX'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Load Report');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Load Report.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
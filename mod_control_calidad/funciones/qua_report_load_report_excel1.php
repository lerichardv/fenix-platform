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
    ->setCellValue('F3', 'Lot code')
    ->setCellValue('G3', 'Ranch lot code')
    ->setCellValue('H3', 'Initial Size at harvest')
    ->setCellValue('I3', 'Final Size at harvest')
    ->setCellValue('J3', 'Dark green color')
    ->setCellValue('K3', 'Yellow leaves')
    ->setCellValue('L3', 'Weeds')
    ->setCellValue('M3', 'Optimal soil water capacity')
    ->setCellValue('N3', 'Right density')
    ->setCellValue('O3', 'Other defects')
    ->setCellValue('P3', 'Percentage Size Range on Spec')
    ->setCellValue('Q3', 'Initial size range')
    ->setCellValue('R3', 'FInal size range')
    ->setCellValue('S3', '% Above Size Range')
    ->setCellValue('T3', '% Below Size Range')
    ->setCellValue('U3', 'Other defects')
    ->setCellValue('V3', 'Dew of leaf')
    ->setCellValue('W3', 'Time of harvest')
    ->setCellValue('X3', 'Temperature of product')/*
    ->setCellValue('V3', 'Average tote weight reported')
    ->setCellValue('W3', 'Real average tote weight')*/
    ->setCellValue('Y3', 'Time of receiving')
    ->setCellValue('Z3', 'Total load lbs goal')
    ->setCellValue('AA3', 'Load weight receiving')
    ->setCellValue('AB3', 'Average tote weight')
    ->setCellValue('AC3', 'Temperature of receiving')
    ->setCellValue('AD3', 'Time of VC')
    ->setCellValue('AE3', 'Temperature of VC')
    ->setCellValue('AF3', 'Hydrocooling')
    ->setCellValue('AG3', 'Time of pick Up')
    ->setCellValue('AH3', 'TLC')
    ->setCellValue('AI3', 'Vacuum Cooler')
    ->setCellValue('AK3', 'Total')/*
    ->setCellValue('AJ3', 'Pickup truck checkin')*/
    ->setCellValue('AK3', 'Cut')
    ->setCellValue('AL3', 'Acres Harvested')
    ->setCellValue('AM3', 'Comments')
    ->setCellValue('AN3', 'Date');

// Titulo Dirección
$worksheet->mergeCells('B1:AN1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:AN1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:AN2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:AN2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: Load Order Assessment from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:AN3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:AN3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:AN3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:AN3')->getFont()->setBold(true);
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
$worksheet->getColumndimension('AL')->setWidth(40);/*
$worksheet->getColumndimension('AL')->setWidth(40);
$worksheet->getColumndimension('AM')->setWidth(40);
$worksheet->getColumndimension('AN')->setWidth(40);*/
$fila = 4;

if (count($PLANTACIONES) > 0) {
    $correlativo = 1;
    $loose_bunches = [ 0=>'Bunch',1=>'Loose',NULL=>''];
    $spec = [0=>'% on spec', 2=>'% out of spec',NULL=>''];
    $cut = [0=>'',1=>'First Cut', 2=>'Second Cut', 3=>'Third Cut',NULL=>''];
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($PLANTACIONES as $plantacion) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($plantacion['nombre_producto']))
            ->setCellValue('D' . $fila, utf8_encode($plantacion['num_orden_compra']))
            ->setCellValue('E' . $fila, utf8_encode($plantacion['fecha_orden']))
            ->setCellValue('F' . $fila, utf8_encode($plantacion['num_lote']))
            ->setCellValue('G' . $fila, utf8_encode($plantacion['num_lote_ranch']))
            ->setCellValue('H' . $fila, utf8_encode($plantacion['inicial_size_harvest']))
            ->setCellValue('I' . $fila, utf8_encode($plantacion['final_size_harvest']))
            ->setCellValue('J' . $fila, utf8_encode($plantacion['dark_green_color']))
            ->setCellValue('K' . $fila, utf8_encode($plantacion['yellow_leaves']))
            ->setCellValue('L' . $fila, utf8_encode($plantacion['weeds']))
            ->setCellValue('M' . $fila, utf8_encode($plantacion['optimal_soil_water_capacity']))
            ->setCellValue('N' . $fila, utf8_encode($plantacion['right_density']))
            ->setCellValue('O' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', $plantacion['other_defects'])))))
            ->setCellValue('P' . $fila, utf8_encode($plantacion['size_range_porcentage'].'% '.$spec[$plantacion['select_size_range']]))
            ->setCellValue('Q' . $fila, utf8_encode($plantacion['inicial_size_range']))
            ->setCellValue('R' . $fila, utf8_encode($plantacion['final_size_range']))
            ->setCellValue('S' . $fila, utf8_encode($plantacion['size_range1'].' '.$spec[$plantacion['select_size_range1']]))
            ->setCellValue('T' . $fila, utf8_encode($plantacion['size_range2'].' '.$spec[$plantacion['select_size_range2']]))
            ->setCellValue('U' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', $plantacion['other_defects_ha'].' '.$spec[$plantacion['select_other_defects_ha']])))))
            ->setCellValue('V' . $fila, utf8_encode($plantacion['dew_leaf'] == 1 ? 'Yes':'No'))
            ->setCellValue('W' . $fila, utf8_encode($plantacion['inicial_time_harvest'].' - '.$plantacion['final_time_harvest']))
            ->setCellValue('X' . $fila, utf8_encode($plantacion['temperature_product']))
            ->setCellValue('Y' . $fila, utf8_encode($plantacion['time_receiving']))
            ->setCellValue('Z' . $fila, utf8_encode($plantacion['total_load_lbs_goal']))
            ->setCellValue('AA' . $fila, utf8_encode($plantacion['load_weight_received']))
            ->setCellValue('AB' . $fila, utf8_encode($plantacion['average_tote_weight']))
            ->setCellValue('AC' . $fila, utf8_encode($plantacion['temperature_receiving']))
            ->setCellValue('AD' . $fila, utf8_encode($plantacion['time_vacuum_cooler']))
            ->setCellValue('AE' . $fila, utf8_encode($plantacion['temperature_vacuum_cooler']))
            ->setCellValue('AF' . $fila, utf8_encode($plantacion['hydrocooling'] == 1 ? 'Yes':'No'))
            ->setCellValue('AG' . $fila, utf8_encode($plantacion['time_pickup']))
            ->setCellValue('AH' . $fila, utf8_encode($plantacion['tlc']))
            ->setCellValue('AI' . $fila, utf8_encode($plantacion['vacuum_cooler']))
            ->setCellValue('AJ' . $fila, utf8_encode($plantacion['tlc'] + $plantacion['vacuum_cooler']))
            ->setCellValue('AK' . $fila, utf8_encode($plantacion['number_cut'] == NULL || $plantacion['number_cut'] == '' ? '':$cut[$plantacion['number_cut']]))
            ->setCellValue('AL' . $fila, utf8_encode($plantacion['acres_cosechados']))
            ->setCellValue('AM' . $fila, utf8_encode($plantacion['comentarios']))
            ->setCellValue('AN' . $fila, utf8_encode($plantacion['date_insert']));

        if ($plantacion['dark_green_color'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['dark_green_color'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['dark_green_color'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('I' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['yellow_leaves'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['yellow_leaves'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['yellow_leaves'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('J' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['weeds'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['weeds'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['weeds'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('K' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['optimal_soil_water_capacity'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['optimal_soil_water_capacity'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['optimal_soil_water_capacity'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('L' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['right_density'] < 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->getStartColor()->setRGB('eb3434');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['right_density'] > 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->getStartColor()->setRGB('3deb34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFont()->setBold(true);            
        }
        if ($plantacion['right_density'] == 5) {
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFill()->getStartColor()->setRGB('ebcc34');
            $objPHPExcel->setActiveSheetIndex(0)->getStyle('M' . $fila)->getFont()->setBold(true);            
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
$objPHPExcel->getActiveSheet()->getStyle('B1:AN' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B1:AN'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:AN'.$fila)->getAlignment()->setWrapText(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Load Order Assessment');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Load Order Assessment.xlsx"');
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
<?PHP
/*
 * Genera el listado de aplicaciones químicas realizadas de acuerdo a fechas, químicos y finca.
 * @author      Jairo Bonilla
 * @date        2020-06-19
 */
ob_end_clean();
ob_start();
session_start();
/*CONEXION CON BASE DE DATOS*/
include_once "../../libs/db_classes/db_mysql_conn.php";
include_once "../../libs/db_classes/db_reportes.php";
/*INSTANCIAMIENTOS*/
$DB_REP                 = new db_reportes();
$cod_info_empresa       = trim($_GET['x1']);
$fecha_inicial          = trim($_GET['x2']);
$fecha_final            = trim($_GET['x3']);
$cod_inventario_quimico = trim($_GET['x4']);

$PLANTACIONES = $DB_REP->rep_nuevo_reporte_excel_aplicacion_quimica($cod_info_empresa,$fecha_inicial,$fecha_final,$cod_inventario_quimico);
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
    ->setCellValue('B3', 'No')
    ->setCellValue('C3', 'Supervisor')
    ->setCellValue('D3', 'Operator')
    ->setCellValue('E3', 'Grower')
    ->setCellValue('F3', 'Watercress')
    ->setCellValue('G3', 'Zones')
    ->setCellValue('H3', 'Blocks Range')
    ->setCellValue('I3', 'Blocks')
    ->setCellValue('J3', 'Seeds')
    ->setCellValue('K3', 'Planting')
    ->setCellValue('L3', 'Application Type')
    ->setCellValue('M3', 'Assesment Date')
    ->setCellValue('N3', 'Harvest Date')
    ->setCellValue('O3', 'Product Code')
    ->setCellValue('P3', 'Chemicals')
    ->setCellValue('Q3', 'EPA Number')
    ->setCellValue('R3', 'Active Ingredients')
    ->setCellValue('S3', 'Acre(s)')
    ->setCellValue('T3', 'Type of Application')
    ->setCellValue('U3', 'Equipment Name')
    ->setCellValue('V3', 'Application Rate')
    ->setCellValue('W3', 'Unit of Measure')
    ->setCellValue('X3', 'Quantity Applied')
    ->setCellValue('Y3', 'Unit of Measure')
    ->setCellValue('Z3', 'Application Rate')
    ->setCellValue('AA3', 'Application Date')
    ->setCellValue('AB3', 'Application Start Time')
    ->setCellValue('AC3', 'Application End Time')
    ->setCellValue('AD3', 'Application Reason')
    ->setCellValue('AE3', 'REI Interval')
    ->setCellValue('AF3', 'Interval Type')
    ->setCellValue('AG3', 'PHI Interval')
    ->setCellValue('AH3', 'Interval Type')
    ->setCellValue('AI3', 'Wind Speed (Mi)')
    ->setCellValue('AJ3', 'Temperature (ºF)');

// Titulo Dirección
$worksheet->mergeCells('B1:AJ1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:AJ1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:AJ2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:AJ2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: New Chemical Application Excel Download');
// Titulos de columnas
$worksheet->getStyle('B3:AJ3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:AJ3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:AJ3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:AJ3')->getFont()->setBold(true);
$worksheet->getColumndimension('A')->setWidth(5);
$worksheet->getColumndimension('B')->setWidth(40);
$worksheet->getColumndimension('C')->setWidth(40);
$worksheet->getColumndimension('D')->setWidth(40);
$worksheet->getColumndimension('E')->setWidth(40);
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
/*$worksheet->getColumndimension('Ab')->setWidth(40);*/
$fila = 4;

if (count($PLANTACIONES) > 0) {
    $correlativo = 1;
    $wizard = new PHPExcel_Helper_HTML();
    $valor_inicial = $PLANTACIONES[0]['cod_aplicacion'];
    $fila_inicial = 4;
    $fila_final = 4;
    foreach ($PLANTACIONES as $plantacion) {
        //Miscellaneous glyphs, UTF-8
        if($valor_inicial != $plantacion['cod_aplicacion'])
        {
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$fila_inicial.':B'.$fila_final);
            $valor_inicial = $plantacion['cod_aplicacion'];
            $fila_inicial = $fila;
            $fila_final = $fila;
            $correlativo += 1;
        }
        else
        {
            $fila_final = $fila;
        }
        $objPHPExcel->getActiveSheet()->freezePane('C4');
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila_inicial, $correlativo)
            ->setCellValue('C' . $fila, $wizard->toRichTextObject($plantacion['nombre_supervisor']))
            ->setCellValue('D' . $fila, $wizard->toRichTextObject($plantacion['nombre_operador']))
            ->setCellValue('E' . $fila, utf8_encode($plantacion['nombre_empresa']))
            ->setCellValue('F' . $fila, utf8_encode($plantacion['flag_watercress'] == 1 ? 'Yes':'No'))
            ->setCellValue('G' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['zonas'])))))))
            ->setCellValue('H' . $fila, utf8_encode($plantacion['min_bloque'].'-'.$plantacion['max_bloque']))
            ->setCellValue('I' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('J' . $fila, utf8_encode($plantacion['nombre_semilla']))
            ->setCellValue('K' . $fila, utf8_encode($plantacion['anio_plantacion'].'-'.$plantacion['num_plantacion'].'-'.$plantacion['codigo_temporada']))
            ->setCellValue('L' . $fila, utf8_encode($plantacion['tipo_quimicos']))
            ->setCellValue('M' . $fila, utf8_encode($plantacion['date_insert']))
            ->setCellValue('N' . $fila, utf8_encode($plantacion['harvest_date']))
            ->setCellValue('O' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['codigo_productos'])))))))
            ->setCellValue('P' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['quimicos'])))))))
            ->setCellValue('Q' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['registro_ambiental_epa'])))))))
            ->setCellValue('R' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['ingredientes_activos'])))))))
            ->setCellValue('S' . $fila, utf8_encode($plantacion['acres']))
            ->setCellValue('T' . $fila, utf8_encode($plantacion['tipo_aplicacion_maquinaria']))
            ->setCellValue('U' . $fila, utf8_encode($plantacion['nombre_maquinaria']))
            ->setCellValue('V' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['application_rate'])))))))
            ->setCellValue('W' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['application_rate_unidad_medida'])))))))
            ->setCellValue('X' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['cantidad_aplicada'])))))))
            ->setCellValue('Y' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['cantidad_aplicada_unidad_medida'])))))))
            ->setCellValue('Z' . $fila, round(floatval($plantacion['cantidad_aplicada']/$plantacion['acres']),2))
            ->setCellValue('AA' . $fila, utf8_encode($plantacion['fecha_aplicacion_operador']))
            ->setCellValue('AB' . $fila, utf8_encode($plantacion['hora_inicial']))
            ->setCellValue('AC' . $fila, utf8_encode($plantacion['hora_final']))
            ->setCellValue('AD' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['razon_aplicacion'])))))))
            ->setCellValue('AE' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['periodo_reingreso'])))))))
            ->setCellValue('AF' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['tipo_periodo_reingreso'])))))))
            ->setCellValue('AG' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['periodo_precosecha'])))))))
            ->setCellValue('AH' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['tipo_periodo_precosecha'])))))))
            ->setCellValue('AI' . $fila, utf8_encode($plantacion['viento']))
            ->setCellValue('AJ' . $fila, utf8_encode($plantacion['temperatura']));
        $fila += 1;
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
$objPHPExcel->getActiveSheet()->getStyle('B1:AJ' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:AJ'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:AJ'.$fila)->getAlignment()->setWrapText(true);

/*Establece formatos de número a celdas*/
$worksheet->getStyle('O4:O'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('S4:S'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('X4:X'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
$worksheet->getStyle('Z4:Z'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Chemical Application');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="New Chemical Application Excel Download.xlsx"');
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
$xlsData = ob_get_contents();
exit;

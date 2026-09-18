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
include_once "../../libs/db_classes/db_reportes.php";
/*INSTANCIAMIENTOS*/
$DB_REP             = new db_reportes();
$fecha_inicial       = $_GET['x1'];
$fecha_final         = $_GET['x2'];
$cod_info_empresa    = $_GET['x3'];
$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

//$PLANTACIONES = $DB_REP->rep_reporte_acres_produccion($fecha_inicial,$fecha_final,$cod_granjas_usuario);
$PLANTACIONES = $DB_REP->rep_reporte_acres_produccion($fecha_inicial,$fecha_final,$cod_info_empresa);
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
    ->setCellValue('C3', 'Planting')
    ->setCellValue('D3', 'Grower')
    ->setCellValue('E3', 'Watercress')
    ->setCellValue('F3', 'Crop#')
    ->setCellValue('G3', 'Planting Date')
    ->setCellValue('H3', 'Zones')
    ->setCellValue('I3', 'Blocks')
    ->setCellValue('J3', 'Date Change Stage')
    ->setCellValue('K3', 'Time Change Stage')
    ->setCellValue('L3', 'Acres')
    ->setCellValue('M3', 'Planted')
    ->setCellValue('N3', 'Trimmed')
    ->setCellValue('O3', 'Growing')
    ->setCellValue('P3', 'Ready to Harvest')
    ->setCellValue('Q3', 'Harvested')
    ->setCellValue('R3', 'System Date')
    ->setCellValue('S3', 'System Time');

// Titulo Dirección
$worksheet->mergeCells('B1:S1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:S2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:S2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: Acres in Production Report from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:S3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:S3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:S3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:S3')->getFont()->setBold(true);
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
$fila = 4;

if (count($PLANTACIONES) > 0) {
    $correlativo = 1;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($PLANTACIONES as $plantacion) {
        $date_insert = DateTime::createFromFormat('m-d-Y H:i:s', $plantacion['date_insert']);
        $fecha = $date_insert->format('m-d-Y');
        $hora = $date_insert->format('h:i:sa');
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($plantacion['anio_plantacion'].'-'.$plantacion['num_plantacion'].'-'.$plantacion['codigo_temporada']))
            ->setCellValue('D' . $fila, utf8_encode($plantacion['nombre_empresa']))
            ->setCellValue('E' . $fila, utf8_encode($plantacion['flag_watercress'] == 1 ? 'Yes':'No'))
            ->setCellValue('F' . $fila, utf8_encode($plantacion['crop_number']))
            ->setCellValue('G' . $fila, utf8_encode($plantacion['fecha_plantacion_planeada']))
            ->setCellValue('H' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['zonas'])))))))
            ->setCellValue('I' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('J' . $fila, date('m-d-Y',strtotime($plantacion['date_update'])))
            ->setCellValue('K' . $fila, date("h:i:sa",strtotime($plantacion['date_update'])))
            ->setCellValue('L' . $fila, utf8_encode($plantacion['num_acres']))
            ->setCellValue('M' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['plantados'])))))))
            ->setCellValue('N' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['podados'])))))))
            ->setCellValue('O' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['crecimiento'])))))))
            ->setCellValue('P' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['listo_cosechar'])))))))
            ->setCellValue('Q' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['cosechado'])))))))
            ->setCellValue('R' . $fila, $fecha)
            ->setCellValue('S' . $fila, $hora);
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
$objPHPExcel->getActiveSheet()->getStyle('B1:S' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:S'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:S'.$fila)->getAlignment()->setWrapText(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Acres in Production Report');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Acres in Production Report.xlsx"');
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

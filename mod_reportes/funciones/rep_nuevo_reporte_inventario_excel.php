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

//$PLANTACIONES = $DB_REP->rep_nuevo_reporte_inventario($fecha_inicial,$fecha_final,$cod_granjas_usuario);
$PLANTACIONES = $DB_REP->rep_nuevo_reporte_inventario($fecha_inicial,$fecha_final,$cod_info_empresa);
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
    ->setCellValue('C3', 'Grower')
    ->setCellValue('D3', 'Code')
    ->setCellValue('E3', 'Type')
    ->setCellValue('F3', 'Chemical')
    ->setCellValue('G3', 'Unit of Measure')
    ->setCellValue('H3', 'Initial Amount')
    ->setCellValue('I3', 'Used')
    ->setCellValue('J3', 'Received')
    ->setCellValue('K3', 'Move In')
    ->setCellValue('L3', 'Move Out')
    //->setCellValue('M3', 'Physical Amount')
    //->setCellValue('N3', 'Discrepancy')
    ->setCellValue('M3', 'Should Be')
    //->setCellValue('P3', 'Dates')
    ->setCellValue('N3', 'Added')
    ->setCellValue('O3', 'Subtracted')
    ->setCellValue('P3', 'Subtracted')
    //->setCellValue('S3', 'Reason')
    ->setCellValue('Q3', 'Surrounding Areas');
    //->setCellValue('U3', 'User')
    //->setCellValue('V3', 'Date');

// Titulo Dirección
$worksheet->mergeCells('B1:Q1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:Q2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: Inventories Report from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:Q3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:Q3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:Q3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:Q3')->getFont()->setBold(true);
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
$fila = 4;

if (count($PLANTACIONES) > 0) {
    $correlativo = 1;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($PLANTACIONES as $plantacion) {
        $sumado = floatval($plantacion['cantidad_sumar'] + $plantacion['cantidad_sumar_bitacora'] + $plantacion['nueva_cantidad_sumar']);
        $restado = floatval($plantacion['cantidad_restar'] + $plantacion['cantidad_restar_bitacora'] + $plantacion['nueva_cantidad_restar']);
        $usado = floatval($plantacion['cantidad_aplicada']);
        $recibido = floatval($plantacion['cantidad_orden_compra']);
        $entro = floatval($plantacion['cantidad_recibida']);
        $salio = floatval($plantacion['cantidad_enviada']);
        //$monto_inicial = floatval($plantacion['cantidad_quimico']+$usado-$recibido-$entro+$salio-$sumado+$restado);
        $monto_inicial = floatval($plantacion['cantidad_quimico_bitacora']);
        $zonas_aledanas = floatval($plantacion['cantidad_quimico_rociado']);
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($plantacion['nombre_empresa']))
            ->setCellValue('D' . $fila, utf8_encode($plantacion['cod_quimico']))
            ->setCellValue('E' . $fila, utf8_encode($plantacion['tipo_quimico']))
            ->setCellValue('F' . $fila, utf8_encode($plantacion['nombre_quimico']))
            ->setCellValue('G' . $fila, utf8_encode($plantacion['unidad_medida']))
            ->setCellValue('H' . $fila, $monto_inicial)
            ->setCellValue('I' . $fila, $usado)
            ->setCellValue('J' . $fila, $recibido)
            ->setCellValue('K' . $fila, $entro)
            ->setCellValue('L' . $fila, $salio)
            //->setCellValue('M' . $fila, floatval($plantacion['cantidad_fisica_quimico_bitacora']))
            //->setCellValue('N' . $fila, abs(floatval($plantacion['cantidad_quimico_bitacora']+$salio+$plantacion['cantidad_sugerida']-$usado-$plantacion['cantidad_fisica_quimico_bitacora'])))
            ->setCellValue('M' . $fila, floatval($monto_inicial-$usado+$recibido+$entro-$salio+$sumado-$restado-$zonas_aledanas))
            //->setCellValue('P' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode($plantacion['fecha_sumar_restar_bitacora'].($plantacion['fecha_sumar_restar'] != '' && $plantacion['fecha_sumar_restar'] != NULL ? '<br>'.$plantacion['fecha_sumar_restar']:'')))))))
            ->setCellValue('N' . $fila, $sumado)
            ->setCellValue('O' . $fila, $restado)
            //->setCellValue('S' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode($plantacion['razon_sumar_restar_bitacora'].($plantacion['razon_sumar_restar'] != '' && $plantacion['razon_sumar_restar'] != NULL ? '<br>'.$plantacion['razon_sumar_restar']:'')))))))
            ->setCellValue('P' . $fila, $zonas_aledanas)
            ->setCellValue('Q' . $fila, ($monto_inicial-$usado+$recibido+$entro-$salio+$sumado-$restado-$zonas_aledanas));
            //->setCellValue('U' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode($plantacion['nombre_usuario']))))))
            //->setCellValue('V' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode($plantacion['date_update']))))));
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
$objPHPExcel->getActiveSheet()->getStyle('B1:Q' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:Q'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:Q'.$fila)->getAlignment()->setWrapText(true);
/*Establece formatos de número a celdas*/
$worksheet->getStyle('H4:Q'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
//$worksheet->getStyle('Q4:R'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');
//$worksheet->getStyle('T4:Q'.$fila)->getNumberFormat()->setFormatCode('#,##0.00');

//$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setVisible(false);
//$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setVisible(false);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Inventories Report');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Inventories Report from ' . $fecha_inicial . ' to ' . $fecha_final.'.xlsx"');
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
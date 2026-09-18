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
include_once "../../libs/db_classes/db_configuracion.php";
/*INSTANCIAMIENTOS*/
$DB_CONF                 = new db_configuracion();
$fecha_inicial          = $_GET['x1'];
$fecha_final            = $_GET['x2'];
$cod_info_empresa       = $_GET['x3'];
$cod_formulario         = $_GET['x4'];

$FORMULARIOS = $DB_CONF->conf_obtener_listado_mis_formularios_excel($fecha_inicial, $fecha_final, $cod_info_empresa, $cod_formulario, 0);
/* EXCEL */
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
date_default_timezone_set('Europe/London');
if (PHP_SAPI == 'cli') {
    die('This example should only be run from a Web Browser');
}

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../libs/PHPExcel-1.8.2/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$worksheet = $objPHPExcel->getActiveSheet();
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B3', 'No.')
    ->setCellValue('C3', 'Form Name')
    ->setCellValue('D3', 'Stage')
    ->setCellValue('E3', 'Observation')
    ->setCellValue('F3', 'User')
    ->setCellValue('G3', 'Date')
    ->setCellValue('H3', 'Item Name')
    ->setCellValue('I3', 'Item ID')
    ->setCellValue('J3', 'Item Type')
    ->setCellValue('K3', 'Item Value');

// Titulo Dirección
$worksheet->mergeCells('B1:K1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'B&W Farming');
// Titulo principal
$worksheet->mergeCells('B2:K2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'B&W Farming: Forms '.$FORMULARIOS[0]['nombre_formulario'].' from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:K3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:K3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:K3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:K3')->getFont()->setBold(true);
$worksheet->getColumndimension('A')->setWidth(5);
$worksheet->getColumndimension('B')->setWidth(10);
$worksheet->getColumndimension('C')->setWidth(25);
$worksheet->getColumndimension('D')->setWidth(20);
$worksheet->getColumndimension('E')->setWidth(60);
$worksheet->getColumndimension('F')->setWidth(25);
$worksheet->getColumndimension('G')->setWidth(25);
$worksheet->getColumndimension('H')->setWidth(40);
$worksheet->getColumndimension('I')->setWidth(40);
$worksheet->getColumndimension('J')->setWidth(40);
$worksheet->getColumndimension('K')->setWidth(40);
$fila = 4;

if (count($FORMULARIOS) > 0) {
    $correlativo = 1;
    $wizard = new PHPExcel_Helper_HTML();
    $rowspan = 0;
    $cod_detalle = $FORMULARIOS[0]['cod_detalle'];
    foreach ($FORMULARIOS as $formulario) {
        //Miscellaneous glyphs, UTF-8
        if ($cod_detalle != $formulario['cod_detalle'])
        {
            $worksheet->mergeCells('B'.($fila-$rowspan).':B'.($fila-1));
            $worksheet->mergeCells('C'.($fila-$rowspan).':C'.($fila-1));
            $worksheet->mergeCells('D'.($fila-$rowspan).':D'.($fila-1));
            $worksheet->mergeCells('E'.($fila-$rowspan).':E'.($fila-1));
            $worksheet->mergeCells('F'.($fila-$rowspan).':F'.($fila-1));
            $worksheet->mergeCells('G'.($fila-$rowspan).':G'.($fila-1));
            $rowspan = 0;
            $cod_detalle = $formulario['cod_detalle'];
            $correlativo += 1;
        }
        if ($cod_detalle == $formulario['cod_detalle'])
        {
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C' . $fila, utf8_encode($formulario['nombre_formulario']))
            ->setCellValue('D' . $fila, utf8_encode($formulario['nombre_estado']))
            ->setCellValue('E' . $fila, utf8_encode($formulario['observacion_review']))
            ->setCellValue('F' . $fila, utf8_encode($formulario['nombre_usuario']))
            ->setCellValue('G' . $fila, utf8_encode($formulario['date_insert']));
            $rowspan++;
        }
        switch ($formulario['cod_tipo_item']) {
            case 1://checkbox
                $respuesta = ($formulario['observacion'] == 1 ? 'Yes':'No');
                break;
            case 3://selectpicker
                $respuesta = $formulario['respuesta_select'];
                break;
            case 8://selectpicker with dependents
                $respuesta = $formulario['respuesta_select'];
                break;
            case 9://radio button
                $respuesta = $formulario['respuesta_select'];
                break;
            default:
                $respuesta = $formulario['observacion'];
                break;
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('H' . $fila, utf8_encode($formulario['nombre_item']))
            ->setCellValue('I' . $fila, utf8_encode($formulario['id_item']))
            ->setCellValue('J' . $fila, utf8_encode($formulario['tipo_item']))
            ->setCellValue('K' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($respuesta)))))));
        $fila += 1;
    }
    $worksheet->mergeCells('B'.($fila-$rowspan).':B'.($fila-1));
    $worksheet->mergeCells('C'.($fila-$rowspan).':C'.($fila-1));
    $worksheet->mergeCells('D'.($fila-$rowspan).':D'.($fila-1));
    $worksheet->mergeCells('E'.($fila-$rowspan).':E'.($fila-1));
    $worksheet->mergeCells('F'.($fila-$rowspan).':F'.($fila-1));
    $worksheet->mergeCells('G'.($fila-$rowspan).':G'.($fila-1));
}
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$fila = $fila - 1;
$objPHPExcel->getActiveSheet()->getStyle('B1:K' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:K'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:K'.$fila)->getAlignment()->setWrapText(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($FORMULARIOS[0]['nombre_formulario']);
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Forms - '.$FORMULARIOS[0]['nombre_formulario'].'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
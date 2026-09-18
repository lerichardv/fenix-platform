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
ini_set('memory_limit', '512M'); 
/*INSTANCIAMIENTOS*/
$DB_REP             = new db_reportes();
$fecha_inicial       = $_GET['x1'];
$fecha_final         = $_GET['x2'];
$cod_info_empresa    = $_GET['x3'];
$cod_granjas_usuario = str_replace(['[','"',']'], '', $_SESSION['cod_info_empresa']);

//$PLANTACIONES = $DB_REP->rep_reporte_exploradoras($fecha_inicial,$fecha_final,$cod_granjas_usuario);
$PLANTACIONES = $DB_REP->rep_reporte_exploradoras($fecha_inicial,$fecha_final,$cod_info_empresa);
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
    ->setCellValue('F3', 'Crop #')
    ->setCellValue('G3', 'Zones')
    ->setCellValue('H3', 'Blocks')
    ->setCellValue('I3', 'Insect(s)')
    ->setCellValue('J3', 'Disease(s)')
    ->setCellValue('K3', 'Weed(s)')
    ->setCellValue('L3', 'Other(s) Damage(s)')
    ->setCellValue('M3', 'Worms')
    ->setCellValue('N3', 'Eggs')
    ->setCellValue('O3', 'Leaf Hoppers')
    ->setCellValue('P3', 'Aphids')
    ->setCellValue('Q3', 'Stink Bugs')
    ->setCellValue('R3', 'Gnats')
    ->setCellValue('S3', 'Flea Beetles')
    ->setCellValue('T3', 'Cyclaman Mites')
    ->setCellValue('U3', 'Cercospora Leaf Spot')
    ->setCellValue('V3', 'Pythium')
    ->setCellValue('W3', 'Rhizoctonia Aerial Blight')
    ->setCellValue('X3', 'Bacteria')
    ->setCellValue('Y3', 'Sclerotinia')
    ->setCellValue('Z3', 'Alternaria Specks')
    ->setCellValue('AA3', 'Mildew')
    ->setCellValue('AB3', 'Virus')
    ->setCellValue('AC3', 'Dollarweed')
    ->setCellValue('AD3', 'Frogs Bit')
    ->setCellValue('AE3', 'Mud Plantain')
    ->setCellValue('AF3', 'Tube Weed')
    ->setCellValue('AG3', 'Grass')
    ->setCellValue('AH3', 'Damaged Leaves')
    ->setCellValue('AI3', 'Purple Stem')
    ->setCellValue('AJ3', 'Watercress Rooted')
    ->setCellValue('AK3', 'System Date')
    ->setCellValue('AL3', 'System Time');

// Titulo Dirección
$worksheet->mergeCells('B1:AL1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:AL1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:AL2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:AL2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: Planting Scouting Report from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:AL3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:AL3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:AL3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:AL3')->getFont()->setBold(true);
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
            ->setCellValue('G' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['zonas'])))))))
            ->setCellValue('H' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('I' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim('Worms: '.$plantacion['gusanos'].'<br>Eggs: '.$plantacion['huevos'].'<br>Leaf Hoopers: '.$plantacion['saltahojas'].'<br>Aphids: '.$plantacion['afidos'].'<br>Stink Bugs: '.$plantacion['chinches'].'<br>Gnats: '.$plantacion['moscos'].'<br>Flea Beetles: '.$plantacion['escarabajos'].'<br>Cyclaman Mites: '.$plantacion['acaros'])))))))
            ->setCellValue('J' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim('Cercospora Leaf Spot: '.$plantacion['cercospora_leaf_spot'].'<br>Pythium/Damp Off: '.$plantacion['pythium'].'<br>Rhizoctonia Aerial Blight: '.$plantacion['rhizoctonia'].'<br>Bacteria: '.$plantacion['bacteria'].'<br>Sclerotinia: '.$plantacion['sclerotinia'].'<br>Alternaria Specks: '.$plantacion['alternaria_specks'].'<br>Mildew: '.$plantacion['mildew'].'<br>Virus: '.$plantacion['virus'])))))))
            ->setCellValue('K' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim('Dollarweed: '.$plantacion['dolar'].'<br>Frogs Bit: '.$plantacion['frogs_bit'].'<br>Mud Plantain: '.$plantacion['plantas_lodo'].'<br>Tube Weed: '.$plantacion['tripa_pollo'].'<br>Grass: '.$plantacion['zacate'])))))))
            ->setCellValue('L' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim('Damaged Leaves: '.$plantacion['hojas_danadas'].'<br>Purple Stem: '.$plantacion['tallos_purpuras'].'<br>Watercress rooted: '.$plantacion['berro_enraizado'])))))))
            ->setCellValue('M' . $fila, utf8_encode($plantacion['gusanos']))
            ->setCellValue('N' . $fila, utf8_encode($plantacion['huevos']))
            ->setCellValue('O' . $fila, utf8_encode($plantacion['saltahojas']))
            ->setCellValue('P' . $fila, utf8_encode($plantacion['afidos']))
            ->setCellValue('Q' . $fila, utf8_encode($plantacion['chinches']))
            ->setCellValue('R' . $fila, utf8_encode($plantacion['moscos']))
            ->setCellValue('S' . $fila, utf8_encode($plantacion['escarabajos']))
            ->setCellValue('T' . $fila, utf8_encode($plantacion['acaros']))
            ->setCellValue('U' . $fila, utf8_encode($plantacion['cercospora_leaf_spot']))
            ->setCellValue('V' . $fila, utf8_encode($plantacion['pythium']))
            ->setCellValue('W' . $fila, utf8_encode($plantacion['rhizoctonia']))
            ->setCellValue('X' . $fila, utf8_encode($plantacion['bacteria']))
            ->setCellValue('Y' . $fila, utf8_encode($plantacion['sclerotinia']))
            ->setCellValue('Z' . $fila, utf8_encode($plantacion['alternaria_specks']))
            ->setCellValue('AA' . $fila, utf8_encode($plantacion['mildew']))
            ->setCellValue('AB' . $fila, utf8_encode($plantacion['virus']))
            ->setCellValue('AC' . $fila, utf8_encode($plantacion['dolar']))
            ->setCellValue('AD' . $fila, utf8_encode($plantacion['frogs_bit']))
            ->setCellValue('AE' . $fila, utf8_encode($plantacion['plantas_lodo']))
            ->setCellValue('AF' . $fila, utf8_encode($plantacion['tripa_pollo']))
            ->setCellValue('AG' . $fila, utf8_encode($plantacion['zacate']))
            ->setCellValue('AH' . $fila, utf8_encode($plantacion['hojas_danadas']))
            ->setCellValue('AI' . $fila, utf8_encode($plantacion['tallos_purpuras']))
            ->setCellValue('AJ' . $fila, utf8_encode($plantacion['berro_enraizado']))
            ->setCellValue('AK' . $fila, $fecha)
            ->setCellValue('AL' . $fila, $hora);
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
$objPHPExcel->getActiveSheet()->getStyle('B1:AL' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:AL'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:AL'.$fila)->getAlignment()->setWrapText(true);

/*Establece formatos de número a celdas*/
$worksheet->getStyle('M4:AJ'.$fila)->getNumberFormat()->setFormatCode('#,##0.000');

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Planting Scouting Report');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Planting Scouting Report.xlsx"');
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

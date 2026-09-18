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
$fecha_inicial      = $_GET['x1'];
$fecha_final        = $_GET['x2'];
$cod_info_empresa   = $_GET['x3'];

$FORMULARIOS = $DB_CONF->conf_obtener_listado_environmental_test_excel($fecha_inicial, $fecha_final, $cod_info_empresa);


/*
* Arrays de codigos no administrables mediante bdd
 */
$cod_location = ['','Cooler','Wall'];
$cod_type_test = ['',
                'Generic E. coli (MPN via Colilert)',
                'Total Coliform - TC (MPN via Colilert)',
                'Fecal Coliform',
                'E. coli O157:H7',
                'Listeria',
                'L. mono (Listera monocytogenes)',
                'Salmonella',
                'STEC',
                'Yeast',
                'Total Plate Count (TPC)',
                'E. coli O157:H7 (Pooled)',
                'Total Coliform',
                'RUSH',
                'Multi-Residue Screen (MRS)',
                'MRS - Extended',
                'MRS + Dithios',
                'USDA NOP',
                'MRS4',
                'MRS + ON’s',
                'MRS - MB',
                'Long Form'
            ];
$cod_source_phase = ['','Non-Food Contact','Food Contact'];
$cod_sample = ['','Swab','Sponge'];
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
    ->setCellValue('C3', 'Country')
    ->setCellValue('D3', 'State')
    ->setCellValue('E3', 'County')
    ->setCellValue('F3', 'Grower')
    ->setCellValue('G3', 'Location')
    ->setCellValue('H3', 'Test')
    ->setCellValue('I3', 'Sample')
    ->setCellValue('J3', 'Sample Date')
    ->setCellValue('K3', 'Sample Time')
    ->setCellValue('L3', 'Result')
    ->setCellValue('M3', 'Geometric Mean');

// Titulo Dirección
$worksheet->mergeCells('B1:M1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'B&W Farming');
// Titulo principal
$worksheet->mergeCells('B2:M2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'B&W Farming: Environmental Tests from ' . $fecha_inicial . ' to ' . $fecha_final);
// Titulos de columnas
$worksheet->getStyle('B3:M3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:M3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:M3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:M3')->getFont()->setBold(true);
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
$worksheet->getColumndimension('L')->setWidth(40);
$worksheet->getColumndimension('M')->setWidth(40);
$fila = 4;

if (count($FORMULARIOS) > 0) {
    $correlativo = 1;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($FORMULARIOS as $formulario) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($formulario['pais']))
            ->setCellValue('D' . $fila, utf8_encode($formulario['departamento']))
            ->setCellValue('E' . $fila, utf8_encode($formulario['municipio']))
            ->setCellValue('F' . $fila, utf8_encode($formulario['nombre_empresa']))
            ->setCellValue('G' . $fila, utf8_encode($cod_location[$formulario['cod_location']]))
            ->setCellValue('H' . $fila, utf8_encode($cod_type_test[$formulario['cod_type_test']]))
            ->setCellValue('I' . $fila, utf8_encode($cod_sample[$formulario['cod_sample']]))
            ->setCellValue('J' . $fila, ($formulario['sample_date']))
            ->setCellValue('K' . $fila, ($formulario['sample_time']))
            ->setCellValue('L' . $fila, ($formulario['result']))
            ->setCellValue('M' . $fila, round($formulario['geometric_mean'], 2));
        $fila += 1;
        $correlativo++;
        //$REGISTROS = $DB_CONF->conf_obtener_listado_detallado_environmental_test($formulario['cod_info_empresa'], $formulario['sample_date'], $formulario['cod_location'], $formulario['cod_type_test'], $formulario['cod_source_phase'], $formulario['cod_sample']);
        /*if (count($REGISTROS)) {
            foreach ($REGISTROS as $registro)
            {
                //Miscellaneous glyphs, UTF-8
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G' . $fila, $registro['sample_date'])
                    ->setCellValue('H' . $fila, $registro['sample_time'])
                    ->setCellValue('I' . $fila, $registro['result']);
                $fila += 1;
            }            
        }*/
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
$objPHPExcel->getActiveSheet()->getStyle('B1:M' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:M'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:M'.$fila)->getAlignment()->setWrapText(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Environmental Tests');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Environmental Tests.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
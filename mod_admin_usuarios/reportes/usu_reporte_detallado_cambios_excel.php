<?PHP
/*
 * Genera el listado general de porcentajes por donante para exportar a excel.
 * @author      Dan Urquía
 * @date        2016-09-23
 */
ob_end_clean();
    ob_start();
session_start();
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");
$fecha_inicial 	= $_GET['x1'];
$fecha_final 	= $_GET['x2'];
$cods_user 	= $_GET['x3'];

/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$TENDENCIAS = $DB_USUARIO->get_cambios_detalle($fecha_inicial, $fecha_final,$cods_user);

/* EXCEL */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
if (PHP_SAPI == 'cli')
    die('');
/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../libs/PHPExcel/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$worksheet = $objPHPExcel->getActiveSheet();
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B3', 'Nro.')
            ->setCellValue('C3', 'Usuario')
            ->setCellValue('D3', 'Mes')
            ->setCellValue('E3', 'Cambios')
            ->setCellValue('F3', 'Fecha')
            ->setCellValue('G3', 'Horas');

// Titulo Dirección
$worksheet->mergeCells('B1:G1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'Cambios de Contraseña del '.$fecha_inicial.' al '.$fecha_final);
// Titulo principal
$worksheet->mergeCells('B2:G2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'Cambios de Contraseña por Usuario');
// Titulos de columnas
$worksheet->getStyle('B3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:G3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:G3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:G3')->getFont()->setBold(true);
$worksheet->getColumndimension('A')->setWidth(2);
$worksheet->getColumndimension('B')->setWidth(10);
$worksheet->getColumndimension('C')->setWidth(15);
$worksheet->getColumndimension('D')->setWidth(15);
$worksheet->getColumndimension('E')->setWidth(15);
$worksheet->getColumndimension('F')->setWidth(15);
$worksheet->getColumndimension('G')->setWidth(35);

$fila = 4;
$correlativo = 1;

if(count($TENDENCIAS) > 0){
    foreach($TENDENCIAS as $INFO)
    {
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, $correlativo)
        ->setCellValue('C'.$fila, utf8_encode($INFO['nombre']))
        ->setCellValue('D'.$fila, utf8_encode($INFO['mes']))
        ->setCellValue('E'.$fila, utf8_encode($INFO['cambios']))
        ->setCellValue('F'.$fila, utf8_encode($INFO['fecha_ingreso']))
        ->setCellValue('G'.$fila, utf8_encode($INFO['horas']));

        $worksheet->getStyle('B'.$fila.':G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $fila += 1;
        $correlativo += 1;
    }
}

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$fila = $fila - 1;
$objPHPExcel->getActiveSheet()->getStyle('B1:G'.$fila)->applyFromArray($styleArray);
$worksheet->getStyle('B1:G'.$fila)->getAlignment()->setWrapText(true);
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Cambios de Contraseña por Usuario');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte de Cambios de Contraseña Mensuales '.date("Y-m-d").'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>

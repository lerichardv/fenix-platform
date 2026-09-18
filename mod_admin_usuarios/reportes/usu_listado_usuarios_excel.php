<?PHP
/*
 * Genera el listado general de usuario activos para exportar a excel.
 * @author     Lidna Zelaya
 * @date        2016-05-10
 */
ob_end_clean();
    ob_start();
session_start();
/*CONEXION CON BASE DE DATOS*/
include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_usuario.php");

/*INSTANCIAMIENTOS*/
$DB_USUARIO = new db_usuario();
$USUARIOS = $DB_USUARIO->usu_get_info_usuarios();

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
            ->setCellValue('B3', 'No.')
            ->setCellValue('C3', 'Nombre')
            ->setCellValue('D3', 'Telefono')
            ->setCellValue('E3', 'Celular')
            ->setCellValue('F3', 'Email')
            ->setCellValue('G3', 'Direccion')
            ->setCellValue('H3', 'Fecha de Ingreso')
            ->setCellValue('I3', 'Programa')
            ->setCellValue('J3', 'Cargo')
            ->setCellValue('K3', 'Perfil')
            ->setCellValue('L3', 'Jefe Inmediato')
            ->setCellValue('M3', 'Activo');
// Titulo Dirección
$worksheet->mergeCells('B1:M1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B1', 'Listado de Usuarios en Sistema OMNIABOX');
// Titulo principal
$worksheet->mergeCells('B2:M2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'Usuarios registrados');
// Titulos de columnas
$worksheet->getStyle('B3:M3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:M3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:M3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:M3')->getFont()->setBold(true);
$worksheet->getColumndimension('A')->setWidth(2);
$worksheet->getColumndimension('B')->setWidth(10);
//$worksheet->getColumndimension('C')->setWidth(15);
$worksheet->getColumndimension('C')->setWidth(30);
$worksheet->getColumndimension('D')->setWidth(30);
$worksheet->getColumndimension('E')->setWidth(30);
$worksheet->getColumndimension('F')->setWidth(30);
$worksheet->getColumndimension('G')->setWidth(30);
$worksheet->getColumndimension('H')->setWidth(30);
$worksheet->getColumndimension('I')->setWidth(30);
$worksheet->getColumndimension('J')->setWidth(30);
$worksheet->getColumndimension('K')->setWidth(30);
$worksheet->getColumndimension('L')->setWidth(30);
$worksheet->getColumndimension('M')->setWidth(10);
$fila = 4;


if(count($USUARIOS) > 0){
    foreach($USUARIOS as $INFO)
    {
      $date = new DateTime($INFO['date_insert']);
      $date = $date->format('Y-m-d');
      $activo = $INFO['activo'] == 1 ?'Si':'No';
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, utf8_encode($INFO['cod_usuario']))
        ->setCellValue('C'.$fila, utf8_encode($INFO['nombre']))
        ->setCellValue('D'.$fila, trim(utf8_encode($INFO['telefono_1'])))
        ->setCellValue('E'.$fila, trim(utf8_encode($INFO['telefono_2'])))
        ->setCellValue('F'.$fila, trim(utf8_encode($INFO['email'])))
        ->setCellValue('G'.$fila, utf8_encode($INFO['direccion']))
        ->setCellValue('H'.$fila, utf8_encode($date))
        ->setCellValue('I'.$fila, utf8_encode($INFO['gerencia']))
        ->setCellValue('J'.$fila, utf8_encode($INFO['cargo']))
        ->setCellValue('K'.$fila, utf8_encode($INFO['perfil']))
        ->setCellValue('L'.$fila, utf8_encode($INFO['nombre_jefe']))
        ->setCellValue('M'.$fila, utf8_encode($activo));
        $fila += 1;
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
$objPHPExcel->getActiveSheet()->getStyle('B1:M'.$fila)->applyFromArray($styleArray);
$worksheet->getStyle('B1:M'.$fila)->getAlignment()->setWrapText(true);
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Reporte General');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Listado de Usuarios OMNIABOX '.date("Y-m-d").'.xlsx"');
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

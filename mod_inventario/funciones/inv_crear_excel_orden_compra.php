<?PHP
/*
 * Genera el listado de fichas para exportar a excel.
 * @author      Mariel Umanzor
 * @date        2014-12-15
 */
ob_end_clean();
ob_start();
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
/*CONEXION CON BASE DE DATOS*/
include_once "../../libs/db_classes/db_mysql_conn.php";
include_once("../../libs/db_classes/db_inventario.php");
include_once("../../libs/db_classes/db_configuracion.php");
/*INSTANCIAMIENTOS*/
$DB_INV = new db_inventario();
$DB_CONF = new db_configuracion();

$cod_orden_compra    = $_POST['x1'];

$ORDEN      = $DB_INV->inv_obtener_info_orden_compra($cod_orden_compra);
$PRODUCTOS  = $DB_INV->inv_listado_ordenes_compra_productos($cod_orden_compra);
$GRANJA     = $DB_CONF->conf_obtener_info_granja($ORDEN[0]['cod_info_empresa']);
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
    ->setCellValue('B4', 'Nro.')
    ->setCellValue('C4', 'Vendor')
    ->setCellValue('D4', 'Net Contents')
    ->setCellValue('E4', 'ID')
    ->setCellValue('F4', 'Product')
    ->setCellValue('G4', 'Unit of Measure')
    ->setCellValue('H4', 'Price per Unit')
    ->setCellValue('I4', 'Total Price');

// Titulo Dirección
$worksheet->mergeCells('B1:I1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'Farm Purchase Request');
// Titulo principal
$worksheet->mergeCells('B2:C2');
$worksheet->mergeCells('D2:F2');
$worksheet->mergeCells('G2:I2');
$worksheet->mergeCells('B3:C3');
$worksheet->mergeCells('D3:F3');
$worksheet->mergeCells('G3:I3');
/*$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
/*$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', );*/
$gdImage = imagecreatefrompng('../../libs/imgs/Logo.png');
// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('Logo B&W');
$objDrawing->setDescription('Logo B&W');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(160);
$objDrawing->setCoordinates('D2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(125);
$worksheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$wizard = new PHPExcel_Helper_HTML();
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', $wizard->toRichTextObject(utf8_encode($GRANJA[0]['nombre_empresa'].'<br>'.$GRANJA[0]['direccion_linea_1'])));
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', $wizard->toRichTextObject('<strong>Desired Delivery Date: </strong>'.utf8_encode($ORDEN[0]['fecha_orden']).
    '<br><strong>Requested By: </strong>'.utf8_encode($ORDEN[0]['nombre_usuario']).'<br><strong>Received Date: </strong>'.utf8_encode($ORDEN[0]['fecha_recibido_pedido'])));

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', $wizard->toRichTextObject('<strong>Vendor Contact E-Mail: </strong>'.utf8_encode($ORDEN[0]['correo_contacto'])));
// Titulos de columnas
$worksheet->getStyle('B4:I4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B4:I4')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B4:I4')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B4:I4')->getFont()->setBold(true);
$worksheet->getColumndimension('A')->setWidth(5);
$worksheet->getColumndimension('B')->setWidth(25);
$worksheet->getColumndimension('C')->setWidth(25);
$worksheet->getColumndimension('D')->setWidth(20);
$worksheet->getColumndimension('E')->setWidth(20);
$worksheet->getColumndimension('F')->setWidth(20);
$worksheet->getColumndimension('G')->setWidth(20);
$worksheet->getColumndimension('H')->setWidth(20);
$worksheet->getColumndimension('I')->setWidth(20);
$fila = 5;

if (count($PRODUCTOS) > 0) {
    $correlativo = 1;
    foreach ($PRODUCTOS as $producto) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($ORDEN[0]['nombre_proveedor']))
            ->setCellValue('D' . $fila, utf8_encode($producto['cantidad']))
            ->setCellValue('E' . $fila, utf8_encode($producto['cod_quimico']))
            ->setCellValue('F' . $fila, utf8_encode($producto['nombre_quimico']))
            ->setCellValue('G' . $fila, utf8_encode($producto['unidad_medida']))
            ->setCellValue('H' . $fila, utf8_encode($producto['precio_quimico']))
            ->setCellValue('I' . $fila, $producto['cantidad']*$producto['precio_quimico']);
        $fila += 1;
        $correlativo += 1;
    }
}
$worksheet->mergeCells('B'.$fila.':I'.$fila);
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $fila, utf8_encode($ORDEN[0]['observaciones']));
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$fila = $fila;
$objPHPExcel->getActiveSheet()->getStyle('B1:I' . $fila)->applyFromArray($styleArray);
$worksheet->getStyle('B5:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:I'.$fila)->getAlignment()->setWrapText(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Farm Purchase Request');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="'.utf8_encode($GRANJA[0]['nombre_empresa']).' Purchase Request.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../adjuntos/Purchase_Request-'.$cod_orden_compra.'.xlsx');
exit;

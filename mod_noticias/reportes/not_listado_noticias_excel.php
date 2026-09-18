<?PHP
/*
 * Listado de noticias en formato de excel
 * @author      Oscar Raudales
 * @date        2015-06-18 
 */
session_start();
if(!isset($_SESSION['cod_usuario'])){
	header('Location: index.php');
}

include_once("../../libs/db_classes/mysql_conn.php"); 
//include_once("../../libs/db_classes/db_becas.php"); //quitar
include_once("../../libs/db_classes/db_noticias.php");
/*INSTANCIAMIENTOS*/
//$DB_NOTICIAS = new db_noticia();
//$NOTICIAS   = $DB_NOTICIAS->get_noticias_activas();
/*************quitar becas*************/
$DB_NOTICIAS = new db_noticia();
$NOTICIASx   = $DB_NOTICIAS->get_noticias_activas();
$fecha_inicio           = $_GET['x1'];
$fecha_fin              = $_GET['x2'];
$cod_tipo_grupo_evento  = $_GET['x3'];
$cod_tipo_evento        = $_GET['x4'] ;
$NOTICIAS   = $DB_NOTICIAS->get_noticias_por_fecha_tipo($fecha_inicio, $fecha_fin, $cod_tipo_grupo_evento, $cod_tipo_evento);
$tipo_grupo_evento = $DB_NOTICIAS->get_grupo_tipo_eventos_por_codigo($cod_tipo_grupo_evento);
$tipo_eventos = $DB_NOTICIAS->get_tipo_eventos($cod_tipo_grupo_evento);
/****************************************** */
/* EXCEL */

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');
/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../libs/PHPExcel/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$worksheet = $objPHPExcel->getActiveSheet(); 
// Add some data

/*
 * Este código arma el bloque con los tipos de evento de acuerdo al grupo seleccionado, 
 * también aplica los estilos correspondientes: fondo amarillo y orientación vertical.
 */

$contador_columna = 2;
foreach ($tipo_eventos as $tipo_evento){
    $contador_columna++;
    $columna_evento = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
    $objPHPExcel->getActiveSheet()->getStyle($columna_evento.'4')->getAlignment()->setTextRotation(90);   
    $worksheet->getColumndimension($columna_evento)->setWidth(3);
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna_evento.'4', utf8_encode($tipo_evento['tipo_evento']));
}
$worksheet->getStyle('D3:'.$columna_evento.'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
$worksheet->getStyle('D3:'.$columna_evento.'4')->getFill()->getStartColor()->setRGB('FFFFCC');
$worksheet->getStyle('D3:'.$columna_evento.'4')->getFont()->getColor()->setRGB('000000'); 
$worksheet->getStyle('D3:'.$columna_evento.'4')->getFont()->setBold(true);
/************** Fin del bloque de tipos de evento ******************/

/*
 * Encabezados que siguen al bloque de tipos de eventos.
 */
$columna_encabezado = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
$worksheet->mergeCells('D3:'.$columna_encabezado.'3');
$worksheet->mergeCells('A3:A4');
$worksheet->mergeCells('B3:B4');
$worksheet->mergeCells('C3:C4');
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'UNIVERSIDAD NACIONAL AUTÓNOMA DE HONDURAS')
            ->setCellValue('A2', 'Dirección de Investigación Científica y Sistemas de Posgrado')
            ->setCellValue('D3', 'Tipo de Evento: '.utf8_encode($tipo_grupo_evento[0]['tipo_grupo_evento']))
            ->setCellValue('A3', 'Nro.')
            ->setCellValue('B3', 'Fecha')
            ->setCellValue('C3', 'Nombre del evento');
$contador_columna++;
$columna_encabezado = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
$worksheet->getStyle($columna_encabezado)->getAlignment()->setWrapText(true);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->mergeCells($columna_encabezado.'3'.':'.$columna_encabezado.'4');
$worksheet->getColumndimension($columna_encabezado)->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna_encabezado.'3', 'Eventos organizados por la DICU');
$columna_dicu = $columna_encabezado;

$contador_columna++;
$columna_encabezado = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
$worksheet->getStyle($columna_encabezado)->getAlignment()->setWrapText(true);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->mergeCells($columna_encabezado.'3'.':'.$columna_encabezado.'4');
$worksheet->getColumndimension($columna_encabezado)->setWidth(20);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna_encabezado.'3', "Eventos organizados por Facultad /\n Centros Regionales y otros");
$columna_facultades = $columna_encabezado;

$contador_columna++;
$columna_encabezado = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
$worksheet->getStyle($columna_encabezado)->getAlignment()->setWrapText(true);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->mergeCells($columna_encabezado.'3'.':'.$columna_encabezado.'4');
$worksheet->getColumndimension($columna_encabezado)->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna_encabezado.'3', 'Lugar donde se desarolló');

$contador_columna++;
$columna_encabezado = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setWrapText(true);
$worksheet->mergeCells($columna_encabezado.'3'.':'.$columna_encabezado.'4');
$worksheet->getColumndimension($columna_encabezado)->setWidth(7);
$objPHPExcel->getActiveSheet()->getStyle($columna_encabezado.'3')->getAlignment()->setTextRotation(90);    
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna_encabezado.'3', "Número de\n participantes");

$contador_columna++;
$columna_encabezado = PHPExcel_Cell::stringFromColumnIndex($contador_columna);
$worksheet->mergeCells($columna_encabezado.'3'.':'.$columna_encabezado.'4');
$worksheet->getStyle($columna_encabezado)->getAlignment()->setWrapText(true);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle($columna_encabezado.'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->getColumndimension($columna_encabezado)->setWidth(25);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($columna_encabezado.'3', 'Observaciones');

//Estilo azul para el segundo bloque del encabezado
$worksheet->getStyle($columna_dicu.'3:'.$columna_encabezado.'3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
$worksheet->getStyle($columna_dicu.'3:'.$columna_encabezado.'3')->getFill()->getStartColor()->setRGB('CCE5FF');
$worksheet->getStyle($columna_dicu.'3:'.$columna_encabezado.'3')->getFont()->getColor()->setRGB('000000'); 
$worksheet->getStyle($columna_dicu.'3:'.$columna_encabezado.'3')->getFont()->setBold(true);

/************** Fin del segundo bloque de encabezados ******************/
// Titulo Dirección
$worksheet->mergeCells('A1:'.$columna_encabezado.'1');
$worksheet->getStyle('A1')->getFont()->setBold(true);
$worksheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
$worksheet->getStyle('A1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF'); 
$worksheet->getStyle('A1:'.$columna_encabezado.'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Dirección de Investigación Científica y Posgrado');
// Titulo principal
$worksheet->mergeCells('A2:'.$columna_encabezado.'2');
$worksheet->getStyle('A2')->getFont()->setBold(true);
$worksheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
$worksheet->getStyle('A2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('A2')->getFont()->getColor()->setRGB('FFFFFF'); 
$worksheet->getStyle('A2:'.$columna_encabezado.'2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B2', 'DICyP: Listado de Eventos Científicos');

$worksheet->getStyle('A3:C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID); 
$worksheet->getStyle('A3:C3')->getFill()->getStartColor()->setRGB('CCE5FF');
$worksheet->getStyle('A3:C3')->getFont()->getColor()->setRGB('000000'); 
$worksheet->getStyle('A3:C3')->getFont()->setBold(true);

$worksheet->getColumndimension('A')->setWidth(5);
$worksheet->getColumndimension('B')->setWidth(12);
$worksheet->getColumndimension('C')->setWidth(30);
$worksheet->getStyle('C')->getAlignment()->setWrapText(true);
//
$worksheet->getStyle('A3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle('B3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle('C3')->getAlignment()->setvertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$worksheet->getStyle('A3')->getAlignment()->sethorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('B3')->getAlignment()->sethorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('C3')->getAlignment()->sethorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$worksheet->getStyle('D3')->getAlignment()->sethorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$fila = 5;

/*
aqui el contenido
 ************************************/
$columnIndex = PHPExcel_Cell::columnIndexFromString($columna_facultades);
$columna_lugar = $columna = PHPExcel_Cell::stringFromColumnIndex($columnIndex);
$columna_participantes = PHPExcel_Cell::stringFromColumnIndex($columnIndex+1);
$columna_observaciones = PHPExcel_Cell::stringFromColumnIndex($columnIndex+2);
$worksheet->getStyle('A5:'.$columna_observaciones.$fila)->getAlignment()->setvertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);    
if(count($NOTICIAS) > 0){
    
    foreach($NOTICIAS as $NOTICIA){									
        $columna = PHPExcel_Cell::stringFromColumnIndex($NOTICIA['cod_tipo_evento']+2);

        switch ($NOTICIA['cod_unidad_academica'])    {
            case 20 : $columna_unidad = $columna_dicu;
                    $NOTICIA['tipo_unidad_academica']='';
                    break;
            default :
                $columna_unidad = $columna_facultades;
                $NOTICIA['tipo_unidad_academica'].=': ';
        };
        

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$fila, $NOTICIA['cod_noticia'])        
                ->setCellValue('B'.$fila, $NOTICIA['fecha_inicio'])        
                ->setCellValue('C'.$fila, utf8_encode($NOTICIA['titulo']))
                ->setCellValue($columna.$fila, 'X')
                ->setCellValue($columna_unidad.$fila, utf8_encode($NOTICIA['tipo_unidad_academica']).utf8_encode($NOTICIA['unidad_academica']))                                   
                ->setCellValue($columna_lugar.$fila, utf8_encode($NOTICIA['lugar']))       
                ->setCellValue($columna_participantes.$fila, utf8_encode($NOTICIA['cantidad_participantes']))    
                ->setCellValue($columna_observaciones.$fila, utf8_encode ($NOTICIA['observaciones']));
        $fila += 1;
	}
}

/* Codigo para imágenes flotantes.   *//*
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$logo = '../../mod_noticias/adjuntos/_49_2015-06-26_Logo_UNAH.png'; // Provide path to your logo file
$objDrawing->setPath($logo);
$objDrawing->setOffsetX(1);    // setOffsetX works properly
$objDrawing->setOffsetY(1);  //setOffsetY has no effect offset solo aplica al primero que encuentra
$objDrawing->setCoordinates('B3');
$objDrawing->setHeight(200); // logo height esto solo toma el último que encuentra
$objDrawing->setWidth(85); // logo width
$objDrawing->setWorksheet($worksheet); 
*/
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$fila = $fila - 1;
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$columna_encabezado.$fila)->applyFromArray($styleArray);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Listado de Investigaciones');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Listado de investigaciones '.date("Y-m-d").'.xlsx"');
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

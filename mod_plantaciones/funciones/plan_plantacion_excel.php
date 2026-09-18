<?PHP
/*
 * Genera el listado de fichas para exportar a excel.
 * @author      Mariel Umanzor
 * @date        2014-12-15
 */
ob_end_clean();
ob_start();
session_start();
ini_set('memory_limit', '128M');
/*CONEXION CON BASE DE DATOS*/
include_once "../../libs/db_classes/db_mysql_conn.php";
include_once "../../libs/db_classes/db_plantaciones.php";
/*INSTANCIAMIENTOS*/
$DB_PLANT   = new db_plantaciones();
$cod_plantacion = $_GET['x1'];

$PLANTACION     = $DB_PLANT->plan_obtener_info_plantacion($cod_plantacion);
$BLOQUES        = $DB_PLANT->plan_listado_bloques_plantacion($cod_plantacion);
$PLANTADOS      = $DB_PLANT->plan_listado_bloques_plantados_plantacion($cod_plantacion);
$OBSERVACIONES  = $DB_PLANT->plan_listado_observaciones_plantacion($cod_plantacion);
$APLICACIONES   = $DB_PLANT->plan_listado_aplicaciones_quimicos_plantacion($cod_plantacion);
$CALIBRACIONES  = $DB_PLANT->plan_listado_calibracion_plantacion($cod_plantacion);
$CAMBIOS        = $DB_PLANT->plan_listado_cambios_plantacion($cod_plantacion);
$EXPLORACIONES  = $DB_PLANT->plan_listado_exploraciones_plantacion($cod_plantacion);
$FORMULARIOS    = $DB_PLANT->plan_listado_formularios_respuestas_plantacion($cod_plantacion);
$LIMPIEZAS      = $DB_PLANT->plan_listado_formularios_limpieza_plantacion($cod_plantacion);
$WORKSHEETS     = $DB_PLANT->plan_listado_formularios_harvesting_worksheet_plantacion($cod_plantacion);
$CHECKLISTS     = $DB_PLANT->plan_listado_formularios_harvesting_checklist_plantacion($cod_plantacion);
$TRASPLANTES    = $DB_PLANT->plan_listado_trasplantes_plantaciones($cod_plantacion);

$fertilizantes = [ '',
            'ON',
            'OFF',
            'Only Water - Sólo Agua'];
$etapas = [ '',
            'Stubble',
            'Leaf Up Stubbles',
            'High Cress'];
$loose_bunches = [ 'Bunches',
            'Loose'];
$conventional_organic = [ 'Organic',
            'Conventional'];
$question = [ 'No',
            'Yes',
            'NA'];

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

// Titulo Dirección
$worksheet->mergeCells('B1:AD1');
$worksheet->getStyle('B1')->getFont()->setBold(true);
$worksheet->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B1')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B1')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B1:AD1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B1', 'BW Farming');
// Titulo principal
$worksheet->mergeCells('B2:AD2');
$worksheet->getStyle('B2')->getFont()->setBold(true);
$worksheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B2')->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B2')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B2:AD2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B2', 'BW Farming: Planting Information');
// Titulos de columnas
$worksheet->getStyle('B3:AD3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B3:AD3')->getFill()->getStartColor()->setRGB('043379');
$worksheet->getStyle('B3:AD3')->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B3:AD3')->getFont()->setBold(true);
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
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B3', 'Grower')
    ->setCellValue('C3', utf8_encode($PLANTACION[0]['nombre_empresa']))
    ->setCellValue('D3', 'Year')
    ->setCellValue('E3', utf8_encode($PLANTACION[0]['anio_plantacion']))
    ->setCellValue('F3', 'No.')
    ->setCellValue('G3', utf8_encode($PLANTACION[0]['num_plantacion']))
    ->setCellValue('H3', 'Planned Planting Date')
    ->setCellValue('I3', utf8_encode($PLANTACION[0]['fecha_plantacion_planeada']))
    ->setCellValue('J3', 'Acres Planted')
    ->setCellValue('K3', utf8_encode($PLANTACION[0]['acres_plantados']))
    ->setCellValue('L3', 'Season')
    ->setCellValue('M3', utf8_encode($PLANTACION[0]['codigo_temporada']));

$fila = 4;

if (count($BLOQUES) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Planting Zones');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Zone')
        ->setCellValue('D'.$fila, 'Block')
        ->setCellValue('E'.$fila, 'Acres')
        ->setCellValue('F'.$fila, 'Stage');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($BLOQUES as $bloque) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($bloque['zona']))
            ->setCellValue('D' . $fila, utf8_encode($bloque['clave_bloque'] != '' ? ($bloque['clave_bloque'].'-'.$bloque['nombre_bloque']):$bloque['nombre_bloque']))
            ->setCellValue('E' . $fila, utf8_encode($bloque['cantidad_acres']))
            ->setCellValue('F' . $fila, utf8_encode($bloque['estado_plantacion_english']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($PLANTADOS) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Seed Planted');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Seed')
        ->setCellValue('D'.$fila, 'Lo. No')
        ->setCellValue('E'.$fila, 'User Amount')
        ->setCellValue('F'.$fila, 'Blocks')
        ->setCellValue('G'.$fila, 'Machinery')
        ->setCellValue('H'.$fila, 'Description');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($PLANTADOS as $plantado) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('C' . $fila, utf8_encode($plantado['nombre_semilla']))
            ->setCellValue('C' . $fila, utf8_encode($plantado['numero_lote']))
            ->setCellValue('E' . $fila, utf8_encode($plantado['cantidad_usada']))
            ->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantado['bloques'])))))))
            ->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantado['maquinarias'])))))))
            ->setCellValue('F' . $fila, utf8_encode($plantado['descripcion']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}


/* FORMULARIOS*/

if (count($FORMULARIOS) > 0) {
    $correlativo = 1;
    $cod_formulario = $FORMULARIOS[0]['cod_formulario'];
    $user_insert = utf8_encode($FORMULARIOS[0]['user_insert']);

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Forms');
    $fila++;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, utf8_encode($FORMULARIOS[0]['nombre_formulario']));
    $fila++;
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'User: ')
        ->setCellValue('C'.$fila, utf8_encode($FORMULARIOS[0]['nombre_usuario']))
        ->setCellValue('D'.$fila, 'Date: ')
        ->setCellValue('E'.$fila, utf8_encode($FORMULARIOS[0]['date_insert']));
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Name')
        ->setCellValue('D'.$fila, 'Text')
        ->setCellValue('E'.$fila, 'Date');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($FORMULARIOS as $formulario) 
    {
        $cierre = 0;
        if ($user_insert != $formulario['user_insert'] || $cod_formulario != $formulario['cod_formulario']) 
        {
            //$worksheet->mergeCells('B'.$fila.':AD'.$fila);
            $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
            $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
            $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
            $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$fila, utf8_encode($formulario['nombre_formulario']))
                ->setCellValue('C'.$fila, 'User: '.utf8_encode($formulario['nombre_usuario']))
                ->setCellValue('D'.$fila, 'Date: '.utf8_encode($formulario['date_insert']));
            $fila++;

            $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
            $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
            $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
            $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$fila, 'No.')
                ->setCellValue('C'.$fila, 'Name')
                ->setCellValue('D'.$fila, 'Text')
                ->setCellValue('E'.$fila, 'Date');
            $fila++;
            $cod_formulario = $formulario['cod_formulario'];
            $user_insert = utf8_encode($formulario['user_insert']);
            $cierre = 1;
            $correlativo = 1;
        }
        if ($formulario['cod_tipo_item'] == 1) {
            //Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $fila, $correlativo)
                //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
                ->setCellValue('C' . $fila, utf8_encode($formulario['texto_item']))
                ->setCellValue('D' . $fila, utf8_encode($formulario['observacion'] == 1 ? 'Yes':'No'));
        }
        if ($formulario['cod_tipo_item'] == 2) {
            //Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $fila, $correlativo)
                //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
                ->setCellValue('C' . $fila, utf8_encode($formulario['texto_item']))
                ->setCellValue('D' . $fila, utf8_encode($formulario['observacion']));
        }
        if ($formulario['cod_tipo_item'] == 3) {
            //Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $fila, $correlativo)
                //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
                ->setCellValue('C' . $fila, utf8_encode($formulario['nombre_item']))
                ->setCellValue('D' . $fila, utf8_encode($formulario['respuesta_selectpicker']));
        }
        if ($formulario['cod_tipo_item'] == 4) {
            //Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $fila, $correlativo)
                //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
                ->setCellValue('C' . $fila, utf8_encode($formulario['nombre_item']))
                ->setCellValue('D' . $fila, utf8_encode($formulario['observacion']));
        }
        if ($formulario['cod_tipo_item'] == 5) {
            //Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $fila, $correlativo)
                //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
                ->setCellValue('C' . $fila, utf8_encode($formulario['texto_item']))
                ->setCellValue('D' . $fila, utf8_encode($formulario['observacion']));
        }
        if ($formulario['cod_tipo_item'] == 6) {
            //Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . $fila, $correlativo)
                //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
                ->setCellValue('C' . $fila, utf8_encode($formulario['texto_item']))
                ->setCellValue('D' . $fila, utf8_encode($formulario['observacion']));
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E' . $fila, utf8_encode($formulario['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

$worksheet->mergeCells('B'.$fila.':AD'.$fila);
$worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
$worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
$worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
$worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('B'.$fila, 'Planting History');
$fila++;

if (count($CAMBIOS) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Stage Changes');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Stage')
        ->setCellValue('D'.$fila, 'User')
        ->setCellValue('E'.$fila, 'Date');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($CAMBIOS as $cambio) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('C' . $fila, utf8_encode($cambio['estado_plantacion_english']))
            ->setCellValue('D' . $fila, utf8_encode($cambio['nombre_usuario']))
            ->setCellValue('E' . $fila, utf8_encode($cambio['date_update']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($CALIBRACIONES) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Calibrations');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Fertilizier')
        ->setCellValue('D'.$fila, 'Hours')
        ->setCellValue('E'.$fila, 'Observation')
        ->setCellValue('F'.$fila, 'User')
        ->setCellValue('G'.$fila, 'Date')
        ->setCellValue('H'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($CALIBRACIONES as $calibracion) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('C' . $fila, utf8_encode($fertilizantes[$calibracion['cod_fertilizante']]))
            ->setCellValue('D' . $fila, utf8_encode($calibracion['horas_aplicacion_calibrar']))
            ->setCellValue('E' . $fila, utf8_encode($calibracion['observaciones_calibrar']))
            ->setCellValue('F' . $fila, utf8_encode($calibracion['nombre_ingresa']))
            ->setCellValue('G' . $fila, utf8_encode($calibracion['fecha_calibracion']))
            ->setCellValue('H' . $fila, utf8_encode($calibracion['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($APLICACIONES) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Chemical Applies');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Blocks')
        ->setCellValue('D'.$fila, 'Supervisor')
        ->setCellValue('E'.$fila, 'Operator')
        ->setCellValue('F'.$fila, 'Date')
        ->setCellValue('G'.$fila, 'Chemicals')
        ->setCellValue('H'.$fila, 'Quantities')
        ->setCellValue('I'.$fila, 'Machinery')
        ->setCellValue('J'.$fila, 'Description')
        ->setCellValue('K'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($APLICACIONES as $aplicacion) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($aplicacion['bloques'])))))))
            ->setCellValue('D' . $fila, utf8_encode($aplicacion['usuario_supervisor']))
            ->setCellValue('E' . $fila, utf8_encode($aplicacion['usuario_operador']))
            ->setCellValue('F' . $fila, utf8_encode($aplicacion['fecha_aplicacion_supervisor']))
            ->setCellValue('G' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($aplicacion['nombre_quimicos'])))))))
            ->setCellValue('H' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($aplicacion['cantidad_quimicos'])))))))
            ->setCellValue('I' . $fila, utf8_encode($aplicacion['nombre_maquinaria']))
            ->setCellValue('J' . $fila, utf8_encode($aplicacion['descripcion_aplicar_quimico']))
            ->setCellValue('K' . $fila, utf8_encode($aplicacion['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($EXPLORACIONES) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Explorations');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Growth stage')
        ->setCellValue('D'.$fila, 'Insects')
        ->setCellValue('E'.$fila, 'Diseases')
        ->setCellValue('F'.$fila, 'Weeds')
        ->setCellValue('G'.$fila, 'Others')
        ->setCellValue('H'.$fila, 'Observation')
        ->setCellValue('I'.$fila, 'User')
        ->setCellValue('J'.$fila, 'Date')
        ->setCellValue('K'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($EXPLORACIONES as $exploracion) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($etapas[$exploracion['etapa_crecimiento']]))
            ->setCellValue('D' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim(
                                                                    'Worms - Gusanos: '.utf8_encode($exploracion['gusanos']).'<br>'.
                                                                    'Eggs - Huevos: '.utf8_encode($exploracion['huevos']).'<br>'.
                                                                    'Leaf Hoppers - Saltahojas: '.utf8_encode($exploracion['saltahojas']).'<br>'.
                                                                    'Aphids - Afidos: '.utf8_encode($exploracion['afidos']).'<br>'.
                                                                    'Stink Bugs - Chinches: '.utf8_encode($exploracion['chinches']).'<br>'.
                                                                    'Gnats - Moscos: '.utf8_encode($exploracion['moscos']).'<br>'.
                                                                    'Flea Beetles - Escarabajos: '.utf8_encode($exploracion['escarabajos']).'<br>'.
                                                                    'Cyclaman Mites - Acaros: '.utf8_encode($exploracion['acaros']).'<br>')))))))
            ->setCellValue('E' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim(
                                                                    'Cercospora Leaf Spot: '.utf8_encode($exploracion['cercospora_leaf_spot']).'<br>'.
                                                                    'Pythium/Damp Off: '.utf8_encode($exploracion['pythium']).'<br>'.
                                                                    'Rhizoctonia Aerial Blight: '.utf8_encode($exploracion['rhizoctonia']).'<br>'.
                                                                    'Bacteria: '.utf8_encode($exploracion['bacteria']).'<br>'.
                                                                    'Sclerotinia: '.utf8_encode($exploracion['sclerotinia']).'<br>'.
                                                                    'Alternaria Specks: '.utf8_encode($exploracion['alternaria_specks']).'<br>'.
                                                                    'Mildew: '.utf8_encode($exploracion['mildew']).'<br>'.
                                                                    'Virus: '.utf8_encode($exploracion['virus']).'<br>')))))))
            ->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim(
                                                                    'Dollarweed: '.utf8_encode($exploracion['dolar']).'<br>'.
                                                                    'Frogs Bit: '.utf8_encode($exploracion['frogs_bit']).'<br>'.
                                                                    'Mud Plantain: '.utf8_encode($exploracion['plantas_lodo']).'<br>'.
                                                                    'Tube Weed: '.utf8_encode($exploracion['tripa_pollo']).'<br>'.
                                                                    'Grass: '.utf8_encode($exploracion['zacate']).'<br>')))))))
            ->setCellValue('G' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim(
                                                                    'Damaged Leaves: '.utf8_encode($exploracion['hojas_danadas']).'<br>'.
                                                                    'Purple Stem: '.utf8_encode($exploracion['tallos_purpuras']).'<br>'.
                                                                    'Watercress rooted: '.utf8_encode($exploracion['berro_enraizado']).'<br>')))))))
            ->setCellValue('H' . $fila, utf8_encode($exploracion['observacion']))
            ->setCellValue('I' . $fila, utf8_encode($exploracion['nombre_usuario']))
            ->setCellValue('J' . $fila, utf8_encode($exploracion['fecha_exploracion']))
            ->setCellValue('K' . $fila, utf8_encode($exploracion['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($LIMPIEZAS) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Cleaning and sanitizing of harvesting equipment');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Date')
        ->setCellValue('D'.$fila, 'Time')
        ->setCellValue('E'.$fila, 'Equipment #')
        ->setCellValue('F'.$fila, 'Cleaning tools/materials')
        ->setCellValue('G'.$fila, 'Cleaning with potable water')
        ->setCellValue('H'.$fila, 'Cleaning with detergent')
        ->setCellValue('I'.$fila, 'Scrubbing')
        ->setCellValue('J'.$fila, 'Rinse with potable water')
        ->setCellValue('K'.$fila, 'Sanitizing with chlorine')
        ->setCellValue('L'.$fila, 'Post sanitizing rinse')
        ->setCellValue('M'.$fila, 'User')
        ->setCellValue('N'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($LIMPIEZAS as $limpieza) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            //->setCellValue('C' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($limpieza['bloques'])))))))
            ->setCellValue('C' . $fila, utf8_encode($limpieza['fecha_limpieza']))
            ->setCellValue('D' . $fila, utf8_encode($limpieza['vez_limpieza']))
            ->setCellValue('E' . $fila, utf8_encode($limpieza['equipo_limpieza']))
            ->setCellValue('F' . $fila, utf8_encode($limpieza['cleaning_tools'] == 1 ? 'Yes':'No'))
            ->setCellValue('G' . $fila, utf8_encode($limpieza['cleaning_potable_water'] == 1 ? 'Yes':'No'))
            ->setCellValue('H' . $fila, utf8_encode($limpieza['cleaning_detergent'] == 1 ? 'Yes':'No'))
            ->setCellValue('I' . $fila, utf8_encode($limpieza['scrubbing'] == 1 ? 'Yes':'No'))
            ->setCellValue('J' . $fila, utf8_encode($limpieza['rinse_potable_water'] == 1 ? 'Yes':'No'))
            ->setCellValue('K' . $fila, utf8_encode($limpieza['sanitizing_chlorine'] == 1 ? 'Yes':'No'))
            ->setCellValue('L' . $fila, utf8_encode($limpieza['post_sanitizing'] == 1 ? 'Yes':'No'))
            ->setCellValue('M' . $fila, utf8_encode($limpieza['nombre_usuario']))
            ->setCellValue('N' . $fila, utf8_encode($limpieza['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($WORKSHEETS) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Harvesting Worksheet');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Date')
        ->setCellValue('D'.$fila, 'PHI')
        ->setCellValue('E'.$fila, 'Loose')
        ->setCellValue('F'.$fila, 'Increment (Bunch-Cello)')
        ->setCellValue('G'.$fila, 'Area FINISHED')
        ->setCellValue('H'.$fila, 'Acres Harvested')
        ->setCellValue('I'.$fila, 'PO# Request')
        ->setCellValue('J'.$fila, 'Quantity Harvested')
        ->setCellValue('K'.$fila, 'Comments')
        ->setCellValue('L'.$fila, 'User')
        ->setCellValue('M'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($WORKSHEETS as $worksheet) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($worksheet['harvest_date']))
            ->setCellValue('D' . $fila, utf8_encode($worksheet['phi'] == 1 ? 'Yes':'No'))
            ->setCellValue('E' . $fila, utf8_encode($worksheet['cellos'] == 1 ? 'Yes':'No'))
            ->setCellValue('F' . $fila, utf8_encode($loose_bunches[$worksheet['increment_bunch_cello']]))
            ->setCellValue('G' . $fila, utf8_encode($worksheet['area_finished'] == 1 ? 'Yes':'No'))
            ->setCellValue('H' . $fila, utf8_encode($worksheet['acres_harvested']))
            ->setCellValue('I' . $fila, utf8_encode($worksheet['orden_compra']))
            ->setCellValue('J' . $fila, utf8_encode($worksheet['cantidad_cosechada']))
            ->setCellValue('K' . $fila, utf8_encode($worksheet['commments_harvesting_worksheet']))
            ->setCellValue('L' . $fila, utf8_encode($worksheet['nombre_usuario']))
            ->setCellValue('M' . $fila, utf8_encode($worksheet['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($CHECKLISTS) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Pre-Operational/Harvesting Checklist');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Date')
        ->setCellValue('D'.$fila, 'Loose Bunches')
        ->setCellValue('E'.$fila, 'Conventional Organic')
        ->setCellValue('F'.$fila, 'Are harvest crews training records up to date?')
        ->setCellValue('G'.$fila, 'Are there sick workers?')
        ->setCellValue('H'.$fila, 'Have sick workers been reassigned to non-food contactjobs?')
        ->setCellValue('I'.$fila, 'Have harvesters properly covered open wounds, lesions, boils, etc.?')
        ->setCellValue('J'.$fila, 'Has the crew been instructed on cornpany policies regarding eating, drinking, tobacco use, iewelrv and other safety rules?')
        ->setCellValue('K'.$fila, 'Is the harvesting crew wearing clean and proper clothing?')
        ->setCellValue('L'.$fila, 'Are harvesting employees wearing hairnets, hats, cap, etc.?')
        ->setCellValue('M'.$fila, 'Are harvesting hands clean and sanítized?')
        ->setCellValue('N'.$fila, 'Are the portable toilets and sanitation station located at 1/4 mile or less from the harvestinz area?')
        ->setCellValue('O'.$fila, 'Have all harvesting tools been cleaned and sanitized?')
        ->setCellValue('P'.$fila, 'Are haul trucks properly cleaned and, if necessary sanitized?')
        ->setCellValue('Q'.$fila, 'Has the truck used for harvesting-hauling produce and themechanical harvesting machine been inspected for oil-diesel/gasoline leaking, loose metal parts, or any other defect posing potential contamination issues?')
        ->setCellValue('R'.$fila, 'Has the truck used for harvesting-hauling produce fit for use?')
        ->setCellValue('S'.$fila, 'Has the mechanical harvesting rnachinery been cleaned and sanitized?')
        ->setCellValue('T'.$fila, 'Is the Mechanical Harvesting machine fit for use?')
        ->setCellValue('U'.$fila, 'Has ali the harvesting equipment been inspected for glass breakage?')
        ->setCellValue('V'.$fila, 'Have ali harvesting totes been cleaned and sanitized?')
        ->setCellValue('W'.$fila, 'Quantity of knives/hooks issued')
        ->setCellValue('X'.$fila, 'Quantity of knives/hooks returned')
        ->setCellValue('Y'.$fila, 'Are there evidence of animal intrusion (fecal material), pest ínfestation, etc. that can pose a risk of contamination on the crop to harvest?')
        ->setCellValue('Z'.$fila, 'Have buffer zones being implemented in the event of a contamination? 30ft (9.1 m) from flooded areas and 5ft (1.5m) from evidence of pest activity.')
        ->setCellValue('AA'.$fila, 'Has the crop-block/seetion been cleared for harvest?')
        ->setCellValue('AB'.$fila, 'Preventive/corrective actions')
        ->setCellValue('AC'.$fila, 'User')
        ->setCellValue('AD'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($CHECKLISTS as $checklist) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            ->setCellValue('C' . $fila, utf8_encode($checklist['fecha_checklist']))
            ->setCellValue('D' . $fila, utf8_encode($loose_bunches[$checklist['loose_bunches']]))
            ->setCellValue('E' . $fila, utf8_encode($conventional_organic[$checklist['conventional_organic']]))
            ->setCellValue('F' . $fila, utf8_encode($question[$checklist['question1']]))
            ->setCellValue('G' . $fila, utf8_encode($question[$checklist['question2']]))
            ->setCellValue('H' . $fila, utf8_encode($question[$checklist['question3']]))
            ->setCellValue('I' . $fila, utf8_encode($question[$checklist['question4']]))
            ->setCellValue('J' . $fila, utf8_encode($question[$checklist['question5']]))
            ->setCellValue('K' . $fila, utf8_encode($question[$checklist['question6']]))
            ->setCellValue('L' . $fila, utf8_encode($question[$checklist['question7']]))
            ->setCellValue('M' . $fila, utf8_encode($question[$checklist['question8']]))
            ->setCellValue('N' . $fila, utf8_encode($question[$checklist['question9']]))
            ->setCellValue('O' . $fila, utf8_encode($question[$checklist['question10']]))
            ->setCellValue('P' . $fila, utf8_encode($question[$checklist['question11']]))
            ->setCellValue('Q' . $fila, utf8_encode($question[$checklist['question12']]))
            ->setCellValue('R' . $fila, utf8_encode($question[$checklist['question13']]))
            ->setCellValue('S' . $fila, utf8_encode($question[$checklist['question14']]))
            ->setCellValue('T' . $fila, utf8_encode($question[$checklist['question15']]))
            ->setCellValue('U' . $fila, utf8_encode($question[$checklist['question16']]))
            ->setCellValue('V' . $fila, utf8_encode($question[$checklist['question17']]))
            ->setCellValue('W' . $fila, utf8_encode($checklist['question18']))
            ->setCellValue('X' . $fila, utf8_encode($checklist['question19']))
            ->setCellValue('Y' . $fila, utf8_encode($question[$checklist['question20']]))
            ->setCellValue('Z' . $fila, utf8_encode($question[$checklist['question21']]))
            ->setCellValue('AA' . $fila, utf8_encode($question[$checklist['question22']]))
            ->setCellValue('AB' . $fila, utf8_encode($checklist['actions']))
            ->setCellValue('AC' . $fila, utf8_encode($checklist['nombre_usuario']))
            ->setCellValue('AD' . $fila, utf8_encode($checklist['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

if (count($TRASPLANTES) > 0) {
    $correlativo = 1;

    $worksheet->mergeCells('B'.$fila.':AD'.$fila);
    $worksheet->getStyle('B'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila)->getFill()->getStartColor()->setRGB('0983bc');
    $worksheet->getStyle('B'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'Transplants');
    $fila++;

    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->setBold(true);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFill()->getStartColor()->setRGB('043379');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getFont()->getColor()->setRGB('FFFFFF');
    $worksheet->getStyle('B'.$fila.':AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'.$fila, 'No.')
        ->setCellValue('C'.$fila, 'Grower Send')
        ->setCellValue('D'.$fila, 'Grower Receive')
        ->setCellValue('E'.$fila, 'Blocks Send')
        ->setCellValue('F'.$fila, 'Amount')
        ->setCellValue('G'.$fila, 'Blocks Receive')
        ->setCellValue('H'.$fila, 'Observation')
        ->setCellValue('I'.$fila, 'User')
        ->setCellValue('J'.$fila, 'Entry');
    $fila++;
    $wizard = new PHPExcel_Helper_HTML();
    foreach ($TRASPLANTES as $trasplante) {
        //Miscellaneous glyphs, UTF-8
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B' . $fila, $correlativo)
            //->setCellValue('F' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($plantacion['bloques'])))))))
            ->setCellValue('C' . $fila, utf8_encode($trasplante['nombre_empresa_envia'].'-'.$trasplante['anio_plantacion_envia'].'-'.$trasplante['num_plantacion_envia'].'-'.$trasplante['codigo_temporada_envia']))
            ->setCellValue('D' . $fila, utf8_encode($trasplante['nombre_empresa_recibe'].'-'.$trasplante['anio_plantacion_recibe'].'-'.$trasplante['num_plantacion_recibe'].'-'.$trasplante['codigo_temporada_recibe']))
            ->setCellValue('E' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($trasplante['bloques'])))))))
            ->setCellValue('F' . $fila, utf8_encode($trasplante['cantidad']))
            ->setCellValue('G' . $fila, $wizard->toRichTextObject(utf8_decode(trim(str_replace('</br>', '<br>', utf8_encode(trim($trasplante['bloques_trasplante'])))))))
            ->setCellValue('H' . $fila, utf8_encode($trasplante['observacion']))
            ->setCellValue('I' . $fila, utf8_encode($trasplante['nombre_usuario']))
            ->setCellValue('J' . $fila, utf8_encode($trasplante['date_insert']));
        $fila += 1;
        $correlativo += 1;
    }
    $fila++;
}

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$fila = $fila - 1;
$objPHPExcel->getActiveSheet()->getStyle('B1:AD'.$fila)->applyFromArray($styleArray);
$worksheet->getStyle('B4:AD'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$worksheet->getStyle('B1:AD'.$fila)->getAlignment()->setWrapText(true);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Planting Information');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Planting '.utf8_encode($PLANTACION[0]['anio_plantacion'].'-'.$PLANTACION[0]['num_plantacion'].'-'.$PLANTACION[0]['codigo_temporada']).'.xlsx"');
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

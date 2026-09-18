<?PHP
/*
 * Genera el listado de siembras para exportar a excel.
 * @author      Dan Urquía
 * @date        2023-11-01
 */

 session_start();
 if(!isset($_SESSION['cod_usuario'])){
   header('Location: index.php');
 }

 require '../../vendor/autoload.php';
 
 use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
 use PhpOffice\PhpSpreadsheet\Style\{Alignment, Fill, Border, Color};

 /*CONEXION CON BASE DE DATOS*/
 include_once("../../libs/db_classes/db_mysql_conn.php");
 include_once("../../libs/db_classes/db_reportes_inventario.php");
 $fecha_inicial 	= $_GET['x1'];
 $fecha_final 	  = $_GET['x2'];
 $estados     	  = $_GET['x3'];

 /*INSTANCIAMIENTOS*/
 $DB_REPORTE = new db_rep_inv();
 $SOWS       = $DB_REPORTE->get_siembras_listado($fecha_inicial, $fecha_final, $estados);

 $spreadsheet = new Spreadsheet();

 $hojaActiva = $spreadsheet->getActiveSheet();
 $hojaActiva->setTitle("Sows Report");
 
 $tableTitle = [
    'font'=>[
      'color'=>[
        'rgb'=>'FFFFFF'
      ],
      'bold'=>true,
      'size'=>15
    ],
    'fill'=>[
      'fillType'=>Fill::FILL_SOLID,
      'startColor'=>[
        'rgb'=>'9307f5'
      ]
      ],
    ];

  $tableHead = [
    'font'=>[
      'color'=>[
        'rgb'=>'FFFFFF'
      ],
    ],
    'fill'=>[
      'fillType'=>Fill::FILL_SOLID,
      'startColor'=>[
        'rgb'=>'5703a6'
      ]
      ],
    ];

 //Header and title
 $spreadsheet->getActiveSheet()->setCellValue('A1','Lady Moon Farms - Sows Report from '.$fecha_inicial.' to '.$fecha_final);
 $spreadsheet->getActiveSheet()->mergeCells('A1:J1');
 $spreadsheet->getActiveSheet()->getStyle('A1:J1')->applyFromArray($tableTitle);
 $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 $spreadsheet->getActiveSheet()->getStyle('A2:J2')->applyFromArray($tableHead);

 $hojaActiva->getColumndimension('A')->setWidth(15);
 $hojaActiva->getColumndimension('B')->setWidth(10);
 $hojaActiva->getColumndimension('C')->setWidth(40);
 $hojaActiva->getColumndimension('D')->setWidth(20);
 $hojaActiva->getColumndimension('E')->setWidth(10);
 $hojaActiva->getColumndimension('F')->setWidth(15);
 $hojaActiva->getColumndimension('G')->setWidth(10);
 $hojaActiva->getColumndimension('H')->setWidth(15);
 $hojaActiva->getColumndimension('I')->setWidth(15);
 $hojaActiva->getColumndimension('J')->setWidth(15);
 
 $hojaActiva->setCellValue('A2', 'Order Number');
 $hojaActiva->setCellValue('B2', 'Item');
 $hojaActiva->setCellValue('C2', 'Seed');
 $hojaActiva->setCellValue('D2', 'Greenhouse');
 $hojaActiva->setCellValue('E2', 'State');
 $hojaActiva->setCellValue('F2', 'Quantity');
 $hojaActiva->setCellValue('G2', 'Overseed');
 $hojaActiva->setCellValue('H2', 'Expected');
 $hojaActiva->setCellValue('I2', 'Expected sow');
 $hojaActiva->setCellValue('J2', 'Expected delivery');

 $FILA = 3;

 if(count($SOWS) > 0) {
  $correlativo = 1;
  foreach ($SOWS as $SOW) {

    $hojaActiva->setCellValue('A'.$FILA, utf8_encode($SOW['NUMERO_ORDEN']));
    $hojaActiva->setCellValue('B'.$FILA, utf8_encode($SOW['ITEM']));
    $hojaActiva->setCellValue('C'.$FILA, utf8_encode($SOW['SEED']));
    $hojaActiva->setCellValue('D'.$FILA, utf8_encode($SOW['GREENHOUSE']));
    $hojaActiva->setCellValue('E'.$FILA, utf8_encode($SOW['ESTADO']));
    $hojaActiva->setCellValue('F'.$FILA, utf8_encode($SOW['CANTIDAD']));
    $hojaActiva->setCellValue('G'.$FILA, utf8_encode($SOW['OVERSEED']));
    $hojaActiva->setCellValue('H'.$FILA, utf8_encode($SOW['TOTAL']));
    $hojaActiva->setCellValue('I'.$FILA, utf8_encode($SOW['EXP_SOW']));
    $hojaActiva->setCellValue('J'.$FILA, utf8_encode($SOW['EXP_DELIVERY']));


    //$spreadsheet->getActiveSheet()->getStyle('G'.$FILA)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $FILA++;
  }
} 

//AutoFilter
$firstRow = 2;
$lastRow  = $FILA - 1;
$spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.':J'.$lastRow);

//Borders
$spreadsheet
    ->getActiveSheet()
    ->getStyle("A".$firstRow.':J'.$lastRow)
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Sows Report '.date("Y-m-d").'.xls"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');

exit;
<?PHP
/*
 * Genera el listado de resumen de semillas para exportar a excel.
 * @author      Dan Urquía
 * @date        2023-11-08
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

 /*INSTANCIAMIENTOS*/
 $DB_REPORTE = new db_rep_inv();
 $SOWS       = $DB_REPORTE->get_resumen_seeds($fecha_inicial, $fecha_final);

 $spreadsheet = new Spreadsheet();

 $hojaActiva = $spreadsheet->getActiveSheet();
 $hojaActiva->setTitle("Seed Summary Report");
 
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
 $spreadsheet->getActiveSheet()->setCellValue('A1','Lady Moon Farms - Seeds Summary Report from '.$fecha_inicial.' to '.$fecha_final);
 $spreadsheet->getActiveSheet()->mergeCells('A1:I1');
 $spreadsheet->getActiveSheet()->getStyle('A1:I1')->applyFromArray($tableTitle);
 $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 $spreadsheet->getActiveSheet()->getStyle('A2:I2')->applyFromArray($tableHead);

 $hojaActiva->getColumndimension('A')->setWidth(40);
 $hojaActiva->getColumndimension('B')->setWidth(10);
 $hojaActiva->getColumndimension('C')->setWidth(15);
 $hojaActiva->getColumndimension('D')->setWidth(15);
 $hojaActiva->getColumndimension('E')->setWidth(10);
 $hojaActiva->getColumndimension('F')->setWidth(20);
 $hojaActiva->getColumndimension('G')->setWidth(20);
 $hojaActiva->getColumndimension('H')->setWidth(10);
 $hojaActiva->getColumndimension('I')->setWidth(20);

 
 $hojaActiva->setCellValue('A2', 'Seed');
 $hojaActiva->setCellValue('B2', 'Sows');
 $hojaActiva->setCellValue('C2', 'First Delivery');
 $hojaActiva->setCellValue('D2', 'Last Delivery');
 $hojaActiva->setCellValue('E2', 'Frequency');
 $hojaActiva->setCellValue('F2', 'Expected Plants');
 $hojaActiva->setCellValue('G2', 'Delivered Plants');
 $hojaActiva->setCellValue('H2', 'Germ');
 $hojaActiva->setCellValue('I2', 'Total Seed Needed');

 $FILA = 3;

 if(count($SOWS) > 0) {
  foreach ($SOWS as $SOW) {
    if($SOW['EXPECTED_PLANTS_2'] == NULL){
      $expected_plants   = utf8_encode($SOW['EXPECTED_PLANTS']);
      $germ              = utf8_encode($SOW['GERM']);
      $total_seed_needed = utf8_encode($SOW['TOTAL_SEED_NEEDED']);
    } else {
      $expected_plants   = utf8_encode($SOW['EXPECTED_PLANTS_2']);
      $germ              = utf8_encode($SOW['GERM_2']);
      $total_seed_needed = utf8_encode($SOW['TOTAL_SEED_NEEDED_2']);
    }
    $hojaActiva->setCellValue('A'.$FILA, utf8_encode($SOW['SEED']));
    $hojaActiva->setCellValue('B'.$FILA, utf8_encode($SOW['SOWS']));
    $hojaActiva->setCellValue('C'.$FILA, utf8_encode($SOW['FIRST_FELIVERY']));
    $hojaActiva->setCellValue('D'.$FILA, utf8_encode($SOW['LAST_FELIVERY']));
    $hojaActiva->setCellValue('E'.$FILA, utf8_encode($SOW['FREQUENCY']));
    $hojaActiva->setCellValue('F'.$FILA, $expected_plants);
    $hojaActiva->setCellValue('G'.$FILA, utf8_encode($SOW['DELIVERED_PLANTS']));
    $hojaActiva->setCellValue('H'.$FILA, $germ.'%');
    $hojaActiva->setCellValue('I'.$FILA, $total_seed_needed);


    $spreadsheet->getActiveSheet()->getStyle('I'.$FILA)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $FILA++;
  }
} 

//AutoFilter
$firstRow = 2;
$lastRow  = $FILA - 1;
$spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.':I'.$lastRow);

//Borders
$spreadsheet
    ->getActiveSheet()
    ->getStyle("A".$firstRow.':I'.$lastRow)
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Seeds Summary Report '.date("Y-m-d").'.xls"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');

exit;
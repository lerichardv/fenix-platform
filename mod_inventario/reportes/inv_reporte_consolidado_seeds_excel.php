<?PHP
/*
 * Genera el listado de siembras y transplantes para exportar a excel.
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
 $fecha_inicial 	  = $_GET['x1'];
 $fecha_final 	    = $_GET['x2'];
 $cod_greenhouse	  = $_GET['x3'];
 $cod_proveedor 	  = $_GET['x4'];
 $cod_tipo_semilla  = $_GET['x5'];

 /*INSTANCIAMIENTOS*/
 $DB_REPORTE = new db_rep_inv();
 $SOWS       = $DB_REPORTE->get_resumen_semillas($fecha_inicial, 
                                                  $fecha_final, 
                                                  $cod_greenhouse, 
                                                  $cod_proveedor, 
                                                  $cod_tipo_semilla);

 $spreadsheet = new Spreadsheet();

 $hojaActiva = $spreadsheet->getActiveSheet();
 $hojaActiva->setTitle("Untreated-Organic Seed Report");
 
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
 $spreadsheet->getActiveSheet()->setCellValue('A1','Lady Moon Farms - Untreated-Organic Seed Report from '.$fecha_inicial.' to '.$fecha_final);
 $spreadsheet->getActiveSheet()->mergeCells('A1:F1');
 $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($tableTitle);
 $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 $spreadsheet->getActiveSheet()->getStyle('A2:F2')->applyFromArray($tableHead);

 $hojaActiva->getColumndimension('A')->setWidth(30);
 $hojaActiva->getColumndimension('B')->setWidth(30);
 $hojaActiva->getColumndimension('C')->setWidth(40);
 $hojaActiva->getColumndimension('D')->setWidth(40);
 $hojaActiva->getColumndimension('E')->setWidth(15);
 $hojaActiva->getColumndimension('F')->setWidth(15);
 
 $hojaActiva->setCellValue('A2', 'Greenhouse');
 $hojaActiva->setCellValue('B2', 'Vendor');
 $hojaActiva->setCellValue('C2', 'Seed');
 $hojaActiva->setCellValue('D2', 'OG Supply');
 $hojaActiva->setCellValue('E2', 'Quantity');
 $hojaActiva->setCellValue('F2', 'Type');

 $FILA = 3;

 if(count($SOWS) > 0) {
  $correlativo = 1;
  foreach ($SOWS as $SOW) {


    $hojaActiva->setCellValue('A'.$FILA, utf8_encode($SOW['GREENHOUSE']));
    $hojaActiva->setCellValue('B'.$FILA, utf8_encode($SOW['VENDOR']));
    $hojaActiva->setCellValue('C'.$FILA, utf8_encode($SOW['SEED']));
    $hojaActiva->setCellValue('D'.$FILA, utf8_encode($SOW['OGSUPPLY']));
    $hojaActiva->setCellValue('E'.$FILA, utf8_encode($SOW['CANTIDAD']));
    $hojaActiva->setCellValue('F'.$FILA, utf8_encode($SOW['TIPO_SEED']));
    $FILA++;
  }
} 

//AutoFilter
$firstRow = 2;
$lastRow  = $FILA - 1;
$spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.':F'.$lastRow);

//Borders
$spreadsheet
    ->getActiveSheet()
    ->getStyle("A".$firstRow.':F'.$lastRow)
    ->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Untreated-Organic Seed Report '.date("Y-m-d").'.xls"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');

exit;
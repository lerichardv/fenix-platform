<?PHP
/*
 * Descarga el planting map en excel.
 * @author      Dan Urquía
 * @date        2025-02-06
*/
 

 session_start();
 if(!isset($_SESSION['cod_usuario'])){
   header('Location: index.php');
 }

 require '../../vendor/autoload.php';
  //mod_farms/reportes/farm_map_granjas_plantacion_excel.php?x1=2024-02-01&x2=2025-03-14&x3=2&x4=1&x5=1,2,3,4,5,6,7,8,9,10,11,12,13&x6=1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,343,344,345,346,347,348,349,350,351,352,353,354,355,356,357,358,359,360,361,362,363,364,365,366,367,368,369,370,371,372,373,374,375
 use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
 use PhpOffice\PhpSpreadsheet\Style\{Alignment, Fill, Border, Color};

 /*CONEXION CON BASE DE DATOS*/
 include_once("../../libs/db_classes/db_mysql_conn.php");
 include_once("../../libs/db_classes/db_farms.php");
 $fecha_inicial 	      = $_GET['x1'];
 $fecha_final 	        = $_GET['x2'];
 $codigosEstados 	      = $_GET['x3'];
 $codigosGranjas 	      = $_GET['x4'];
 $codigosCampos   	    = $_GET['x5'];
 $codigoBloquesUsados 	= $_GET['x6'];

 /*INSTANCIAMIENTOS*/
 $DB_FARM = new db_farms();
 $MAPA = $DB_FARM->get_mapa_farm_planting($fecha_inicial,
                                         $fecha_final,
                                         $codigosEstados,
                                         $codigosGranjas,
                                         $codigosCampos,
                                         $codigoBloquesUsados);
 $spreadsheet = new Spreadsheet();

 //var_dump($MAPA);

 $hojaActiva = $spreadsheet->getActiveSheet();
 $hojaActiva->setTitle("Farm Planting Map Report");

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
 $spreadsheet->getActiveSheet()->setCellValue('A1','Lady Moon Farms - Farm Planting Map Report from '.$fecha_inicial.' to '.$fecha_final);
 $spreadsheet->getActiveSheet()->mergeCells('A1:K1');
 $spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($tableTitle);
 $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
 $spreadsheet->getActiveSheet()->getStyle('A2:K2')->applyFromArray($tableHead);

 $hojaActiva->getColumndimension('A')->setWidth(15);
 $hojaActiva->getColumndimension('B')->setWidth(10);
 $hojaActiva->getColumndimension('C')->setWidth(10);
 $hojaActiva->getColumndimension('D')->setWidth(10);
 $hojaActiva->getColumndimension('E')->setWidth(15);
 $hojaActiva->getColumndimension('F')->setWidth(15);
 $hojaActiva->getColumndimension('G')->setWidth(40);
 $hojaActiva->getColumndimension('H')->setWidth(10);
 $hojaActiva->getColumndimension('I')->setWidth(6);
 $hojaActiva->getColumndimension('J')->setWidth(15);
 $hojaActiva->getColumndimension('K')->setWidth(10);

 $hojaActiva->setCellValue('A2', 'Farm');
 $hojaActiva->setCellValue('B2', 'Field');
 $hojaActiva->setCellValue('C2', 'Block');
 $hojaActiva->setCellValue('D2', 'Initial Acres');
 $hojaActiva->setCellValue('E2', 'Planting Date');
 $hojaActiva->setCellValue('F2', 'Commodity');
 $hojaActiva->setCellValue('G2', 'Crop Name');
 $hojaActiva->setCellValue('H2', 'Used Acres');
 $hojaActiva->setCellValue('I2', 'Age');
 $hojaActiva->setCellValue('J2', 'Terminated Date');
 $hojaActiva->setCellValue('K2', 'Status');

 $FILA = 3;

 if(count($MAPA) > 0) {
  foreach ($MAPA as $MAP) {
    $hojaActiva->setCellValue('A'.$FILA, utf8_encode($MAP['FARM']));
    $hojaActiva->setCellValue('B'.$FILA, utf8_encode($MAP['FIELD']));
    $hojaActiva->setCellValue('C'.$FILA, utf8_encode($MAP['BLOQUE']));
    $hojaActiva->setCellValue('D'.$FILA, utf8_encode($MAP['INITIAL_ACRE']));
    $hojaActiva->setCellValue('E'.$FILA, utf8_encode($MAP['PLANTING_DATE']));
    $hojaActiva->setCellValue('F'.$FILA, utf8_encode($MAP['COMMODITY']));
    $hojaActiva->setCellValue('G'.$FILA, utf8_encode($MAP['SEED']));
    $hojaActiva->setCellValue('H'.$FILA, utf8_encode($MAP['USED_ACRES']));
    $hojaActiva->setCellValue('I'.$FILA, utf8_encode($MAP['AGE']));
    $hojaActiva->setCellValue('J'.$FILA, utf8_encode($MAP['TERMINATED_DATE']));
    $hojaActiva->setCellValue('K'.$FILA, $MAP['COMPLETE']);

    $spreadsheet->getActiveSheet()->getStyle('K'.$FILA)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $FILA++; 
  }
} 

//AutoFilter
$firstRow = 2;
$lastRow  = $FILA - 1;
$spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.':K'.$lastRow);

//Borders
$spreadsheet
    ->getActiveSheet()
    ->getStyle("A".$firstRow.':K'.$lastRow)
    ->getBorders()
    ->getAllBorders() 
    ->setBorderStyle(Border::BORDER_THIN);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Farm Planting Map Report '.date("Y-m-d").'.xls"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');

exit;

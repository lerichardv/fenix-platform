<?PHP
/*
 * Genera el listado de siembras y transplantes para exportar a excel.
 * @author      Dan Urquía
 * @date        2023-11-01
 */
ini_set('display_errors', 1); //Permite mostrar los errores en la pantalla del navagador (usario)
error_reporting(E_ERROR); //Permite capturar los errores que se produzcan en tiempo de ejecución
session_start();
if (!isset($_SESSION['cod_usuario'])) {
  //  header('Location: index.php');
}

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Fill, Border, Color};

/*CONEXION CON BASE DE DATOS*/

include_once("../../libs/db_classes/db_mysql_conn.php");
include_once("../../libs/db_classes/db_reportes_inventario.php");
$fecha_inicial   = $_GET['x1'];
$fecha_final     = $_GET['x2'];
$estados         = $_GET['x3'];

/*INSTANCIAMIENTOS*/
$DB_REPORTE = new db_rep_inv();
$SOWS       = $DB_REPORTE->get_siembras_transplantes($fecha_inicial, $fecha_final, $estados);

$spreadsheet = new Spreadsheet();

$hojaActiva = $spreadsheet->getActiveSheet();
$hojaActiva->setTitle("Sows and Transplants Report");

$tableTitle = [
  'font' => [
    'color' => [
      'rgb' => 'FFFFFF'
    ],
    'bold' => true,
    'size' => 15
  ],
  'fill' => [
    'fillType' => Fill::FILL_SOLID,
    'startColor' => [
      'rgb' => '9307f5'
    ]
  ],
];

$tableHead = [
  'font' => [
    'color' => [
      'rgb' => 'FFFFFF'
    ],
  ],
  'fill' => [
    'fillType' => Fill::FILL_SOLID,
    'startColor' => [
      'rgb' => '5703a6'
    ]
  ],
];

//Header and title
$spreadsheet->getActiveSheet()->setCellValue('A1', 'Lady Moon Farms - Sows and Transplants Report from ' . $fecha_inicial . ' to ' . $fecha_final);
$spreadsheet->getActiveSheet()->mergeCells('A1:R1');
$spreadsheet->getActiveSheet()->getStyle('A1:R1')->applyFromArray($tableTitle);
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A2:R2')->applyFromArray($tableHead);

$hojaActiva->getColumndimension('A')->setWidth(15);
$hojaActiva->getColumndimension('B')->setWidth(8);
$hojaActiva->getColumndimension('C')->setWidth(15);
$hojaActiva->getColumndimension('D')->setWidth(40);
$hojaActiva->getColumndimension('E')->setWidth(15);
$hojaActiva->getColumndimension('F')->setWidth(5);
$hojaActiva->getColumndimension('G')->setWidth(12);
$hojaActiva->getColumndimension('H')->setWidth(15);
$hojaActiva->getColumndimension('I')->setWidth(10);
$hojaActiva->getColumndimension('J')->setWidth(15);
$hojaActiva->getColumndimension('K')->setWidth(15);
$hojaActiva->getColumndimension('L')->setWidth(15);
$hojaActiva->getColumndimension('M')->setWidth(20);
$hojaActiva->getColumndimension('N')->setWidth(15);
$hojaActiva->getColumndimension('O')->setWidth(12);
$hojaActiva->getColumndimension('P')->setWidth(15);
$hojaActiva->getColumndimension('Q')->setWidth(15);
$hojaActiva->getColumndimension('R')->setWidth(10);

$hojaActiva->setCellValue('A2', 'Order Number');
$hojaActiva->setCellValue('B2', 'Item');
$hojaActiva->setCellValue('C2', 'Sow Group');
$hojaActiva->setCellValue('D2', 'Seed');
$hojaActiva->setCellValue('E2', 'Greenhouse');
$hojaActiva->setCellValue('F2', 'State');
$hojaActiva->setCellValue('G2', 'Plants Ordered');
$hojaActiva->setCellValue('H2', 'Overseed');
$hojaActiva->setCellValue('I2', 'Expected');
$hojaActiva->setCellValue('J2', 'Expected sow');
$hojaActiva->setCellValue('K2', 'Expected delivery');
$hojaActiva->setCellValue('L2', 'Transplant ticket');
$hojaActiva->setCellValue('M2', 'Location');
$hojaActiva->setCellValue('N2', 'Transplant delivery');
$hojaActiva->setCellValue('O2', 'Germination');
$hojaActiva->setCellValue('P2', 'Delivered Plants');
$hojaActiva->setCellValue('Q2', 'Balance');
$hojaActiva->setCellValue('R2', 'Completed');

$FILA = 3;

if (count($SOWS) > 0) {
  $correlativo = 1;
  foreach ($SOWS as $SOW) {
    $ticket                   = ($SOW['ticket_tras'] == NULL) ? '-' : utf8_encode($SOW['ticket_tras']);
    $location                 = ($SOW['location'] == NULL) ? '-' : utf8_encode($SOW['location']);
    $fecha_entrega_trasplante = ($SOW['fecha_entrega_trasplante'] == NULL) ? '-' : utf8_encode($SOW['fecha_entrega_trasplante']);
    $germ                     = ($SOW['germ'] == NULL) ? '-' : utf8_encode($SOW['germ']) . '%';
    $cantidad_plantas         = ($SOW['cantidad_plantas'] == NULL) ? '-' : utf8_encode($SOW['cantidad_plantas']);

    // $datos_cantidades =  $DB_REPORTE->get_cantidad_plantacion($ticket, $SOW['cod_plantacion']);
    // if (isset($datos_cantidades[0]['total_plantas'])) {
    //   if ($datos_cantidades[0]['cantidad_plantaciones'] > 1) {
    //     $datos_suma_cantidades_plantas_por_plantacion =  $DB_REPORTE->get_cantidad_sumada_semillas_plantacion($SOW['cod_plantacion']);
    //     $cantidad_plantas = $datos_suma_cantidades_plantas_por_plantacion[0]['cantidad_plantas'];
    //   } else {
    //     $cantidad_plantas = $datos_cantidades[0]['total_plantas'];
    //   }
    // }
    if ($SOW['cantidad_2'] == NULL) {
      // Caso: no se tiene un trasplante
      $cantidad    = $SOW['cantidad']; //Cantidad ordenada en las plantación
      $exp_plants  = $SOW['exp_plants'];
      $balance     = ($SOW['balance'] == NULL) ? '-' : utf8_encode($SOW['balance']);
    } else {
      $cantidad    = $SOW['cantidad'];
      $exp_plants  = $SOW['exp_plants_2'];
      $exp_plants  = $SOW['exp_plants'];
      $balance     = ($SOW['balance_2'] == NULL) ? '-' : utf8_encode($SOW['balance_2']);
    }
    // $cantidad_plantas = $cantidad_plantas;
    $cantidad_plantas_sin_formato = str_replace(",", "", $cantidad_plantas);
    $exp_plants_sin_formato       = str_replace(",", "", $exp_plants);


    if ($cantidad_plantas_sin_formato == '-') {
      $balance  = '-';
      $germ     = '-';
    } else {
      $balance  = $cantidad_plantas_sin_formato - $exp_plants_sin_formato;
      $balance  = number_format($balance);
      $germ     = ($cantidad_plantas_sin_formato / $exp_plants_sin_formato)  * 100;
      $germ     = round($germ, 2) . '%';
    }


    switch ($SOW['completado']) {
      case "0":
        $completado = 'No';
        break;
      case "1":
        $completado = 'Yes';
        break;
      default:
        $completado = '-';
    }
    $hojaActiva->setCellValue('A' . $FILA, utf8_encode($SOW['numero_orden']));
    $hojaActiva->setCellValue('B' . $FILA, utf8_encode($SOW['item']));
    $hojaActiva->setCellValue('C' . $FILA, utf8_encode($SOW['sow_group']));
    $hojaActiva->setCellValue('D' . $FILA, utf8_encode($SOW['nombre_semilla']));
    $hojaActiva->setCellValue('E' . $FILA, utf8_encode($SOW['nombre_empresa']));
    $hojaActiva->setCellValue('F' . $FILA, utf8_encode($SOW['estado']));
    $hojaActiva->setCellValue('G' . $FILA, $cantidad);
    $hojaActiva->setCellValue('H' . $FILA, utf8_encode($SOW['overseed']) . '%');
    $hojaActiva->setCellValue('I' . $FILA, $exp_plants);
    $hojaActiva->setCellValue('J' . $FILA, utf8_encode($SOW['expected_sow']));
    $hojaActiva->setCellValue('K' . $FILA, utf8_encode($SOW['expected_delivery']));
    $hojaActiva->setCellValue('L' . $FILA, $ticket);
    $hojaActiva->setCellValue('M' . $FILA, $location);
    $hojaActiva->setCellValue('N' . $FILA, $fecha_entrega_trasplante);
    $hojaActiva->setCellValue('O' . $FILA, $germ);
    $hojaActiva->setCellValue('P' . $FILA, $cantidad_plantas);
    $hojaActiva->setCellValue('Q' . $FILA, $balance);
    $hojaActiva->setCellValue('R' . $FILA, $completado);

    $spreadsheet->getActiveSheet()->getStyle('G' . $FILA)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    $FILA++;
  }
}

//AutoFilter
$firstRow = 2;
$lastRow  = $FILA - 1;
$spreadsheet->getActiveSheet()->setAutoFilter("A" . $firstRow . ':R' . $lastRow);

//Borders
$spreadsheet
  ->getActiveSheet()
  ->getStyle("A" . $firstRow . ':R' . $lastRow)
  ->getBorders()
  ->getAllBorders()
  ->setBorderStyle(Border::BORDER_THIN);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Sows and Transplants Report ' . date("Y-m-d") . '.xls"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');

exit;

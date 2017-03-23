<?php

require_once '../Classes/PHPExcel.php';
include '../db.php';

$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'FECHA')
            ->setCellValue('B1', 'HORA')
            ->setCellValue('C1', 'CLIENTE')
            ->setCellValue('D1', 'TOTAL');

if ( isset($_GET['f']) ){
				$buscar = mysql_real_escape_string($_GET['f']);
				$date = explode(" - ", $buscar);
				
				$query = mysql_query("SELECT 
					ventas.idventas,
					ventas.fecha,
					ventas.hora,
					clientes.nombre
					FROM ventas 
					LEFT JOIN clientes ON clientes.idclientes=ventas.idcliente
					WHERE ventas.fecha 
					GROUP BY ventas.idventas
					ORDER BY ventas.idventas DESC");

				$query = mysql_query("SELECT 
					*
					FROM ventas_pagos 
					JOIN ventas ON ventas.idventas=ventas_pagos.idventa
					JOIN clientes ON clientes.idclientes=ventas.idcliente
					WHERE ventas_pagos.fecha BETWEEN '".$date[0]."' AND '".$date[1]."'
					ORDER BY ventas_pagos.idpagos DESC") or die( mysql_error() );
			} else {
				$buscar = date("Y-m-d")." - ".date("Y-m-d");
				$query = mysql_query("SELECT 
					*
					FROM ventas_pagos 
					JOIN ventas ON ventas.idventas=ventas_pagos.idventa
					JOIN clientes ON clientes.idclientes=ventas.idcliente
					WHERE ventas_pagos.fecha=CURDATE()
					ORDER BY ventas_pagos.idpagos DESC") or die( mysql_error() );
			}

$total = 0;
$row = 2;
while($q = mysql_fetch_object($query)){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, $q->fecha)
            ->setCellValue('B'.$row, $q->hora)
            ->setCellValue('C'.$row, $q->nombre)
            ->setCellValue('D'.$row, $q->cantidad);

    $total += $q->cantidad;
    $row++;
}

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$row, "Total : ")
            ->setCellValue('D'.$row, $total);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Ingresos');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Ingresos'.date("Y-m-d").'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
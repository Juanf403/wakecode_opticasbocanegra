<?php

require_once '../Classes/PHPExcel.php';
include '../db.php';

$objPHPExcel = new PHPExcel();



$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '#')
            ->setCellValue('B1', 'FECHA')
            ->setCellValue('C1', 'HORA')
            ->setCellValue('D1', 'CLIENTE')
            ->setCellValue('E1', 'TOTAL')
            ->setCellValue('F1', 'PAGADO')
            ->setCellValue('G1', 'ESTATUS');

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
					WHERE ventas.fecha BETWEEN '".$date[0]."' AND '".$date[1]."'
					GROUP BY ventas.idventas
					ORDER BY ventas.idventas DESC");
			} else {
				$buscar = date("Y-m-d")." - ".date("Y-m-d");
				$query = mysql_query("SELECT 
					ventas.idventas,
					ventas.fecha,
					ventas.hora,
					clientes.nombre
					FROM ventas 
					LEFT JOIN clientes ON clientes.idclientes=ventas.idcliente
					WHERE ventas.fecha=CURDATE()
					GROUP BY ventas.idventas
					ORDER BY ventas.idventas DESC");
			}

$total = 0;
$suma = 0;
$row = 2;
	while($q = mysql_fetch_object($query)){
					# sacamos el total 
					$asd = mysql_fetch_object(mysql_query("SELECT SUM(total) total FROM ventas_articulos WHERE idventa='".$q->idventas."'"));
					# sacamos los pagos
					$asd2 = mysql_fetch_object(mysql_query("SELECT SUM(cantidad) cantidad FROM ventas_pagos WHERE idventa='".$q->idventas."'"));

					if ( @$_GET['estado'] == 1){
						if ($asd2->cantidad >= $asd->total){
							$suma += $asd2->cantidad;
							$total += $asd->total;

				$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$row, $q->idventas)
	            ->setCellValue('B'.$row, $q->fecha)
	            ->setCellValue('C'.$row, $q->hora)
	            ->setCellValue('D'.$row, $q->nombre)
	            ->setCellValue('E'.$row, $asd->total)
	            ->setCellValue('F'.$row, $asd2->cantidad)
	            ->setCellValue('G'.$row, "liquidado");

	    			
	    			$row++;
						}
					} else {
						if ($asd2->cantidad < $asd->total){
							$suma += $asd2->cantidad;
							$total += $asd->total;
							
				$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$row, $q->idventas)
	            ->setCellValue('B'.$row, $q->fecha)
	            ->setCellValue('C'.$row, $q->hora)
	            ->setCellValue('D'.$row, $q->nombre)
	            ->setCellValue('E'.$row, $asd->total)
	            ->setCellValue('F'.$row, $asd2->cantidad)
	            ->setCellValue('G'.$row, "Pendiente");


	    			
	   				 $row++;
						}
					}
	}

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$row, "Total Venta: ")
            ->setCellValue('E'.$row, "$ ".$total." Pesos");

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G'.$row, "Total Pagado: ")
            ->setCellValue('H'.$row, "$ ".$suma." Pesos");

// Rename worksheet
            if ( @$_GET['estado'] == 1){
$objPHPExcel->getActiveSheet()->setTitle('Reporte Liquidados');
}else{
	$objPHPExcel->getActiveSheet()->setTitle('Reporte Pendientes');
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
            if ( @$_GET['estado'] == 1){
header('Content-Disposition: attachment;filename="Reporte Liquidados '.date("Y-m-d").'.xls"');
}else{
header('Content-Disposition: attachment;filename="Reporte Pendientes '.date("Y-m-d").'.xls"');
}

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
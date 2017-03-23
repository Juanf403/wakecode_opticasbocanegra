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

$user = mysql_real_escape_string($_GET['vendedor']);
$users   = mysql_query("SELECT * FROM usuarios WHERE idusuarios=".$user." ORDER BY nombre ASC");
$u = mysql_fetch_object($users);

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '#')
			->setCellValue('B1', 'FECHA')
            ->setCellValue('C1', 'HORA')
            ->setCellValue('D1', 'CLIENTE')
            ->setCellValue('E1', 'TOTAL')
            ->setCellValue('F1', 'PAGADO')
            ->setCellValue('G1', 'ESTATUS');

				$buscar = mysql_real_escape_string($_GET['daterange']);
				$date = explode(" - ", $buscar);
				$user = mysql_real_escape_string($_GET['vendedor']);
				$query = mysql_query("SELECT 
					ventas.idventas,
					ventas.fecha,
					ventas.hora,
					ventas.descuento,
					clientes.nombre,
					(SELECT SUM(total) FROM ventas_articulos WHERE idventa=ventas.idventas) as nuevoTotal,
					(SELECT SUM(cantidad) FROM ventas_pagos WHERE idventa=ventas.idventas) as cantidad
					FROM ventas 
					JOIN clientes ON clientes.idclientes=ventas.idcliente
					WHERE ventas.fecha BETWEEN '".$date[0]."' AND '".$date[1]."' AND ventas.idusuario='".$user."'
					ORDER BY ventas.idventas DESC") or die(mysql_query());
				

				
				
$suma = 0;
$pagado = 0;
$total = 0;
$row = 2;
while($q = mysql_fetch_object($query)){
					$descuento = ($q->descuento / 100) * $q->nuevoTotal;
					$total = ($q->nuevoTotal - $descuento);

					

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, $q->idventas)
            ->setCellValue('B'.$row, $q->fecha)
            ->setCellValue('C'.$row, $q->hora)
            ->setCellValue('D'.$row, $q->nombre)
            ->setCellValue('E'.$row, $total)
            ->setCellValue('F'.$row, $q->cantidad);
            
            if ($q->cantidad >= $total){
            	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G'.$row,"Liquidado");
        }else{
        	$objPHPExcel->setActiveSheetIndex(0)
        	->setCellValue('G'.$row, "Pendiente");
        }

    				$suma += $q->nuevoTotal;
					$pagado += $q->cantidad;
    $row++;
}

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$row, "Total Ventas: ")
            ->setCellValue('E'.$row, "$ ".$suma." Pesos");

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('G'.$row, "Total Ingresos: ")
            ->setCellValue('H'.$row, "$ ".$pagado." Pesos");
            $row = $row + 2;

            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$row, "Vendedor: ")
            ->setCellValue('E'.$row, $u->nombre);

// Rename worksheet

$objPHPExcel->getActiveSheet()->setTitle('Reporte vendedor');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
$users   = mysql_query("SELECT * FROM usuarios WHERE idusuarios=".$user." ORDER BY nombre ASC");
$u = mysql_fetch_object($users);
header('Content-Disposition: attachment;filename="Reporte vendedor '.$u->nombre." ".date("Y-m-d").'.xls"');
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
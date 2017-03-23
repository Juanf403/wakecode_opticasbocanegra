<?php

require_once '../Classes/PHPExcel.php';
include '../db.php';

$objPHPExcel = new PHPExcel();

$art = mysql_real_escape_string($_GET['Art']); 

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'ARTICULO')
            ->setCellValue('B1', 'DESCRIPCION')
            ->setCellValue('C1', 'OBSERVACIONES')
            ->setCellValue('D1', 'PRECIO')
            ->setCellValue('E1', 'STOCK');

$query  = mysql_query("SELECT * FROM articulos JOIN categorias ON categorias.idcategorias=articulos.idcategoria WHERE articulos.articulo = '".$art."' ORDER BY articulo ASC") or die(mysql_error());

$total = 0;
$row = 2;
while($q = mysql_fetch_object($query)){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, $q->articulo)
            ->setCellValue('B'.$row, $q->descripcion)
            ->setCellValue('C'.$row, $q->observaciones)
            ->setCellValue('D'.$row, $q->precio)
            ->setCellValue('E'.$row, $q->stock);

    $total += $q->stock;
    $row++;
}

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'.$row, "Total de Articulos: ")
            ->setCellValue('E'.$row, $total);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Inventario');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reportes_'.$art.'_'.date("Y-m-d").'.xls"');
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
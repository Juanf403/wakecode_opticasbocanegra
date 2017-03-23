<?php

require_once '../Classes/PHPExcel.php';
include '../db.php';

$objPHPExcel = new PHPExcel();

$id = mysql_real_escape_string($_GET['id']);
$cat = mysql_real_escape_string($_GET['cat']);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'CATEGORIA')
            ->setCellValue('B1', 'ARTICULO')
            ->setCellValue('C1', 'DESCRIPCION')
            ->setCellValue('D1', 'STOCK');

$query  = mysql_query("SELECT 
			SUM(articulos.stock) as stock2,
			articulos.*,
			categorias.*
		FROM 
			articulos 
		JOIN categorias ON categorias.idcategorias=articulos.idcategoria
		WHERE 
			articulos.idcategoria='".$id."' GROUP BY articulos.articulo") or die(mysql_error());

$total = 0;
$row = 2;
while($q = mysql_fetch_object($query)){
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row, $q->categoria)
            ->setCellValue('B'.$row, $q->articulo)
            ->setCellValue('C'.$row, $q->descripcion)
            ->setCellValue('D'.$row, $q->stock2);

    $total += $q->stock2;
    $row++;
}

	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'.$row, "Total de Articulos: ")
            ->setCellValue('D'.$row, $total);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Inventario');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Reportes_'.$cat.'_'.date("Y-m-d").'.xls"');
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
<?php
if (!isset($_GET['sucursal'])){
	header("Location: iniciarsesion.php");
}else{
	header("Location: sucursal2/index.php");
}


/*$sucursal = mysql_real_escape_string($_GET['sucursal']);
 echo $sucursal;
		if($sucursal = 1 ){
			header("refresh:5; iniciarsesion.php");
		}
		if($sucursal = 2 ){
			header("refresh:5; iniciarsesion2.php");
		}*/
?>
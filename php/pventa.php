<section class="panel panel-default pos-rlt clearfix">

	<header class="panel-heading"> <i class="fa fa-shopping-cart"></i> Ventas</header>
	
	<div class="row wrapper">
		<div class="col-xs-12 col-md-6 m-b-xs">
			<a href="admin.php?m=pventaAgregar" class="btn btn-sm btn-success m-r"><i class="fa fa-plus"></i> Nueva Orden</a>
		</div>
		<div class="col-xs-12 col-md-6 m-b-xs">
			<form action="" id="buscarCliente" method="get">
				<div class="input-group">
					<input type="hidden" name="m" value="pventa">
					<input type="text" class="input-sm form-control" name="buscar" placeholder="Buscar por nombre o folio">
					<span class="input-group-btn"> <button class="btn btn-sm btn-default" id="buscar" type="submit"> <i class="fa fa-search"></i> </button> </span>
				</div>
			</form>
		</div>
	</div>



<?php
if ( isset($_POST['idventa']) ){
	$idventa 	= mysql_real_escape_string($_POST['idventa']);
	$cantidad 	= mysql_real_escape_string($_POST['cantidad']);
	$metodo 	= mysql_real_escape_string($_POST['metodo']);
	$comentario = mysql_real_escape_string($_POST['comentario']);
	$fecha 	  	= date("Y-m-d");
	$hora 	  	= date("H:m:s");

	mysql_query("INSERT INTO ventas_pagos SET idventa='".$idventa."',fecha='".$fecha."',hora='".$hora."',cantidad='".$cantidad."',comentario='".$comentario."',metodo='".$metodo."'");

	echo '<div class="col-md-12">
		<div class="alert alert-success">
			<strong> <i class="fa fa-check"></i> Pago agregado correctamente.</strong>
		</div>
	</div>';
}
?>

	<div class="table-responsive">
		<table class="table table-striped b-t b-light">
			<thead>
				<tr>
					<th width="80" class="text-center"> # </th>
					<th width="120"> Fecha </th>
					<th width="80"> Hora </th>
					<th>Cliente</th>
					<th width="140" class="text-right">Total</th>
					<th width="140" class="text-right">Pagado</th>
					<th width="140" class="text-center">Estatus</th>
					<th width="150"> </th>
				</tr>

			<tbody>

<?php
			if ( isset($_GET['borrar']) ){
				# CUANDO SE BORRA LA VENTA SE SUMA LA CANTIDAD DE ARTICULOS
				$borrar = mysql_real_escape_string($_GET['borrar']);

				mysql_query("DELETE FROM ventas WHERE idventas='".$borrar."'");
				mysql_query("DELETE FROM ventas_pagos WHERE idventa='".$borrar."'");

				$query = mysql_query("SELECT * FROM ventas_articulos WHERE idventa='".$borrar."'");
				while($q = mysql_fetch_object($query)){
					mysql_query("UPDATE articulos SET stock=stock+".$q->cantidad." WHERE idarticulos='".$q->idarticulo."'");
				}
				mysql_query("DELETE FROM ventas_articulos WHERE idventa='".$borrar."'");
			}

			if ( isset($_GET['buscar']) ){
				$buscar = mysql_real_escape_string($_GET['buscar']);

				$query = "SELECT 
					ventas.idventas,
					ventas.fecha,
					ventas.hora,
					ventas.descuento,
					clientes.nombre
					FROM ventas 
					LEFT JOIN clientes ON clientes.idclientes=ventas.idcliente
					WHERE ventas.idventas LIKE '%".$buscar."%' OR clientes.nombre LIKE '%".$buscar."%'
					ORDER BY ventas.idventas DESC";
				$url = "admin.php?m=pventa&buscar=".$buscar;
			} else {
				$query = "SELECT 
					ventas.idventas,
					ventas.fecha,
					ventas.hora,
					ventas.descuento,
					ventas.venta,
					clientes.nombre
					FROM ventas 
					LEFT JOIN clientes ON clientes.idclientes=ventas.idcliente
					GROUP BY ventas.idventas
					ORDER BY ventas.idventas DESC";
				$url = "admin.php?m=pventa";
				
			}

			

##### PAGINADOR #####
$rows_per_page = 30;

if(isset($_GET['pag']))
	$page = mysql_real_escape_string($_GET['pag']);
else if (@$_GET['pag'] == "0")
	$page = 1;
else 
	$page = 1;

$num_rows 		= mysql_num_rows(mysql_query($query));
$lastpage		= ceil($num_rows / $rows_per_page);    		
$page     = (int)$page;
if($page > $lastpage){
	$page = $lastpage;
}
if($page < 1){
	$page = 1;
}
$limit 		= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;
$query  .=" $limit";

$query = mysql_query($query) or die(mysql_error());
##### PAGINADOR #####

			while($q = mysql_fetch_object($query)){

				# sacamos el total 
				$asd = mysql_fetch_object(mysql_query("SELECT SUM(total) total FROM ventas_articulos WHERE idventa='".$q->idventas."'"));
				# sacamos los pagos
				$asd2 = mysql_fetch_object(mysql_query("SELECT SUM(cantidad) cantidad FROM ventas_pagos WHERE idventa='".$q->idventas."'"));

				$descuento = ($q->descuento / 100) * $asd->total;
				$total = ($asd->total - $descuento);
				
				echo '<tr>
					<td class="text-center">'.$q->idventas.'</td>
					<td>'.$q->fecha.'</td>
					<td>'.$q->hora.'</td>
					<td>'.$q->nombre.'</td>
					<td class="text-right">$ '.$total.' pesos </td>
					<td class="text-right">$ '.$asd2->cantidad.' pesos</td>
					<td class="text-center">';

					if ($q->venta == "separado"){
						echo '<label class="label label-info"> separado </label>';
					} else {
						if ($asd2->cantidad >= $total){
							echo '<label class="label label-success"> liquidado </label>';
						} else {
							echo '<label class="label label-warning"> pendiente </label>';
						}	
					}
					
						
						
						if ($_SESSION['userPv'] != "3"){
								
					echo '</td>
					<td class="text-right">
						<a href="#" data-id="'.$q->idventas.'" class="agregarPago btn btn-sm btn-success"> <i class="fa fa-usd"></i> </a> &nbsp;
						<a href="admin.php?m=pventaEditar&id='.$q->idventas.'" class="btn btn-sm btn-default"> <i class="fa fa-pencil"></i> </a> &nbsp;
						<a href="admin.php?m=pventa&borrar='.$q->idventas.'" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> </a>
					</td>';
						}
				echo '</tr>';
			}
?>
		    </tbody>
			</thead>
		</table>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-sm-12 text-right text-center-xs">
				<ul class="pagination pagination-sm m-t-none m-b-none">
<?php

	if($num_rows != 0){
		$nextpage = $page + 1;
		$prevpage = $page - 1;

		if ($page == 1) {
			echo '<li class="disabled"><a href="#"><i class="fa fa-chevron-left"></i></a></li>';
			
			echo '<li class="active"><a href="">1</a></li>';
			
			for($i= $page+1; $i<= $lastpage ; $i++){
				echo '<li><a href="'.$url.'&pag='.$i.'">'.$i.'</a></li> ';
			}

			if($lastpage >$page ){
				echo '<li><a href="'.$url.'&pag='.$nextpage.'"><i class="fa fa-chevron-right"></i></a></li>';
			}else{	
				echo '<li class="disabled"><a href="#"><i class="fa fa-chevron-right"></i></a></li>';
			}
		} else {
			echo '<li><a href="'.$url.'&pag='.$prevpage.'"><i class="fa fa-chevron-left"></i></a></li>';
			
			for($i= 1; $i<= $lastpage ; $i++){
				if($page == $i){
					echo '<li class="active"><a href="#">'.$i.'</a></li>';
				} else {
					echo '<li><a href="'.$url.'&pag='.$i.'">'.$i.'</a></li> ';
				}
			}
         
			if($lastpage >$page ){
				echo '<li><a href="'.$url.'&pag='.$nextpage.'"><i class="fa fa-chevron-right"></i></a></li>';
			} else {
				echo '<li class="disabled"><a href="#"><i class="fa fa-chevron-right"></i></a></li>';
			}
		}
	}

?>
				</ul>
			</div>
		</div>
	</footer>
</section>

<div class="modal fade" id="modal-pagos">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="m-t-none m-b">Agregar pago</h3>
						<form role="form" action="" method="post">
							<input type="hidden" name="idventa" id="idventa" value="" >
							<div class="form-group">
								<div class="row">
									<label class="col-md-6 control-label"><strong>Metodo de Pago</strong></label>
									<div class="col-md-6">
										<select name="metodo" class="form-control">
											<option>Efectivo</option>
											<option>Tarjeta Debido/Credito</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<label class="col-md-6 control-label"><strong>Cantidad</strong></label>
									<div class="col-md-6"><input type="text" class="form-control" name="cantidad" value="0" ></div>
								</div>
							</div>
							<div class="form-group">
								<label><strong>Comentarios</strong></label>
								<textarea class="form-control" name="comentario" style="height:150px;"></textarea>
							</div>
							<div class="checkbox m-t-lg">
								<a class="btn btn-sm btn-default m-t-n-xs" id="cancelar"> <i class="fa fa-times"></i> <strong>Cancelar</strong></a>
								<button type="submit" class="btn btn-sm pull-right btn-success m-t-n-xs"> <i class="fa fa-usd"></i> <strong>Agregar pago</strong></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
$(function(){

	$(".agregarPago").click(function(){
		$("#idventa").val($(this).data("id"))
		$("#modal-pagos").modal("show");
	});

	$("#cancelar").click(function(){
		$("#modal-pagos").modal("hide");
	});
	

});
</script>
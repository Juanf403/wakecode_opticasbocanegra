<?php
if ( isset($_GET['daterange']) ){
	$buscar = mysql_real_escape_string($_GET['daterange']);
	$date = explode(" - ", $buscar);
} else {
	$buscar = date("Y-m-d")." - ".date("Y-m-d");
}
@$user = mysql_real_escape_string($_GET['vendedor']);
$imprimir = "imprimir/vendedor.php?daterange=".$buscar."&vendedor=".$user;
?>
<section class="panel panel-default pos-rlt clearfix">

	<header class="panel-heading"> <i class="fa fa-usd"></i> Reporte de Ventas por Vendedor</header>
	
	<form id="reportesForm" action="" method="get">
		<div class="row wrapper">
			<div class="col-md-3">
				<input type="hidden" value="vendedor" name="m">
				<div class="input-group m-b">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" id="daterange" class="form-control btn-sm" name="daterange" value="<?php echo $buscar; ?>" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<select class="form-control" name="vendedor">
<?php 
				$users   = mysql_query("SELECT * FROM usuarios ORDER BY nombre ASC");
				while($u = mysql_fetch_object($users)){
					if (@$_GET['vendedor'] == $u->idusuarios)
						echo '<option value="'.$u->idusuarios.'" selected>'.$u->nombre.'</option>';
					else
						echo '<option value="'.$u->idusuarios.'">'.$u->nombre.'</option>';
				}
?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<button class="btn btn-md btn-success"> <i class="fa fa-search"></i> Buscar</button>
				<a href="<?php echo $imprimir; ?>" target="_blank" class="m-l btn btn-md btn-default"><i class="fa fa-print"></i> Imprimir Resultados </a>
			</div>
		</div>
	</form>

<?php
			if ( isset($_GET['daterange']) ){
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
				</tr>
			</thead>
			<tbody>
<?php
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
				while($q = mysql_fetch_object($query) ){

					# sacamos el total 
					#$asd = mysql_fetch_object(mysql_query("SELECT SUM(total) total FROM ventas_articulos WHERE idventa='".$q->idventas."'"));
					# sacamos los pagos
					#$asd2 = mysql_fetch_object(mysql_query("SELECT SUM(cantidad) cantidad FROM ventas_pagos WHERE idventa='".$q->idventas."'"));

					$descuento = ($q->descuento / 100) * $q->nuevoTotal;
					$total = ($q->nuevoTotal - $descuento);
					
					echo '<tr>
						<td class="text-center">'.$q->idventas.'</td>
						<td>'.$q->fecha.'</td>
						<td>'.$q->hora.'</td>
						<td>'.$q->nombre.'</td>
						<td class="text-right">$ '.$total.' pesos </td>
						<td class="text-right">$ '.$q->cantidad.' pesos</td>
						<td class="text-center">';
						if ($q->cantidad >= $total){
							echo '<label class="label label-success"> liquidado</label>';
						} else {
							echo '<label class="label label-warning"> pendiente</label>';
						}
						echo '</td>
					</tr>';

					$suma += $q->nuevoTotal;
					$pagado += $q->cantidad;
				}
?>
			</tbody>
		</table>

	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-sm-12 text-right text-center-xs">
				<strong>Total de ventas: $ <?php echo $suma; ?> pesos | Total de ingresos: $ <?php echo $pagado; ?> pesos </strong></strong>
			</div>
		</div>
	</footer>
<?php
			} else {

				echo "<div class='col-md-12 '><div class='alert alert-warning'>
				<strong><i class='fa fa-warning'></i> Favor de elegir el rango de fechas y el vendedor.</strong>
				</div></div>";
			}
?>
		    
</section>
<script type="text/javascript">
$(function(){
	$('input[name="daterange"]').daterangepicker({
        	format: 'YYYY-MM-DD',
        	locale: {
            	applyLabel: 'Buscar',
        	    cancelLabel: 'Cancelar',
    	        fromLabel: 'De',
	            toLabel: 'A',
            	customRangeLabel: 'Custom',
        	    daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vie','Sa'],
    	        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        	}
    	});
    	$('#daterange').on('apply.daterangepicker', function(ev, picker) {
  			//$("#reportesForm").submit();
		});
});
</script>
<?php
if ( isset($_GET['daterange']) ){
	$buscar = mysql_real_escape_string($_GET['daterange']);
	$date = explode(" - ", $buscar);
} else {
	$buscar = date("Y-m-d")." - ".date("Y-m-d");
	
	
}
$e = mysql_real_escape_string($_GET['estado']);
$imprimir = "imprimir/liquidados.php?f=".$buscar."&estado=".$e;
?>
<section class="panel panel-default pos-rlt clearfix">

	<header class="panel-heading"> <i class="fa fa-bar-chart-o"></i> Reportes</header>
	
	<div class="row wrapper">
		<div class="col-md-3">
			<form id="reportesForm" action="" method="get">
				<input type="hidden" value="reportes" name="m">
				<input type="hidden" value="<?php echo @$_GET['estado']; ?>" name="estado">
				<div class="input-group m-b">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" id="daterange" class="form-control btn-sm" name="daterange" value="<?php echo $buscar; ?>" />
				</div>
			</form>
		</div>
		<div class="col-md-8"><div class="col-md-8"><a href="<?php echo $imprimir; ?>" target="_blank" class="m-l btn btn-md btn-default"><i class="fa fa-print"></i> Imprimir Resultados </a></div></div>
	</div>

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

			<tbody>

<?php
			if ( isset($_GET['daterange']) ){
				$buscar = mysql_real_escape_string($_GET['daterange']);
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


			$suma = 0;
			$total = 0;
			while($q = mysql_fetch_object($query)){
				
				# sacamos el total 
				$asd = mysql_fetch_object(mysql_query("SELECT SUM(total) total FROM ventas_articulos WHERE idventa='".$q->idventas."'"));
				# sacamos los pagos
				$asd2 = mysql_fetch_object(mysql_query("SELECT SUM(cantidad) cantidad FROM ventas_pagos WHERE idventa='".$q->idventas."'"));

				if ( @$_GET['estado'] == 1){
					if ($asd2->cantidad >= $asd->total){
						$suma += $asd2->cantidad;
						$total += $asd->total;
						echo '<tr>
							<td class="text-center">'.$q->idventas.'</td>
							<td>'.$q->fecha.'</td>
							<td>'.$q->hora.'</td>
							<td>'.$q->nombre.'</td>
							<td class="text-right">$ '.$asd->total.' pesos </td>
							<td class="text-right">$ '.$asd2->cantidad.' pesos</td>
							<td class="text-center"><label class="label label-success"> liquidado</label></td>
						</tr>';
					}
				} else {
					if ($asd2->cantidad < $asd->total){
						$suma += $asd2->cantidad;
						$total += $asd->total;
						echo '<tr>
							<td class="text-center">'.$q->idventas.'</td>
							<td>'.$q->fecha.'</td>
							<td>'.$q->hora.'</td>
							<td>'.$q->nombre.'</td>
							<td class="text-right">$ '.$asd->total.' pesos </td>
							<td class="text-right">$ '.$asd2->cantidad.' pesos</td>
							<td class="text-center"><label class="label label-warning"> pendiente</label></td>
						</tr>';
					}
				}
				
			}
?>
		    </tbody>
			</thead>
		</table>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-sm-12 text-right text-center-xs">
				<strong>Total Venta: $ <?php echo $total; ?> pesos</strong> | <strong>Total Pagado: $ <?php echo $suma; ?> pesos</strong>
			</div>
		</div>
	</footer>
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
  			$("#reportesForm").submit();
		});
});
</script>
<?php
if ( isset($_GET['daterange']) ){
	$buscar = mysql_real_escape_string($_GET['daterange']);
	$date = explode(" - ", $buscar);
} else {
	$buscar = date("Y-m-d")." - ".date("Y-m-d");
}
$imprimir = "imprimir/ingresos.php?f=".$buscar;
?>
<section class="panel panel-default pos-rlt clearfix">

	<header class="panel-heading"> <i class="fa fa-usd"></i> Reporte de Ingresos</header>
	
	<div class="row wrapper">
		<div class="col-md-3">
			<form id="reportesForm" action="" method="get">
				<input type="hidden" value="ingresos" name="m">
				<div class="input-group m-b">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" id="daterange" class="form-control btn-sm" name="daterange" value="<?php echo $buscar; ?>" />
				</div>
			</form>
		</div>
		<div class="col-md-8"><a href="<?php echo $imprimir; ?>" target="_blank" class="m-l btn btn-md btn-default"><i class="fa fa-print"></i> Imprimir Resultados </a></div>
	</div>

	<div class="table-responsive">
		<table class="table table-striped b-t b-light">
			<thead>
				<tr>
					<th width="120"> Fecha </th>
					<th width="80"> Hora </th>
					<th>Cliente</th>
					<th width="180" class="text-left">Total</th>
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


			$suma = 0;
			while($q = mysql_fetch_object($query)){
						$suma += $q->cantidad;

						echo '<tr>
							<td>'.$q->fecha.'</td>
							<td>'.$q->hora.'</td>
							<td>'.$q->nombre.'</td>
							<td class="text-right">$ '.$q->cantidad.' pesos</td>
						</tr>';

				
			}
?>
		    </tbody>
			</thead>
		</table>
	</div>
	<footer class="panel-footer">
		<div class="row">
			<div class="col-sm-12 text-right text-center-xs">
				<strong>Total: $ <?php echo $suma; ?> pesos</strong></strong>
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
<section class="panel panel-default pos-rlt clearfix">

	<header class="panel-heading"> <i class="fa fa-warning"></i> Pendientes Liquidar</header>
	
	<div class="row wrapper">
		<div class="col-md-12">
			<a href="admin.php?m=pventa" class="btn btn-sm btn-default"> <i class="fa fa-reply"></i> Regresar a Ventas</a>
		</div>
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

				$query = mysql_query("SELECT 
					SUM(ventas_articulos.total) totalarticulos,
					SUM(ventas_pagos.cantidad) totalpagos,
					ventas.*
					FROM ventas 
					LEFT JOIN ventas_articulos ON ventas_articulos.idventa=ventas.idventas 
					LEFT JOIN ventas_pagos ON ventas_pagos.idventa=ventas.idventas 
					GROUP BY ventas_articulos.idventa,ventas_pagos.idventa 
					ORDER BY ventas.idventas DESC") or die(mysql_Error());

			$suma = 0;
			$total = 0;
			while($q = mysql_fetch_object($query)){
				
				echo "<pre>";
				print_r($q);
				echo "</pre>";
				die;
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
	
</section>
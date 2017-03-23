<div class="row">
	<div class="col-md-7">
		<section class="panel panel-default pos-rlt clearfix">

			<header class="panel-heading"> <i class="fa fa-calendar"></i> Citas en los proximos 7 dias</header>
			
			<div class="table-responsive">
				<table class="table ">
					<tr>
						<th class="text-center">Fecha</th>
						<th class="text-center" width="120">Hora</th>
						<th>Cliente</th>
						<th>Descripcion</th>
					</tr>
<?php
					$query = mysql_query("SELECT *,DATEDIFF(citas.fecha,CURDATE()) as dias FROM citas JOIN clientes ON clientes.idclientes=citas.idcliente WHERE DATEDIFF(citas.fecha,CURDATE()) <= 7 and DATEDIFF(citas.fecha,CURDATE()) >= 0 ORDER BY nombre ASC");
					while($q = mysql_fetch_object($query)){
						echo "<tr>";
						if($q->dias <= 3 and $q->dias >= 0){
							echo "
							<td class='text-center'><label class='label bg-danger'>".$q->fecha."</label></td>";
						}else{
							echo "
							<td class='text-center'>".$q->fecha."</label></td>";
						}
						echo "
							<td class='text-center'>".$q->hora."</label></td>
							<td>".$q->nombre."</td>
							<td>".$q->descripcion."</td>
						<tr>";
					}
?>
				</table>
			</div>
			
		</section>
	</div>
	<?php
	if ($_SESSION['userPv'] != "3"){
		?>
	<div class="col-md-5">
		<section class="panel panel-default pos-rlt clearfix">

			<header class="panel-heading"> <i class="fa fa-warning"></i> Alerta de Stock</header>
			
			<div class="table-responsive">
				<table class="table ">
					<tr>
						<th class="text-center" width="120">Disponibles</th>
						<th>Articulo</th>
					</tr>
<?php
					$query = mysql_query("SELECT * FROM articulos WHERE stock<alerta AND alerta!=0 ORDER BY articulo ASC");
					while($q = mysql_fetch_object($query)){
						echo "<tr>
							<td class='text-center'><label class='label bg-danger'>".$q->stock."</label></td>
							<td>".$q->articulo." ".$q->descripcion."</td>
						<tr>";
					}
?>
				</table>
			</div>
			
		</section>
	</div>
	<?php
		}
	?>
</div>
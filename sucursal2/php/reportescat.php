<?php
if (!isset($_GET['id'])){
?>
	<section class="panel panel-default pos-rlt clearfix">

		<header class="panel-heading"> <i class="fa fa-bar-chart-o"></i> Reportes de Categorias</header>

		<div class="table-responsive">
			<br>
			<table class="table table-striped b-t b-light">
				<thead>
					<tr>
						<th>Nombre de Categoria</th>
						<th width="100"></th>
					</tr>
				<tbody>
	<?php
				$query  = mysql_query("SELECT * FROM categorias ORDER BY categoria ASC");

				$suma = 0;
				$total = 0;
				while($q = mysql_fetch_object($query)){
					
							echo '<tr>
								<td>'.$q->categoria.'</td>
								<td class="text-center">
									<a href="admin.php?m=reportescats&id='.$q->idcategorias.'" class="btn btn-sm btn-default"> <i class="fa fa-eye"></i> </a>
								</td>
							</tr>';
				}
	?>
			    </tbody>
				</thead>
			</table>
		</div>
	</section>
<?php
} else {
	$id = mysql_real_escape_string($_GET['id']);
	$data = mysql_fetch_object(mysql_query("SELECT * FROM categorias WHERE idcategorias='".$id."' LIMIT 1"));

	$imprimir = "imprimir/categorias.php?id=".$id."&cat=".$data->categoria;
?>
	<section class="panel panel-default pos-rlt clearfix">

		<header class="panel-heading"> <i class="fa fa-bar-chart-o"></i> Reportes de Categoria: <?php echo $data->categoria; ?></header>

		<div class="row wrapper">
			<div class="col-md-12">
				<a href="admin.php?m=reportescats" class="btn btn-default btn-md"> <i class="fa fa-reply"></i> Regresar </a>
				<a href="<?php echo $imprimir; ?>" target="_blank" class="m-l btn btn-md btn-default"><i class="fa fa-print"></i> Imprimir Resultados </a>
			</div>
		</div>
		
		<div class="table-responsive">
			<br>
			<table class="table table-striped b-t b-light">
				<thead>
					<tr>
						<th>Nombre de Articulo</th>
						<th class="text-center" width="120">Cantidad</th>
						<th class="text-center" width="180"></th>
					</tr>

				<tbody>

	<?php
				$query  = mysql_query("SELECT SUM(stock) as stock,articulo FROM articulos WHERE idcategoria='".$id."' GROUP BY articulo") or die(mysql_error());
				$total  = 0;
				while($q = mysql_fetch_object($query)){
					
					echo '<tr>
						<td>'.$q->articulo.'</td>
						<td class="text-center">'.$q->stock.'</td>
						<td class="text-center">
									<a href="admin.php?m=reportesart&Art='.urlencode($q->articulo).'" class="btn btn-sm btn-default"> <i class="fa fa-eye"></i> </a>
								</td>
					</tr>';

					$total += $q->stock;
				}
	?>
			    </tbody>
				</thead>
			</table>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-sm-12 text-right text-center-xs">
					<strong>Total articulos: <?php echo $total; ?></strong>
				</div>
			</div>
		</footer>
	</section>
<?php
}
?>
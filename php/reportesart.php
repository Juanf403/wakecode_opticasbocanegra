<?php
if (!isset($_GET['id'])){
	$art = mysql_real_escape_string($_GET['Art']);
	$imprimir = "imprimir/articulos.php?Art=".$art.'"';
?>
	<section class="panel panel-default pos-rlt clearfix">

		<header class="panel-heading"> <i class="fa fa-bar-chart-o"></i> Reportes de Articulo: <?php echo $art ?></header>

		<div class="row wrapper">
			<div class="col-md-12">
				<a href="<?php echo $imprimir; ?>" target="_blank" class="m-l btn btn-md btn-default"><i class="fa fa-print"></i> Imprimir Resultados </a>
			</div>
		</div>

		<div class="table-responsive">
			<br>
			<table class="table table-striped b-t b-light">
				<thead>
					<tr>
						<th>Descripcion</th>
						<th>Observaciones</th>
						<th>Precio</th>
						<th>Cantidad</th>
					</tr>
				<tbody>
	<?php
				$query  = mysql_query("SELECT * FROM articulos JOIN categorias ON categorias.idcategorias=articulos.idcategoria WHERE articulos.articulo = '".$art."' ORDER BY articulo ASC");

				$suma = 0;
				$total = 0;
				while($q = mysql_fetch_object($query)){
					
							echo '<tr>
								<td>'.$q->descripcion.'</td>
								<td>'.$q->observaciones.'</td>
								<td>'.$q->precio.'</td>
								<td>'.$q->stock.'</td>
								
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
					</tr>

				<tbody>

	<?php
				$query  = mysql_query("SELECT SUM(stock) as stock,articulo FROM articulos WHERE idcategoria='".$id."' GROUP BY articulo") or die(mysql_error());
				$total  = 0;
				while($q = mysql_fetch_object($query)){
					
					echo '<tr>
						<td>'.$q->articulo.'</td>
						<td class="text-center">'.$q->stock.'</td>
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
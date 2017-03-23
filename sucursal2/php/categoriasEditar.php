<?php

$id = mysql_real_escape_string($_GET['id']);

if ( isset($_POST['nombre']) ){

	$tipo  	= mysql_real_escape_string($_POST['tipo']);
	$nombre = mysql_real_escape_string($_POST['nombre']);

	if ( mysql_query("UPDATE categorias SET tipo='".$tipo."',categoria='".$nombre."' WHERE idcategorias='".$id."'") )
	{
		$errorMsg = '<div class="alert alert-success">
				<i class="fa fa-check"></i> Categoria editada correctamente.
			</div>';
	} else {
		$errorMsg = '<div class="alert alert-danger">
			<i class="fa fa-times"></i> Error, intenta nuevamente.
		</div>';

	}
}
$data = mysql_fetch_object(mysql_query("SELECT * FROM categorias WHERE idcategorias='".$id."' LIMIT 1"));
?>	
	<section class="panel panel-default">
			<header class="panel-heading">
				<i class="fa fa-list icon"></i> Editar Categoria
			</header>
			<div class="panel-body">
				<form class="bs-example form-horizontal" action="" method="post">
					<?php echo $errorMsg; ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label">Tipo</label>
								<div class="col-md-8">
									<select name="tipo" class="form-control">
										<option <?php if ($data->tipo == "Producto") echo "selected"; ?>>Producto</option>
										<option <?php if ($data->tipo == "Servicio") echo "selected"; ?>>Servicio</option>
									</select>
								</div>
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-2 control-label">Categoria</label>
								<div class="col-md-10"><input type="text" name="nombre" class="form-control" value="<?php echo $data->categoria; ?>" placeholder=""></div>
							</div>
						</div>	
					</div>
					<div class="line line-dashed line-lg pull-in"></div>
					<div class="form-group text-right">
						<div class="col-lg-12"> 
							<button type="submit" class="btn btn-md btn-success"><i class="fa fa-check icon"></i> Agregar</button>
							<a href="admin.php?m=categorias" class="btn btn-md btn-danger"><i class="fa fa-times icon"></i> Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</section>

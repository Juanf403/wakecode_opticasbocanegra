<?php

if ( isset($_POST['nombre']) ){

	$tipo  	= mysql_real_escape_string($_POST['tipo']);
	$nombre = mysql_real_escape_string($_POST['nombre']);

	if ( mysql_query("INSERT INTO categorias SET tipo='".$tipo."',categoria='".$nombre."'") ){
		$errorMsg = '<div class="alert alert-success">
				<i class="fa fa-check"></i> Categoria agregado correctamente.
			</div>';
	} else {
		$errorMsg = '<div class="alert alert-danger">
			<i class="fa fa-times"></i> Error, intenta nuevamente.
		</div>';
	}
}

?>
<section class="panel panel-default">
			<header class="panel-heading">
				<i class="fa fa-list icon"></i> Agregar Categoria
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
										<option>Producto</option>
										<option>Servicio</option>
									</select>
								</div>
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label">Categoria</label>
								<div class="col-md-8"><input type="text" name="nombre" class="form-control" placeholder=""></div>
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
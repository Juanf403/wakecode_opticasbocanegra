<?php

$id = mysql_real_escape_string($_GET['id']);

if ( isset($_POST['nombre']) ){

	$nombre 		    = mysql_real_escape_string($_POST['nombre']);
	$categoria 		  	= mysql_real_escape_string($_POST['categoria']);
	$precio  			= mysql_real_escape_string($_POST['precio']);
	$stock  	     	= mysql_real_escape_string($_POST['stock']);
	$alerta      		= mysql_real_escape_string($_POST['alerta']);
	$descripcion  	    = mysql_real_escape_string($_POST['descripcion']);
	$observaciones  	= mysql_real_escape_string($_POST['observaciones']);
	$costo  			= mysql_real_escape_string($_POST['costo']);
	
	if ( mysql_query("UPDATE articulos SET articulo='".$nombre."',idcategoria='".$categoria."',precio='".$precio."',stock='".$stock."',alerta='".$alerta."',descripcion='".$descripcion."',observaciones='".$observaciones."',costo='".$costo."' WHERE idarticulos='".$id."'") ){
		$errorMsg = '<div class="alert alert-success">
				<i class="fa fa-check"></i> Articulo editado correctamente.
			</div>';
	} else {
		$errorMsg = '<div class="alert alert-danger">
			<i class="fa fa-times"></i> Error, intenta nuevamente.
		</div>';
	}

}

$data = mysql_fetch_object(mysql_query("SELECT * FROM articulos WHERE idarticulos='".$id."' LIMIT 1"));
?>
		<section class="panel panel-default">
			<header class="panel-heading">
				<i class="fa fa-tag"></i> Agregar Articulo
			</header>
			<div class="panel-body">
				<form class="bs-example form-horizontal" action="" method="post">
					<?php echo $errorMsg; ?>
					<div class="form-group">
						<label class="col-md-2 control-label">Nombre</label>
						<div class="col-md-10"><input type="text" name="nombre" class="form-control" value="<?php echo $data->articulo; ?>"></div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Categoria</label>
						<div class="col-lg-10">
							<select class="form-control" name="categoria">
										<option></option>
<?php
									$query = mysql_query("SELECT * FROM categorias ORDER BY categoria ASC");
									while($q = mysql_fetch_object($query)){ 

										if ($q->idcategorias == $data->idcategoria)
											echo '<option value="'.$q->idcategorias.'" selected>'.$q->tipo.' - '.$q->categoria.'</option>';
										else 
											echo '<option value="'.$q->idcategorias.'">'.$q->tipo.' - '.$q->categoria.'</option>';
									}
?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Precio</label>
								<div class="col-md-3"><input type="text" name="precio" class="form-control" value="<?php echo $data->precio; ?>"></div>
								<label class="col-md-3 control-label">Costo</label>
								<div class="col-md-3"><input type="text" name="costo" class="form-control" value="<?php echo $data->costo; ?>"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-6 control-label">Stock</label>
										<div class="col-md-6"><input type="text" name="stock" class="form-control" value="<?php echo $data->stock; ?>"></div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-6 control-label">Alerta</label>
										<div class="col-md-6"><input type="text" name="alerta" class="form-control" value="<?php echo $data->alerta; ?>"></div>
									</div>
								</div>
							</div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Descripcion</label>
								<div class="col-md-9"><textarea class="form-control" name="descripcion" style="height:150px;" placeholder=""><?php echo $data->descripcion; ?></textarea></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-3 control-label">Observaciones</label>
								<div class="col-md-9"><textarea class="form-control" name="observaciones" style="height:150px;" placeholder=""><?php echo $data->observaciones; ?></textarea></div>
							</div>
						</div>
					</div>
					<div class="line line-dashed line-lg pull-in"></div>
					<div class="form-group text-right">
						<div class="col-md-12"> 
							<button type="submit" class="btn btn-md btn-success"><i class="fa fa-check icon"></i> Editar</button>
							<a href="admin.php?m=inventario&buscar=<?php echo @$_GET['buscar']; ?>" class="btn btn-md btn-danger"><i class="fa fa-times icon"></i> Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</section>
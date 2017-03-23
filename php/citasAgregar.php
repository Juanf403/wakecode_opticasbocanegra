<?php

if ( isset($_POST['fecha']) ){

	$fecha  = mysql_real_escape_string($_POST['fecha']);
	$hora   = mysql_real_escape_string($_POST['hora']);
	$cliente = mysql_real_escape_string($_POST['cliente']);
	$desc   = mysql_real_escape_string($_POST['desc']);
	

	if ( mysql_query("INSERT INTO citas SET fecha='".$fecha."',hora='".$hora."',idcliente='".$cliente."',descripcion='".$desc."'")){
		$errorMsg = '<div class="alert alert-success">
				<i class="fa fa-check"></i> Cita agregada correctamente.
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
				<i class="fa fa-calendar icon"></i> Agregar Cita
			</header>
			<div class="panel-body">
				<form class="bs-example form-horizontal" action="" method="post">
					<?php echo $errorMsg; ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Fecha de Visita</label>
								<div class="col-lg-9 col-md-9 ">
									<input class="datepicker-input form-control" name="fecha" type="text" value="<?php echo date("Y-m-d"); ?>" data-date-format="yyyy-mm-dd">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Hora de Visita</label>
								<div class="col-lg-9 col-md-9 "><input type="text" name="hora" class="form-control"></div>
							</div>
							
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Nombre del Cliente</label>
								<div class="col-lg-9 col-md-9">
									<select class="form-control" name="cliente" id="cliente" style="width:100%;">
										<option></option>
									</select>
								</div>
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Descripcion</label>
								<div class="col-lg-9 col-md-9 "><textarea name="desc" class="form-control" style="height:200px;"></textarea></div>
							</div>
						</div>	
					</div>
					<div class="line line-dashed line-lg pull-in"></div>
					<div class="form-group text-right">
						<div class="col-lg-12"> 
							<button type="submit" class="btn btn-md btn-success"><i class="fa fa-check icon"></i> Agregar</button>
							<a href="admin.php?m=citas" class="btn btn-md btn-danger"><i class="fa fa-times icon"></i> Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</section>

<script type="text/javascript">
$(function(){
	$("#cliente").select2({
  		ajax: {
    		url: 'php/ajax/clientes.php',
    		dataType: "json",
    		data: function( params ){
	    		return {
	   				q: params.term
	    		};
	   		},
	    	processResults: function (data, params) {
	      		return {
       				results: data,
        		};
	    	},
    		cache: true
  		},
  		placeholder: "Selecciona un cliente...",
	});
});
</script>
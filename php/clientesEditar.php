<?php

$id = mysql_real_escape_string($_GET['id']);

if ( isset($_POST['nombre']) ){

	$nombre 	    = mysql_real_escape_string($_POST['nombre']);
	$correo 	    = mysql_real_escape_string($_POST['correo']);
	$nacimiento     = mysql_real_escape_string($_POST['nacimiento']);
	$sexo  	        = mysql_real_escape_string($_POST['sexo']);
	$edad  	        = mysql_real_escape_string($_POST['edad']);
	$domicilio  	= mysql_real_escape_string($_POST['domicilio']);
	$telefono  		= mysql_real_escape_string($_POST['telefono']);
	$colonia  	    = mysql_real_escape_string($_POST['colonia']);
	$cp  		    = mysql_real_escape_string($_POST['cp']);
	$celular  	    = mysql_real_escape_string($_POST['celular']);
	$ocupacion 	    = mysql_real_escape_string($_POST['ocupacion']);
    $usalentes 		= mysql_real_escape_string($_POST['usalentes']);
	$ultimoexamen 	= mysql_real_escape_string($_POST['ultimoexamen']);
	$eninterior 	= mysql_real_escape_string($_POST['eninterior']);
	$campo1 		= mysql_real_escape_string($_POST['campo1']);
	$campo2 		= mysql_real_escape_string($_POST['campo2']);
	$campo3 		= mysql_real_escape_string($_POST['campo3']);
	$campo4 		= mysql_real_escape_string($_POST['campo4']);
	$campo5 		= mysql_real_escape_string($_POST['campo5']);
	$campo6 		= mysql_real_escape_string($_POST['campo6']);
	$campo7 		= mysql_real_escape_string($_POST['campo7']);
	$campo8 		= mysql_real_escape_string($_POST['campo8']);
	$campo9 		= mysql_real_escape_string($_POST['campo9']);
	$campo10  		= mysql_real_escape_string($_POST['campo10']);
	$campo11 		= mysql_real_escape_string($_POST['campo11']);
	$campo12 		= mysql_real_escape_string($_POST['campo12']);
	$diabetes 		= mysql_real_escape_string($_POST['diabetes']); 
	$controlada1	= mysql_real_escape_string($_POST['controlada1']);
	$hipertension 	= mysql_real_escape_string($_POST['hipertension']);
	$controlada2 	= mysql_real_escape_string($_POST['controlada2']);
	$airelibre 		= mysql_real_escape_string($_POST['airelibre']);
	$campo13 		= mysql_real_escape_string($_POST['campo13']);
	$campo14 		= mysql_real_escape_string($_POST['campo14']);
	$campo15 		= mysql_real_escape_string($_POST['campo15']);
	$campo16 		= mysql_real_escape_string($_POST['campo16']);
	$campo17 		= mysql_real_escape_string($_POST['campo17']);
	$campo18 		= mysql_real_escape_string($_POST['campo18']);
	$campo19	 	= mysql_real_escape_string($_POST['campo19']);
	$campo20		= mysql_real_escape_string($_POST['campo20']);
	$campo21 		= mysql_real_escape_string($_POST['campo21']);
	$campo22 		= mysql_real_escape_string($_POST['campo22']);
	$campo23 		= mysql_real_escape_string($_POST['campo23']);

	if ( mysql_query("UPDATE clientes SET 
		nombre='".$nombre."',
		fechanacimiento='".$nacimiento."',
		correo='".$correo."',
		sexo='".$sexo."',
		edad='".$edad."',
		domicilio='".$domicilio."',
		colonia='".$colonia."',
		codigopostal='".$cp."',
		telefono='".$telefono."',
		celular='".$celular."',
		ocupacion='".$ocupacion."',
		usalentes='".$usalentes."',
		ultimoexamen='".$ultimoexamen."',
		eninterior='".$eninterior."',
		campo1='".$campo1."',
		campo2='".$campo2."',
		campo3='".$campo3."',
		campo4='".$campo4."',
		campo5='".$campo5."',
		campo6='".$campo6."',
		campo7='".$campo7."',
		campo8='".$campo8."',
		campo9='".$campo9."',
		campo10='".$campo10."',
		campo11='".$campo11."',
		campo12='".$campo12."',
		diabetes='".$diabetes."',
		controlada1='".$controlada1."',
		hipertension='".$hipertension."',
		controlada2='".$controlada2."',
		airelibre='".$airelibre."',
		campo13='".$campo13."',
		campo14='".$campo14."',
		campo15='".$campo15."',
		campo16='".$campo16."',
		campo17='".$campo17."',
		campo18='".$campo18."',
		campo19='".$campo19."',
		campo20='".$campo20."',
		campo21='".$campo21."',
		campo22='".$campo22."',
		campo23='".$campo23."'
		WHERE idclientes='".$id."'") ){
		$errorMsg = '<div class="alert alert-success">
				<i class="fa fa-check"></i> Cliente agregado correctamente.
			</div>';
	} else {
		$errorMsg = '<div class="alert alert-danger">
			<i class="fa fa-times"></i> Error, intenta nuevamente.
		</div>';
	}
}

$data = mysql_fetch_object(mysql_query("SELECT * FROM clientes WHERE idclientes='".$id."' LIMIT 1"));
?>
		<section class="panel panel-default">
			<header class="panel-heading">
				<i class="fa fa-user icon"></i> Editar Cliente
			</header>
			<div class="panel-body">
				<form class="bs-example form-horizontal" action="" method="post">
					<?php echo $errorMsg; ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Nombre</label>
								<div class="col-lg-9 col-md-9"><input type="text" name="nombre" class="form-control" value="<?php echo $data->nombre; ?>"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Correo</label>
								<div class="col-lg-9 col-md-9"><input type="text" name="correo" class="form-control" value="<?php echo $data->correo; ?>"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Domicilio</label>
								<div class="col-lg-9 col-md-9"><input type="text" name="domicilio" class="form-control" value="<?php echo $data->domicilio; ?>"></div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Colonia</label>
								<div class="col-md-9"><input type="text" name="colonia" class="form-control" value="<?php echo $data->colonia; ?>"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Telefono</label>
								<div class="col-lg-9 col-md-9 "><input type="text" name="telefono" class="form-control" value="<?php echo $data->telefono; ?>"></div>
							</div>
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Primera Visita</label>
								<div class="col-lg-9 col-md-9 "><input type="text" name="nacimiento" class="form-control" value="<?php echo $data->fechanacimiento; ?>"></div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-6 control-label">Sexo</label>
										<div class="col-md-6">
											<select class="form-control" name="sexo">
												<option></option>
												<option <?php if ($data->sexo == "Masculino") echo "selected"; ?>>Masculino</option>
												<option <?php if ($data->sexo == "Femenino") echo "selected"; ?>>Femenino</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-6 control-label">Edad</label>
										<div class="col-md-6"><input type="text" name="edad" class="form-control" value="<?php echo $data->edad; ?>"></div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Estatus Actual</label>
								<div class="col-md-9"><input type="text" name="cp" class="form-control" value="<?php echo $data->codigopostal; ?>"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Otros</label>
								<div class="col-lg-9  col-md-9 "><input type="text" name="celular" class="form-control" value="<?php echo $data->celular; ?>"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 col-md-3 control-label">Ocupaci&oacute;n</label>
								<div class="col-lg-9 col-md-9 "><input type="text" name="ocupacion" class="form-control" value="<?php echo $data->ocupacion; ?>"></div>
							</div>
						</div>	
					</div>

					<section class="panel panel-default">
						<header class="panel-heading">
							<i class="fa fa-edit icon"></i> Historial Médico
						</header>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-6 control-label">Ya usa lentes?</label>
										<div class="col-md-6 ">
											<select class="form-control" name="usalentes">
												<option <?php if ($data->usalentes == "NO") echo "selected"; ?>>NO</option>
												<option <?php if ($data->usalentes == "SI") echo "selected"; ?>>SI</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-6 control-label">Fecha del Ultimo Examen</label>
										<div class="col-md-6">
											<input type="text" name="ultimoexamen" class="form-control" value="<?php echo $data->ultimoexamen; ?>" placeholder="">
										</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-6 control-label">Padece Diabetes?</label>
												<div class="col-md-6">
													<select class="form-control" name="diabetes">
														<option <?php if ($data->diabetes == "NO") echo "selected"; ?>>NO</option>
														<option <?php if ($data->diabetes == "SI") echo "selected"; ?>>SI</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-6 control-label">Padece Hipertension?</label>
												<div class="col-md-6">
													<select class="form-control" name="hipertension">
														<option <?php if ($data->hipertension == "NO") echo "selected"; ?>>NO</option>
														<option <?php if ($data->hipertension == "SI") echo "selected"; ?>>SI</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-6 control-label">Controlada?</label>
												<div class="col-md-6">
													<select class="form-control" name="controlada1">
														<option <?php if ($data->controlada1 == "NO") echo "selected"; ?>>NO</option>
														<option <?php if ($data->controlada1 == "SI") echo "selected"; ?>>SI</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-6 control-label">Controlada?</label>
												<div class="col-md-6">
													<select class="form-control" name="controlada2">
														<option <?php if ($data->controlada2 == "NO") echo "selected"; ?>>NO</option>
														<option <?php if ($data->controlada2 == "SI") echo "selected"; ?>>SI</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="line line-dashed line-lg pull-in"></div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-6 control-label">Trabaja en el Interior</label>
										<div class="col-md-6">
											<select class="form-control" name="eninterior">
												<option <?php if ($data->eninterior == "NO") echo "selected"; ?>>NO</option>
												<option <?php if ($data->eninterior == "SI") echo "selected"; ?>>SI</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-3 col-md-3 control-label">Al aire libre?</label>
										<div class="col-lg-9 col-md-9 ">
											<select class="form-control" name="airelibre">
												<option <?php if ($data->airelibre == "NO") echo "selected"; ?>>NO</option>
												<option <?php if ($data->airelibre == "SI") echo "selected"; ?>>SI</option>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="line line-dashed line-lg pull-in"></div>

							<div class="row">
								<div class="col-md-12">
									<table class="table table-bordered">
										<tr>
											<th>GRAD.</th>
											<th width="120"></th>
											<th>ESF.</th>
											<th>CIL.</th>
											<th>EJE</th>
											<th>ADD</th>
										</tr>
										<tr>
											<td>RX</td>
											<td>OJO DER.</td>
											<td><input type="text" class="form-control" name="campo1" value="<?php echo $data->campo1; ?>"/></td>
											<td><input type="text" class="form-control" name="campo2" value="<?php echo $data->campo2; ?>"/></td>
											<td><input type="text" class="form-control" name="campo3" value="<?php echo $data->campo3; ?>"/></td>
											<td><input type="text" class="form-control" name="campo4" value="<?php echo $data->campo4; ?>"/></td>
										</tr>
										<tr>
											<td>ARMAZON</td>
											<td>OJO IZQ</td>
											<td><input type="text" class="form-control" name="campo5" value="<?php echo $data->campo5; ?>"/></td>
											<td><input type="text" class="form-control" name="campo6" value="<?php echo $data->campo6; ?>"/></td>
											<td><input type="text" class="form-control" name="campo7" value="<?php echo $data->campo7; ?>"/></td>
											<td><input type="text" class="form-control" name="campo8" value="<?php echo $data->campo8; ?>"/></td>
										</tr>
									</table>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered">
										<tr>
											<th>GRAD.</th>
											<th width="120"></th>
											<th>ESF.</th>
											<th>CIL.</th>
											<th>EJE</th>
											<th>ADD</th>
										</tr>
										<tr>
											<td>RX</td>
											<td>OJO DER.</td>
											<td><input type="text" class="form-control" name="campo13"  value="<?php echo $data->campo13; ?>"/></td>
											<td><input type="text" class="form-control" name="campo14"  value="<?php echo $data->campo14; ?>"/></td>
											<td><input type="text" class="form-control" name="campo15"  value="<?php echo $data->campo15; ?>"/></td>
											<td><input type="text" class="form-control" name="campo16"  value="<?php echo $data->campo16; ?>"/></td>
										</tr>
										<tr>
											<td>L / C</td>
											<td>OJO IZQ</td>
											<td><input type="text" class="form-control" name="campo17" value="<?php echo $data->campo17; ?>"/></td>
											<td><input type="text" class="form-control" name="campo18" value="<?php echo $data->campo18; ?>"/></td>
											<td><input type="text" class="form-control" name="campo19" value="<?php echo $data->campo19; ?>"/></td>
											<td><input type="text" class="form-control" name="campo20" value="<?php echo $data->campo20; ?>"/></td>
										</tr>
									</table>
								</div>
							</div>

							<div class="line line-dashed line-lg pull-in"></div>

							<div class="row">
								<div class="col-md-6">
									<table class="table table-bordered">
										<tr>
											<th colspan="2">RETINOSCOPIA</th>
										</tr>
										<tr>
											<td width="250">OJO DER.</td>
											<td><input type="text" class="form-control" name="campo9" value="<?php echo $data->campo9; ?>"/></td>
										</tr>
										<tr>
											<td width="250">OJO IZQ.</td>
											<td><input type="text" class="form-control" name="campo10" value="<?php echo $data->campo10; ?>"/></td>
										</tr>
									</table>

									<table class="table table-bordered">
										<tr>
											<td width="250">DISTANCIA DE LECTURA</td>
											<td>
												<div class="input-group"> <input type="text" name="campo11" value="<?php echo $data->campo11; ?>" class="form-control"> <span class="input-group-addon">cm</span> </div>
											</td>
										</tr>
										<tr>
											<td width="250">DIP</td>
											<td>
												<div class="input-group"> <input type="text" name="campo12" value="<?php echo $data->campo12; ?>" class="form-control"> <span class="input-group-addon">mm</span> </div>
											</td>
										</tr>
									</table>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-md-6 control-label">PPC</label>
										<div class="col-md-6">
											<input type="text" class="form-control" name="campo21" value="<?php echo $data->campo21; ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-6 control-label">COVER TEST</label>
										<div class="col-md-6">
											<input type="text" class="form-control" name="campo22" value="<?php echo $data->campo22; ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-6 control-label">AMPL. ACOMODACION</label>
										<div class="col-md-6">
											<input type="text" class="form-control" name="campo23" value="<?php echo $data->campo23; ?>"/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

					<div class="line line-dashed line-lg pull-in"></div>
					<div class="form-group text-right">
						<div class="col-lg-12"> 
							<button type="submit" class="btn btn-md btn-success"><i class="fa fa-check icon"></i> Editar</button>
							<a href="admin.php?m=clientes&buscar=<?php echo @$_GET['buscar']; ?>" class="btn btn-md btn-danger"><i class="fa fa-times icon"></i> Cancelar</a>
						</div>
					</div>
				</form>
			</div>
		</section>

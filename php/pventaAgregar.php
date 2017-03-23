<?php
 
if ( isset($_POST['cliente']) ){

	$fecha 	  = date("Y-m-d");
	$hora 	  = date("H:m:s");
	$venta 	  = mysql_real_escape_string($_POST['venta']);
	$cliente  = mysql_real_escape_string($_POST['cliente']);
	$pagocon  = mysql_real_escape_string($_POST['pagocon']);
	$metodo   = mysql_real_escape_string($_POST['metodo']);
	$descuento = mysql_real_escape_string($_POST['descuento']);
	/*22-marzo-17 se agrega campo vendedor*/
	$vendedor = mysql_real_escape_string($_POST['vendedor']);


	mysql_query("INSERT INTO ventas SET fecha='".$fecha."',hora='".$hora."',idcliente='".$cliente."',venta='".$venta."',descuento='".$descuento."',vendedor='".$vendedor."',idusuario='".$_SESSION['userId']."'") or die(mysql_error());
	$idventa = mysql_insert_id();

	$idarticulo = $_POST['idarticulo'];
	$precio 	= $_POST['precio'];
	$cantidad 	= $_POST['cantidad'];
	$sql 		= array();

	$Total = 0;
	for ($i=0; $i < count($idarticulo); $i++) {

		$total = $cantidad[$i] * $precio[$i];
		
		$sql[] = "(".$idventa.",'".mysql_real_escape_string($idarticulo[$i])."','".mysql_real_escape_string($precio[$i])."','".mysql_real_escape_string($cantidad[$i])."','".mysql_real_escape_string($total)."')";
		$Total += $total;
		#echo $cantidad[$i]."<br>";

		if ($venta == "finalizada"){
			mysql_query("UPDATE articulos SET stock=stock-".$cantidad[$i]." WHERE idarticulos='".mysql_real_escape_string($idarticulo[$i])."'");
		}
	}

	mysql_query("INSERT INTO ventas_articulos(idventa,idarticulo,precio,cantidad,total) VALUES ".implode(",", $sql));
	
	if (!empty($_POST['pagocon'])){
		
		if ( $pagocon >= $Total){
			$pagototal = $Total;

			$cambio = $pagocon - $Total;

			$errorMsg = '<div class="col-md-12">
				<div class="alert alert-success">
					<i class="fa fa-check"></i> Venta No: <strong>'.$idventa.'</strong> agregada, cambio para el cliente <strong>$ '.$cambio.' pesos</strong>
				</div>
			</div>';
		} else {
			$pagototal = $pagocon;

			$errorMsg = '<div class="col-md-12">
				<div class="alert alert-success">
					<i class="fa fa-check"></i> Venta No: <strong>'.$idventa.'</strong> agregada.</strong>
				</div>
			</div>';
		}

		mysql_query("INSERT INTO ventas_pagos SET idventa='".$idventa."',fecha='".$fecha."',hora='".$hora."',cantidad='".$pagototal."',metodo='".$metodo."'");
		
	} else {
		$errorMsg = '<div class="col-md-12">
				<div class="alert alert-success">
					<i class="fa fa-check"></i> Venta No: <strong>'.$idventa.'</strong> agregada.</strong>
				</div>
			</div>';
	}
	
	#if ( !empty($_POST['anticipo']) ){
	#	mysql_query("INSERT INTO pagos SET ordenId='".$ordenId."',fecha='".$fecha."',descripcion='Anticipo',cantidad='".$anticipo."',metodopago='".$metodopago."'");
	#}

	

}
?>
<form class="bs-example form-horizontal" action="" method="post">
	<div class="row">
		<?php echo @$errorMsg; ?>
		<div class="col-md-4">
			<section class="panel panel-default">
				<header class="panel-heading">
					<i class="fa fa-user icon"></i> Seleccionar Cliente
				</header>
				<div class="panel-body">
					<a href="#" class="btn btn-success btn-sm pull-right m-b agregarCliente"> <i class="fa fa-plus"></i> Agregar Nuevo Cliente</a>
					<select class="form-control" name="cliente" id="cliente" style="width:100%;">
						<option></option>
					</select>

					<div class="alert alert-warning m-t" style="display:none;" id="errorCliente">
						<i class="fa fa-warning"></i> Favor de seleccionar un cliente.
					</div>
				</div>
			</section>
			<section class="panel panel-default">
				<header class="panel-heading">
					<i class="fa fa-usd icon"></i> Informacion de Venta
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped">
								<tr>
									<th width="200">
										<label class="control-label"><strong>Vendedor: </strong></label>
									</th>
									<td>
										<select class="form-control" name="vendedor" id="vendedor" style="width:100%;">
										<option></option>
										</select>
									</td>
								</tr>
								<tr>
									<th width="200">Subtotal: </th>
									<td class="text-right"> 
										$ <span id="subtotal"> 0.00 </span> pesos
										<input type="hidden" id="subtotalOculto" value=""/>
									</td>
								</tr>
								<tr>
									<th width="200">
										<label class="control-label"><strong>Descuento: (%) </strong></label>
									</th>
									<td>
										<div class="form-group">
											<input type="text" class="form-control input-md text-right descuento" id="descuento" name="descuento" value="0" />
										</div>
									</td>
								</tr>
								<tr>
									<th width="200">Total: </th>
									<td class="text-right"> $ <span id="total"> 0.00 </span> pesos</td>
								</tr>
								<tr>
									<th width="200">
										<label class="control-label"><strong>Metodo de Pago: </strong></label>
									</th>
									<td>
										<div class="form-group">
											<select name="metodo" class="form-control">
												<option>Efectivo</option>
												<option>Tarjeta Debido/Credito</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<th width="200">
										<label class="control-label"><strong>Pago Con: </strong></label>
									</th>
									<td>
										<div class="form-group">
											<input type="text" class="form-control input-md text-right" id="pagocon" name="pagocon" value="0" />
										</div>
									</td>
								</tr>
								<tr>
									<th width="200">Cambio: </th>
									<td width="300" class="text-right"> $ <span id="cambio"> 0.00 </span> pesos</td>
								</tr>
							</table>
						</div>
					</div>
					<?php
					if ($_SESSION['userPv'] != "3"){
						?>
					<div class="line line-dashed line-lg pull-in"></div>

					<button name="venta" type="submit" value="finalizada"  class="btn-block btn btn-lg btn-success"><i class="fa fa-check icon"></i> Finalizar Venta</button>

					<button name="venta" type="submit" value="separado" class="btn-block btn btn-lg btn-info"><i class="fa fa-clock-o icon"></i> Separado</button>

					<div class="line line-dashed line-lg pull-in"></div>

					<a href="admin.php?m=pventa" class="btn btn-md btn-danger btn-block"><i class="fa fa-times icon"></i> Cancelar</a>
					<?php
						}
					?>
				</div>
			</section>
		</div>
		<div class="col-md-8">
			<section class="panel panel-default">
				<header class="panel-heading">
					<i class="fa fa-shopping-cart icon"></i> Agregar Articulo
				</header>
				<div class="panel-body">
					<div class="row m-b">
						<div class="col-md-12" >
							<select class="form-control input-md" id="articulo" style="width:100%;">
								<option></option>
							</select>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-striped" id="productos">
							<tr>
								<th>Articulo</th>
								<th width="120">Precio U.</th>
								<th width="100">Cantidad</th>
								<th width="120">Total</th>
								<th></th>
							</tr>
						</table>						
					</div>
				</div>
			</section>
		</div>
	</div>
</form>
		
<div class="modal fade" id="modal-clientes">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="m-t-none m-b">Agregar Cliente</h3>
						<form role="form" action="" method="post" id="formCliente">
							<div class="form-group">
								<div class="row">
									<label class="col-md-4 control-label"><strong>Nombre</strong></label>
									<div class="col-md-8"><input type="text" class="form-control" name="nombre"></div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<label class="col-md-4 control-label"><strong>Tel&eacute;fono</strong></label>
									<div class="col-md-8"><input type="text" class="form-control" name="telefono" ></div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<label class="col-md-4 control-label"><strong>Correo</strong></label>
									<div class="col-md-8"><input type="text" class="form-control" name="correo"></div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<label class="col-md-4 control-label"><strong>Direcci&oacute;n</strong></label>
									<div class="col-md-8"><input type="text" class="form-control" name="direccion"></div>
								</div>
							</div>
							<div class="checkbox m-t-lg">
								<a class="btn btn-sm btn-default m-t-n-xs" id="cancelar"> <i class="fa fa-times"></i> <strong>Cancelar</strong></a>
								<button type="submit" class="btn btn-sm pull-right btn-success m-t-n-xs" id="submitCliente"> <i class="fa fa-check"></i> <strong>Agregar Cliente</strong></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>


<script>
function actualizarSaldos(){
	var total = 0;
	$(".totalArticulo").each(function(){
		total += parseInt( $(this).html() );
	});

	$("#subtotal").html(total);
	$("#subtotalOculto").val(total);

	if ( $("#descuento").val() != "0"){
		console.log("hay descuento");
		var descuento 	= $("#descuento").val();
		var total2 		= $("#subtotalOculto").val();
		var result = (descuento / 100) * total2;
		
		$("#total").html( total2 - result);
	} else {
		$("#total").html(total);	
	}
	
}

function actualizarTabla(id){
	var cuantos = $("#productos tr").length;
	var next 	= (cuantos+1);

	$.ajax({
  		dataType: "json",
  		url: "php/ajax/articulo.php?q="+id,
  		success: function(articulo){
  			var nuevaFila = '<tr>'+
                        '<td>'+articulo[0].articulo+
                        	'<input type="hidden" name="idarticulo[]" value="'+id+'">'+
                        	'<input type="hidden" name="precio[]" value="'+articulo[0].precio+'">'+
                        '</td>'+
						'<td class="text-right v-middle">$ <span class="precioArticulo">'+articulo[0].precio+'</span></td>'+
						'<td class="text-right v-middle"><input type="text" name="cantidad[]" value="1" data-precio="'+articulo[0].precio+'" data-oid="'+next+'" class="form-control cantidad text-right"></td>'+
						'<td class="text-right v-middle">$ <span class="totalArticulo" id="total_'+next+'">'+articulo[0].precio+'</span></td>'+
                        '<td class="text-right"><a href="#" class="btn btn-sm btn-danger clsEliminarFila"> <i class="fa fa-trash-o"></i> </a></td>'+
                    '</tr>';
    		$('table#productos tr:last').after(nuevaFila);
    		actualizarSaldos();
  		}
	});
}
	$(function(){

		$(".agregarCliente").click(function(){
			$("#modal-clientes").modal("show");
		});

		$("#cancelar").click(function(){
			$("#modal-clientes").modal("hide");
		});

		$("#submitCliente").click(function(e){
			e.preventDefault();

			var datos = $("#formCliente").serialize();

			$.ajax({
				method: "post",
		  		data: datos,
		  		url: "php/ajax/post.cliente.php",
		  		success: function(x){

		  			//	$("#cliente").attr('value',x.oid);
		  			//	$("#cliente").val(x.nombre);
		  				
		  				$("#modal-clientes").modal("hide");
		  			//}
		  		}
			});
		});

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
		
		$("#vendedor").select2({
  			ajax: {
    			url: 'php/ajax/vendedor.php',
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
  			placeholder: "Selecciona un vendedor...",
		});

		var $articulo = $("#articulo").select2({
  			ajax: {
    			url: 'php/ajax/articulos.php',
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
  			placeholder: "Selecciona un articulo...",
		});

		$articulo.on("select2:select", function (e) { 
			actualizarTabla(e.params.data.id)
			$articulo.val(null).trigger("change");
		});

		$(document).on('click','.clsEliminarFila',function(){
			var objFila = $(this).parents().get(1);
			$(objFila).remove();
			actualizarSaldos();
		});

		/* descuento */ 
		$(document).on('keyup','.descuento',function(){
			var descuento 	= $(this).val()
			var total 		= $("#subtotalOculto").val();

			var result = (descuento / 100) * total;
			
			$("#total").html( total - result);
		});

		$(document).on('keyup','.cantidad',function(){
			var este = $(this).val()
			var precio = $(this).data("precio");
			var ide = $(this).data("oid");
			var nuevo = parseInt(este) * parseInt(precio);
			
			$("#total_"+ide).html(nuevo);
			actualizarSaldos();
		});

		$(document).on('keyup', '#pagocon', function(){
			var total = $("#total").html();
			var pago  = $(this).val();

			var resta = parseInt(pago) - parseInt(total);
			$("#cambio").html(resta);
		});

		$(".form-horizontal").submit(function(e){
			
			if ( $("#cliente").val() == ""){
				$("#errorCliente").show();
				return false;
			}

		});
		
	});
</script>
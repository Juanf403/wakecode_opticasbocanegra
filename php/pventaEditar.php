<?php

$id = mysql_real_escape_string($_GET['id']);

if ( isset($_POST['idarticulo']) ){
	
	$fecha 	  = date("Y-m-d");
	$hora 	  = date("H:m:s");
	$venta 	  = mysql_real_escape_string($_POST['venta']);
	$preventa = mysql_real_escape_string($_POST['preventa']);
	$cliente  = mysql_real_escape_string($_POST['cliente']);
	$descuento = mysql_real_escape_string($_POST['descuento']);
	/*22-marzo-17 se agrega campo vendedor*/
	$vendedor = mysql_real_escape_string($_POST['vendedor']);

	mysql_query("UPDATE ventas SET idcliente='".$cliente."',descuento='".$descuento."',vendedor='".$vendedor."',venta='".$venta."' WHERE idventas='".$id."'") or die(mysql_error());
	mysql_query("DELETE FROM ventas_articulos WHERE idventa='".$id."'");

	$idarticulo = $_POST['idarticulo'];
	$precio 	= $_POST['precio'];
	$cantidad 	= $_POST['cantidad'];
	$sql 		= array();

	$Total = "";
	for ($i=0; $i < count($idarticulo); $i++) { 
		$total = $cantidad[$i] * $precio[$i];
		$sql[] = "(".$id.",'".mysql_real_escape_string($idarticulo[$i])."','".mysql_real_escape_string($precio[$i])."','".mysql_real_escape_string($cantidad[$i])."','".mysql_real_escape_string($total)."')";
		$Total += $total;

		if ($preventa == "separado"){
			if ($venta == "finalizada"){
				mysql_query("UPDATE articulos SET stock=stock-".$cantidad[$i]." WHERE idarticulos='".mysql_real_escape_string($idarticulo[$i])."'");
			}
		}
	}

	mysql_query("INSERT INTO ventas_articulos(idventa,idarticulo,precio,cantidad,total) VALUES ".implode(",", $sql));

	#mysql_query("INSERT INTO ventas_pagos SET idventa='".$id."',fecha='".$fecha."',hora='".$hora."',cantidad='".$pagocon."'");
	#if ( !empty($_POST['anticipo']) ){
	#	mysql_query("INSERT INTO pagos SET ordenId='".$ordenId."',fecha='".$fecha."',descripcion='Anticipo',cantidad='".$anticipo."',metodopago='".$metodopago."'");
	#}

	#$cambio = $pagocon - $Total;

	$errorMsg = '<div class="col-md-12">
				<div class="alert alert-success">
					<i class="fa fa-check"></i> Venta actalizada: <strong>'.$id.'</strong> editada</strong>
				</div>
			</div>';

}

if ( isset($_POST['cantidad2']) ){
	$metodo 	= mysql_real_escape_string($_POST['metodo']);
	$cantidad 	= mysql_real_escape_string($_POST['cantidad2']);
	$comentario = mysql_real_escape_string($_POST['comentario']);
	$fecha 	  	= date("Y-m-d");
	$hora 	  	= date("H:m:s");


	mysql_query("INSERT INTO ventas_pagos SET idventa='".$id."',fecha='".$fecha."',hora='".$hora."',cantidad='".$cantidad."',comentario='".$comentario."',metodo='".$metodo."'");

	echo '<div class="col-md-12">
		<div class="alert alert-success">
			<strong> <i class="fa fa-check"></i> Pago agregado correctamente.</strong>
		</div>
	</div>';
}

$data = mysql_fetch_object(mysql_query("SELECT * FROM ventas JOIN clientes ON clientes.idclientes=ventas.idcliente WHERE idventas='".$id."' LIMIT 1"));
$datav = mysql_fetch_object(mysql_query("SELECT * FROM ventas JOIN usuarios ON usuarios.idusuarios=ventas.vendedor WHERE idventas='".$id."' LIMIT 1"));
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
					<select class="form-control" name="cliente" id="cliente" style="width:100%;">
						<option value="<?php echo $data->idcliente; ?>"><?php echo $data->nombre; ?></option>
					</select>

<?php

				if ($data->idcliente != 0){
					$cliente = mysql_fetch_object(mysql_query("SELECT * FROM clientes WHERE idclientes='".$data->idcliente."' LIMIT 1"));

					echo '<div class="row m-t">
						<div class="col-xs-12 col-md-4"><strong>Nombre:</strong></div>
						<div class="col-xs-12 col-md-8">'.$cliente->nombre.'</div>
					</div>';


					echo '<div class="row m-t">
						<div class="col-xs-12 col-md-4"><strong>Direcci&oacute;n:</strong></div>
						<div class="col-xs-12 col-md-8">'.$cliente->domicilio.'</div>
					</div>';

					if (!empty($cliente->telefono)){
						echo '<div class="row m-t">
							<div class="col-xs-12 col-md-4"><strong>Tel&eacute;fono:</strong></div>
							<div class="col-xs-12 col-md-8">'.$cliente->telefono.'</div>
						</div>';
					}

					if (!empty($cliente->celular)){
						echo '<div class="row m-t">
							<div class="col-xs-12 col-md-4"><strong>Celular:</strong></div>
							<div class="col-xs-12 col-md-8">'.$cliente->celular.'</div>
						</div>';
					}
				
					if (!empty($cliente->correo)){
						echo '<div class="row m-t">
							<div class="col-xs-12 col-md-4"><strong>Correo:</strong></div>
							<div class="col-xs-12 col-md-8">'.$cliente->correo.'</div>
						</div>';
					}
				}
?>
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
									<th width="200">Vendedor: </th>
									<td class="text-right">
										<select class="form-control" name="vendedor" id="vendedor" style="width:100%;">
											<option value="<?php echo $datav->idusuarios; ?>"><?php echo $datav->nombre; ?></option>
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
									<th width="200">Descuento: (%) </th>
									<td class="text-right">
										<div class="form-group">
											<input type="text" class="form-control input-md text-right descuento" id="descuento" name="descuento" value="<?php echo $data->descuento; ?>" />
										</div>
									</td>
								</tr>
								<tr>
									<th width="200">Total: </th>
									<td class="text-right"> $ <span id="total"> 0.00 </span> pesos</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="line line-dashed line-lg pull-in"></div>

					<input type="hidden" name="preventa" value="<?php echo $data->venta; ?>" />

<?php
						if ($data->venta == "separado"){
							echo '<button name="venta" type="submit" value="finalizada"  class="btn-block btn btn-lg btn-success"><i class="fa fa-check icon"></i> Finalizar Venta</button>';
						} else {
							echo '<button name="venta" type="submit" value="finalizada"  class="btn-block btn btn-lg btn-success"><i class="fa fa-check icon"></i> Modificar Venta</button>';
						}
?>

<?php
						if ($data->venta == "separado"){
							echo '<button name="venta" type="submit" value="separado" class="btn-block btn btn-lg btn-info"><i class="fa fa-clock-o icon"></i> Modificar Separado</button>';
						}
?>
						
					<div class="line line-dashed line-lg pull-in"></div>

					<a href="admin.php?m=pventa" class="btn btn-mf btn-danger btn-block"><i class="fa fa-times icon"></i> Cancelar</a>
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
<?php
						$next  = 1; 
						$query = mysql_query("SELECT * 
							FROM ventas_articulos 
							JOIN articulos ON articulos.idarticulos=ventas_articulos.idarticulo
							WHERE idventa='".$data->idventas."' 
							ORDER BY idva ASC");
						while($q = mysql_fetch_object($query)){
							echo '<tr>
                        		<td>
                        			'.$q->articulo.' - '.$q->descripcion.'
                        			<input type="hidden" name="idarticulo[]" value="'.$q->idarticulo.'">
                        			<input type="hidden" name="precio[]" value="'.$q->precio.'">
                        		</td>
								<td class="text-right v-middle">
									$ <span class="precioArticulo">'.$q->precio.'</span>
								</td>
								<td class="text-right v-middle">
									<input type="text" name="cantidad[]" value="'.$q->cantidad.'" data-precio="'.$q->precio.'" data-oid="'.$next.'" class="form-control cantidad text-right">
								</td>
								<td class="text-right v-middle">$ <span class="totalArticulo" id="total_'.$next.'">'.($q->precio*$q->cantidad).'</span></td>
                        		<td class="text-right"><a href="#" class="btn btn-sm btn-danger clsEliminarFila"> <i class="fa fa-trash-o"></i> </a></td>
                    		</tr>';
                    		$next++;
						}
?>
						</table>						
					</div>
				</div>
			</section>

			<section class="panel panel-default">
				<header class="panel-heading">
					<i class="fa fa-usd icon"></i> Listado de Pagos
				</header>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12 m-b">
							<a href="#" class="agregarPago btn btn-sm btn-success"> <i class="fa fa-usd"></i> Agregar Pago</a>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-striped">
							<tr>
								<th width="120">Fecha</th>
								<th width="120">Hora</th>
								<th width="135">Metodo de Pago</th>
								<th width="150">Cantidad</th>
								<th>Descripcion</th>
								<th width="80"></th>
							</tr>
<?php
						if ( isset($_GET['del']) ){
							$del = mysql_real_escape_string($_GET['del']);
							mysql_query("DELETE FROM ventas_pagos WHERE idpagos='".$del."'");
						}

						$suma = 0;
						$query = mysql_query("SELECT * FROM ventas_pagos WHERE idventa='".$id."'");
						while($q = mysql_fetch_object($query)){
							$suma += $q->cantidad;
?>	
							<tr>
								<td><?php echo $q->fecha; ?></td>
								<td><?php echo $q->hora; ?></td>
								<td class="text-center"><?php echo $q->metodo; ?></td>
								<td class="text-right">$ <?php echo $q->cantidad; ?> pesos</td>
								<td><?php echo $q->comentario; ?></td>
								<td class="text-right">
									<a href="admin.php?m=pventaEditar&id=<?php echo $id; ?>&del=<?php echo $q->idpagos; ?>" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> </a>					
								</td>
							</tr>
<?php
						}
?>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th class="text-left" colspan="2">Total: $ <?php echo $suma; ?> pesos</th>
							</tr>
						</table>						
					</div>
				</div>
			</section>
		</div>
	</div>
</form>
		
<div class="modal fade" id="modal-pagos">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="m-t-none m-b">Agregar pago</h3>
						<form role="form" action="" method="post">
							<input type="hidden" name="idventa" id="idventa" value="" >
							<div class="form-group">
								<div class="row">
									<label class="col-md-6 control-label"><strong>Metodo de Pago</strong></label>
									<div class="col-md-6">
										<select name="metodo" class="form-control">
											<option>Efectivo</option>
											<option>Tarjeta Debido/Credito</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<label class="col-md-6 control-label"><strong>Cantidad</strong></label>
									<div class="col-md-6"><input type="text" class="form-control" name="cantidad2" value="0" ></div>
								</div>
							</div>
							<div class="form-group">
								<label><strong>Comentarios</strong></label>
								<textarea class="form-control" name="comentario" style="height:150px;"></textarea>
							</div>
							<div class="checkbox m-t-lg">
								<a class="btn btn-sm btn-default m-t-n-xs" id="cancelar"> <i class="fa fa-times"></i> <strong>Cancelar</strong></a>
								<button type="submit" class="btn btn-sm pull-right btn-success m-t-n-xs"> <i class="fa fa-usd"></i> <strong>Agregar pago</strong></button>
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

function actualizarCambio(){
	var total = $("#total").html();
	var pago  = $("#pagocon").val();

	var resta = parseInt(pago) - parseInt(total);
	$("#cambio").html(resta);
}
	$(function(){

		$(".agregarPago").click(function(){
			$("#modal-pagos").modal("show");
		});

		$("#cancelar").click(function(){
			$("#modal-pagos").modal("hide");
		});

		actualizarSaldos();
		actualizarCambio();

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
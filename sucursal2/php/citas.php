<?php
if ( isset($_GET['daterange']) ){
	$buscar = mysql_real_escape_string($_GET['daterange']);
	$date = explode(" - ", $buscar);
	$url = "admin.php?m=citas&daterange=".$buscar;
	$consulta  = "SELECT * FROM citas JOIN clientes ON clientes.idclientes=citas.idcliente WHERE fecha BETWEEN '".$date[0]."' AND '".$date[1]."' ORDER BY idcitas DESC";
} else {
	$buscar = date("Y-m-d")." - ".date("Y-m-d");
	$consulta  = "SELECT * FROM citas JOIN clientes ON clientes.idclientes=citas.idcliente WHERE fecha=CURDATE() ORDER BY idcitas DESC";
	$url = "admin.php?m=citas";
}
?>
<section class="panel panel-default pos-rlt clearfix">

	<header class="panel-heading"> <i class="fa fa-calendar"></i> Citas</header>
	
	<div class="row wrapper">
		<div class="col-md-2 m-b-xs">
			<a href="admin.php?m=citasAgregar" class="btn btn-md btn-success"><i class="fa fa-plus"></i> Agregar Cita</a>
		</div>
		<div class="col-md-3">
			<form id="reportesForm" action="" method="get">
				<input type="hidden" value="citas" name="m">
				<div class="input-group m-b">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" id="daterange" class="form-control btn-sm" name="daterange" value="<?php echo $buscar; ?>" />
				</div>
			</form>
		</div>

		

		<div class="col-md-6"></div>

	</div>

	<div class="table-responsive">
		<table class="table table-striped b-t b-light">
			<thead>
				<tr>
					<th width="120">Fecha</th>
					<th width="120">Hora</th>
					<th>Cliente</th>
					<th>Descripcion</th>
					<th width="180"></th>
				</tr>
			</thead>
			<tbody>

<?php
			if ( isset($_GET['del']) ){
				$del = mysql_real_escape_string($_GET['del']);
				mysql_query("DELETE FROM citas WHERE idcitas='".$del."'");
			}


##### PAGINADOR #####
$rows_per_page = 30;

if(isset($_GET['pag']))
	$page = mysql_real_escape_string($_GET['pag']);
else if (@$_GET['pag'] == "0")
	$page = 1;
else 
	$page = 1;

$num_rows 		= mysql_num_rows(mysql_query($consulta));
$lastpage		= ceil($num_rows / $rows_per_page);    		
$page     = (int)$page;
if($page > $lastpage){
	$page = $lastpage;
}
if($page < 1){
	$page = 1;
}
$limit 		= 'LIMIT '. ($page -1) * $rows_per_page . ',' .$rows_per_page;
$consulta  .=" $limit";

$consulta = mysql_query($consulta);
##### PAGINADOR #####

			while($q = mysql_fetch_object($consulta)){ 
?>
				<tr>
					<td><?php echo $q->fecha; ?></td>
					<td><?php echo $q->hora; ?></td>
					<td><?php echo $q->nombre; ?></td>
					<td><?php echo $q->descripcion; ?></td>
					<td class="text-right">
						<a href="admin.php?m=citasEditar&id=<?php echo $q->idcitas; ?>" class="btn btn-sm btn-default"> <i class="fa fa-pencil"></i> </a> &nbsp;
						<a href="admin.php?m=citas&del=<?php echo $q->idcitas; ?>" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> </a>					
					</td>
				</tr>
<?php
			}
?>
			</tbody>
		</table>
	</div>


<footer class="panel-footer">
		<div class="row">
			<div class="col-sm-12 text-right text-center-xs">
				<ul class="pagination pagination-sm m-t-none m-b-none">
<?php
	if($num_rows != 0){
		$nextpage = $page + 1;
		$prevpage = $page - 1;

		if ($page == 1) {
			echo '<li class="disabled"><a href="#"><i class="fa fa-chevron-left"></i></a></li>';
			
			echo '<li class="active"><a href="">1</a></li>';
			
			for($i= $page+1; $i<= $lastpage ; $i++){
				echo '<li><a href="'.$url.'&pag='.$i.'">'.$i.'</a></li> ';
			}

			if($lastpage >$page ){
				echo '<li><a href="'.$url.'&pag='.$nextpage.'"><i class="fa fa-chevron-right"></i></a></li>';
			}else{	
				echo '<li class="disabled"><a href="#"><i class="fa fa-chevron-right"></i></a></li>';
			}
		} else {
			echo '<li><a href="'.$url.'&pag='.$prevpage.'"><i class="fa fa-chevron-left"></i></a></li>';
			
			for($i= 1; $i<= $lastpage ; $i++){
				if($page == $i){
					echo '<li class="active"><a href="#">'.$i.'</a></li>';
				} else {
					echo '<li><a href="'.$url.'&pag='.$i.'">'.$i.'</a></li> ';
				}
			}
         
			if($lastpage >$page ){
				echo '<li><a href="'.$url.'&pag='.$nextpage.'"><i class="fa fa-chevron-right"></i></a></li>';
			} else {
				echo '<li class="disabled"><a href="#"><i class="fa fa-chevron-right"></i></a></li>';
			}
		}
	}
?>
				</ul>
			</div>
		</div>
	</footer>
</section>
<script type="text/javascript">
$(function(){
	$('input[name="daterange"]').daterangepicker({
        	format: 'YYYY-MM-DD',
        	locale: {
            	applyLabel: 'Buscar',
        	    cancelLabel: 'Cancelar',
    	        fromLabel: 'De',
	            toLabel: 'A',
            	customRangeLabel: 'Custom',
        	    daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vie','Sa'],
    	        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        	}
    	});
    	$('#daterange').on('apply.daterangepicker', function(ev, picker) {
  			$("#reportesForm").submit();
		});
});
</script>
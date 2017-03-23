<?php




$errorMsg = "";

if ( isset($_POST['user']) ){
	

	$user = mysql_real_escape_string($_POST['user']);
	$pass = mysql_real_escape_string($_POST['pass']);

	$consulta = "SELECT * FROM usuarios WHERE email='".$user."' AND password='".$pass."' LIMIT 1";
	if ( mysql_num_rows( mysql_query($consulta) )){

		$data 				= mysql_fetch_object(mysql_query($consulta));

		$_SESSION['userId'] = $data->idusuarios;
		$_SESSION['userNm'] = $data->email;
		$_SESSION['userPv'] = $data->privilegio;

		header("Location: admin.php");
	} else {
		$errorMsg = "<div class='row'>
						<div class='wrapper'>
							<div class='alert alert-danger'> <i class='fa fa-times'></i> <b>Usuario o contrase&ntilde;a incorrecta.</b></div>	
						</div>
					</div>";
	}
}

?>

<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head> 
		<meta charset="utf-8" />
		<title>Opctica Bocanegra</title>
		<meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<link rel="stylesheet" href="css/app.v1.css" type="text/css" />
		<!--[if lt IE 9]>
		<script src="js/ie/html5shiv.js"></script>
		<script src="js/ie/respond.min.js"></script>
		<script src="js/ie/excanvas.js"></script>
		<![endif]-->
</head>

<body class="">
	<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
		<div class="container aside-xxl">
			<section class="panel panel-default bg-white m-t-lg">
				<header class="panel-heading text-center">
					<strong>Optica Bocanegra</strong>
				</header>
				<form action="" class="panel-body wrapper-lg" >
					<div class="form-group">
						<a href="sucursal.php" role="button" class="btn btn-block btn-primary">Sucursal 1</a> 
					</div>
					
					<a href="sucursal.php?sucursal=2" role="button" class="btn btn-block btn-primary">Sucursal 2</a>

					<?php echo $errorMsg; ?>
				</form>
			</section>
		</div>
	</section>
	<!-- footer -->
	<footer id="footer">
		<div class="text-center padder">
			<p> <small><a href="http://nuevolaredo.f403.mx">F403.MX Nuevo Laredo</a></small> </p>
		</div>
	</footer>
	<!-- / footer -->

	<!-- Bootstrap -->
	<!-- App -->
	<script src="js/app.v1.js"></script>
	<script src="js/app.plugin.js"></script>
</body>
</html>
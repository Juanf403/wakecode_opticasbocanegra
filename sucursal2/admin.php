<?php
@session_start();

if ( !isset($_SESSION['userId']) ){
	header("Location: ./index.php");
	die;
}

/* 
Role: username / password
Admin: admin@tecdiary.com / 12345678
Sales staff: sales@tecdiary.com / 12345678

Puntos de Ventas
https://demo.phppointofsale.com/index.php/login
http://demo.pointofsales.bryantan.info/user/login
https://github.com/jimwins/scat

http://spos.tecdiary.org/login
Role: username / password
Admin: admin@tecdiary.com / 12345678
Sales staff: sales@tecdiary.com / 12345678

Pendientes
* control de stock detalle
* alertas cuando queden pocos productos ( en venta tambien o disable)
* venta por cada vendedor
* hay dos tipos de inventario
* las micas deben de pedir y reportes mensuales para ver que se capturo o que hace falta
* borrar signos como $ o letras como pesos en los campos 
* graficas de reportes
* modulo de configuracion para nombre del sistema
* registro de logs
*/

include 'db.php';

if ( isset($_GET['m']) ){
	switch($_GET['m']) {

		/* clientes */
		case "clientes":
			$paginaPHP = "php/clientes.php";
		break;
		case "clientesAgregar":
			$paginaPHP = "php/clientesAgregar.php";
		break;
		case "clientesEditar":
			$paginaPHP = "php/clientesEditar.php";
		break;

		case "clientePagos":
			$paginaPHP = "php/clientePagos.php";
		break;
		case "clienteCitas":
			$paginaPHP = "php/clienteCitas.php";
		break;

		/* citas */
		case "citas":
			$paginaPHP = "php/citas.php";
		break;
		case "citasAgregar":
			$paginaPHP = "php/citasAgregar.php";
		break;
		case "citasEditar":
			$paginaPHP = "php/citasEditar.php";
		break;

		/* inventario */
		case "inventario":
			$paginaPHP = "php/inventario.php";
		break;
		case "inventario2":
			$paginaPHP = "php/inventario.php";
		break;
		case "inventarioAgregar":
			$paginaPHP = "php/inventarioAgregar.php";
		break;
		case "inventarioEditar":
			$paginaPHP = "php/inventarioEditar.php";
		break;

		/* punto de venta */
		case "pventa":
			$paginaPHP = "php/pventa.php";
		break;
		case "pventaAgregar":
			$paginaPHP = "php/pventaAgregar.php";
		break;
		case "pventaEditar":
			$paginaPHP = "php/pventaEditar.php";
		break;

		/* liquidar */
		case "liquidar":
			$paginaPHP = "php/liquidar.php";
		break;

		/* categorias */
		case "categorias":
			$paginaPHP = "php/categorias.php";
		break;
		case "categoriasAgregar":
			$paginaPHP = "php/categoriasAgregar.php";
		break;
		case "categoriasEditar":
			$paginaPHP = "php/categoriasEditar.php";
		break;

		/* ingresos */ 
		case "ingresos":
			$paginaPHP = "php/ingresos.php";
		break;

		/* reportes */
		case "reportes":
			$paginaPHP = "php/reportes.php";
		break;
		case "reportesart":
			$paginaPHP = "php/reportesart.php";
		break;
		case "reportescats":
			$paginaPHP = "php/reportescat.php";
		break;
		case "vendedor":
			$paginaPHP = "php/vendedor.php";
		break;


		/* usuarios */
		case "usuarios":
			$paginaPHP = "php/usuarios.php";
		break;
		case "usuariosAgregar":
			$paginaPHP = "php/usuariosAgregar.php";
		break;
		case "usuariosEditar":
			$paginaPHP = "php/usuariosEditar.php";
		break;
	}
} else {
		$paginaPHP = "php/index.php";
}

$errorMsg = "";

?>
<!DOCTYPE html>
<html lang="en" class="app">
<head> <meta charset="utf-8" />
<title>Optica Bocanegra</title>
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="css/app.v1.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/select2.min.css">
<link rel="stylesheet" type="text/css" href="css/datepicker.css">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
<script src="js/app.v1.js"></script>
<!--[if lt IE 9]>
<script src="js/ie/html5shiv.js"></script>
<script src="js/ie/respond.min.js"></script>
<script src="js/ie/excanvas.js"></script>
<![endif]-->
</head>
<body class="">
	<section class="vbox">
		<header class=" dk header navbar navbar-fixed-top-xs" style="background:orange;">
			<div class="navbar-header aside-md">
				<a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html"> <i class="fa fa-bars"></i> </a>
				<a href="#" class="navbar-brand" data-toggle="fullscreen" style="color:white;">Optica Bocanegra</a>
				<a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user"> <i class="fa fa-cog"></i> </a>
			</div>
			<ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:white;"> <span class="thumb-sm avatar pull-left"> <img src="images/avatar_default.jpg"> </span > admin <b class="caret"></b> </a>
					<ul class="dropdown-menu animated fadeInRight"> <span class="arrow top"></span> 
						<li class="divider"></li>
						<li> <a href="cerrar.php">Salir</a> </li>
					</ul>
				</li>
			</ul>
		</header>
		<section>
			<section class="hbox stretch">
				<!-- .aside -->
				<aside class="bg-black lter aside-md hidden-print hidden-xs <?php if ( @$_GET['m'] == "pventaAgregar" ) echo "nav-xs"; ?> <?php if (@$_GET['m'] == "pventaEditar") echo 'nav-xs'; ?>" id="nav">
					<section class="vbox">
						<section class="w-f scrollable">
							<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
								<!-- nav -->
								<nav class="nav-primary hidden-xs">
									<ul class="nav">
										<li><a href="admin.php"> <i class="fa fa-home"></i> <span>Inicio</span> </a> </li>
										<li><a href="admin.php?m=clientes"> <i class="fa fa-users"></i> <span>Clientes</span> </a> </li>
										<li><a href="admin.php?m=pventaAgregar"> <i class="fa fa-barcode"></i> <span>POS</span> </a> </li>
										<li><a href="admin.php?m=citas"> <i class="fa fa-calendar"></i> <span>Citas</span> </a> </li>
										<?php
										if ($_SESSION['userPv'] != "3"){
											?>
										<li><a href="admin.php?m=inventario"><i class="fa fa-tag"></i><span>Inventario</span></a></li>
										<?php
												}
										?>
										<li><a href="admin.php?m=pventa"><i class="fa fa-shopping-cart"></i><span>Historial de Venta</span></a></li>
										<?php
										if ($_SESSION['userPv'] != "3"){
											?>
										<li class="">
											<a href="#webpage" class=""><i class="fa fa-bar-chart-o icon"></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>Reportes</span> </a>
											<ul class="nav lt" style="display: none;">
												<li> <a href="admin.php?m=ingresos"> <i class="fa fa-usd"></i> <span>Ingresos</span> </a> </li>
												<li> <a href="admin.php?m=reportes&estado=1"> <i class="fa fa-check"></i> <span>Liquidados</span> </a> </li>
												<li> <a href="admin.php?m=reportes&estado=0"> <i class="fa fa-exclamation"></i> <span>Pendientes</span> </a> </li>
												<li> <a href="admin.php?m=reportescats"> <i class="fa fa-list"></i> <span>Categorias</span> </a> </li>
												<li> <a href="admin.php?m=vendedor"> <i class="fa fa-user"></i> <span>Por Vendedor</span> </a> </li>
											</ul>
										</li>
										<li class="">
											<a href="#webpage" class=""><i class="fa fa-cog icon"></i> <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i> </span> <span>Config</span> </a>
											<ul class="nav lt" style="display: none;">
												<li> <a href="admin.php?m=categorias"> <i class="fa fa-list"></i> <span>Categorias</span> </a> </li>
												<li> <a href="admin.php?m=usuarios"> <i class="fa fa-users"></i> <span>Usuarios</span> </a> </li>
											</ul>
										</li>
										<?php
												}
										?>
									</ul>
								</nav>
								<!-- / nav -->
							</div>
						</section>
					</section>
				</aside>
				<!-- /.aside -->
				<section id="content">
					<section class="vbox">
						<!--<header class="header bg-white b-b b-light"> <p>Layout with black color</p> </header>-->
						<section class="scrollable wrapper w-f">
							<?php include $paginaPHP; ?>
						</section>
						<footer class="footer bg-white b-t b-light text-right">
							<p><a href=""></a></p>
						</footer>
					</section>
					<a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" data-target="#nav,html"></a>
				</section>
			</section>
		</section>
	</section>
	<!-- Bootstrap -->
	<!-- App -->
	<script src="js/app.plugin.js"></script>
	<script type="text/javascript" src="js/select2.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
	<!-- daterangepicker -->
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/2.9.0/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
</body>
</html>
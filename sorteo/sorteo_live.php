<?php session_start();?>
<!DOCTYPE html>
<html lang="es">
<head>
	<?php 
		include "../pages/getProperty.php"; 
		include "../pages/conexion_bd.php";
	?>
	
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="../favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="../favicon.png" type="image/png"/>
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/limb.css" rel="stylesheet">

	<title><?php echo getPropiedad("titulo_head"); ?></title>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
	<?php		
		require('SingletonDataSorteoLive.php');
		$instancia = SingletonDataSorteoLive::getInstance();

		
		if ($_GET['operacion']=='inicio'){		
			$instancia->start();
		}

		if ($_GET['operacion']=='sortea'){			
			$instancia->sortea();
		}

		if ($_GET['operacion']=='fin'){
			$instancia->fin();
		}
	?>
</head>
<body>
	<!--Menu-->
	<?php 
		$page="sorteo"; 
		include "../pages/menu.php"; 
	?>

    <div class="container">    			
		<h2>Sorteo</h2>
		<?php
			if ($_SESSION['is_admin']) { 
		?>
			<div class="h2">
				<button type="button" class="btn btn-success" style="display:none;" id="btn_inicia" onclick="$.ajax( {'url':'sorteo_live.php?operacion=inicio','type': 'GET'});">
					<span class="glyphicon glyphicon-off"></span> Inicia Sorteo
				</button>
				<button type="button" class="btn btn-info" style="display:none;" id="btn_sortea" onclick="$.ajax( {'url':'sorteo_live.php?operacion=sortea','type': 'GET'});">
					<span class="glyphicon glyphicon-repeat"></span> Sortea
				</button>
				<button type="button" class="btn btn-danger" style="display:none;" id="btn_finaliza" onclick="$.ajax( {'url':'sorteo_live.php?operacion=fin','type': 'GET'});">
					<span class="glyphicon glyphicon-off"></span> Finaliza Sorteo
				</button>
			</div>
		<?php 
			}		
			require('sectionSorteo.php'); 
		?>
	
	</div>

	
 	<script src="../bootstrap/js/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<?php 
	include_once("validateAdmin.php");	
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<? include "../../pages/getProperty.php"; ?>
	
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="../../favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="../../favicon.png" type="image/png"/>
	<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<title><? echo getPropiedad("titulo_head"); ?></title>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
</head>
<body> 
	<!--Menu-->
	<?php 
		$page="admin"; 
		include "../../pages/menu.php"; 
	?>

	<div class="container">
		<form role="form">
			<h2 class="text-center">Mantenimiento de Partidos</h2>
			<div class="form-group text-center">
				<a href="addPartido.php" class="btn btn-lg btn-info">Añadir Partido</a>
			</div>
			<div class="form-group text-center">
				<a href="selectEditPartido.php" class="btn btn-lg btn-info">Editar Partido</a>
			</div>
			<div class="form-group text-center">
				<a href="actualizarApuestas.php" class="btn btn-lg btn-info">Actualizar Apuestas</a>
			</div>
			<div class="form-group text-center">
				<a href="configuracion.php" class="btn btn-lg btn-info">Configuración</a>
			</div>
		</form>
		</div>
	</div>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>



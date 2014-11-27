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
	<link href="../../datepicker/css/datepicker.css" rel="stylesheet">
	
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

	<div class="container col-sm-6 col-sm-offset-3">
		<form role="form" class="center-block"  action="../dao/insertPartido.php">
			<h2 class="text-center">Nuevo Partido
				<a href="./mantenimientoPartidos.php" class="btn btn-info btn-sm" >
 		 			<span class="glyphicon glyphicon-arrow-left"></span>
				</a>
			</h2>
			<div class="form-group">
			    <label for="fecha">Fecha</label>
			    <input type="date" class="form-control text-center" name="fecha" id="fecha" placeholder="yyyy-mm-dd" pattern="\d{4}-\d{2}-\d{2}" required>
			</div>
			<div class="form-group">
			    <label for="hora">Hora</label>
			    <input type="time" class="form-control text-center" name="hora" id="hora" placeholder="HH:MM:SS" value="22:00:00" pattern="\d{2}:\d{2}:\d{2}" required>
			</div>
			
			<div class="form-group">
			    <label>Fase del torneo</label>
				<?php echo getComboFases('fase',-1); ?>
			</div>
			
			<div class="form-group">
			    <label>Equipo Local</label>
			    <?php echo getComboEquipos('local',-1); ?>
			</div>
			<div class="form-group">
			    <label>Equipo Visitante</label>
    			<?php echo getComboEquipos('visitante',-1); ?>
			</div>
			<div class="form-group text-center">
				<button type="submit" class="btn btn-success">Guardar</button>
			</div>
		</form>
		</div>
	</div>
	
			
<script src="../../bootstrap/js/jquery.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>


</body>
</html>



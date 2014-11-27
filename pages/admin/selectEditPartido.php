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
	<link href="../../css/limb.css" rel="stylesheet">
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
		<div class="h2 row">
		  	<div class=" col-md-4 col-sm-5 col-sm-offset-3 col-xs-8 col-xs-offset-0 text-right">Seleccionar Partido</div>
		  	<div class="col-sm-1 col-xs-4 text-left">
				<a href="./mantenimientoPartidos.php" class="btn btn-info btn-sm">
		 			<span class="glyphicon glyphicon-arrow-left"></span>
				</a>	
			</div>
		</div>

		<div class="row">
			<div class="list-group col-sm-4 col-sm-offset-4 col-xs-6 col-xs-offset-3">
				<select onchange="location = this.options[this.selectedIndex].value;" class="form-control">
					<option>Fechas...</option>
					<? 
						//Ejecutamos la sentencia SQL
						$result=mysql_query("select distinct fecha from partidos order by fecha asc");
						while ($row=mysql_fetch_array($result)) {
							$date = date_create($row["fecha"]);
						  echo '<option value=selectEditPartido.php?fecha='.$row["fecha"].'>'.date_format($date, 'd/m/Y');
						  echo '</option>';
						}mysql_free_result($result);
					?>
				</select>
			</div>
		</div>
		<div class="list-group text-center col-sm-6 col-sm-offset-3">
			<?php 
				if(!isset($_GET["fecha"])){
					$hoy = new DateTime();
					$fecha_aux=$hoy->format('Y-m-d');
				}else{
					$fecha_aux=$_GET["fecha"];
				}
				echo getListaPartidosEditar($fecha_aux); 
		   	?>
		</div>
	</div>
	
	<div class="container text-center">
		<a href="./mantenimientoPartidos.php" class="btn btn-info">Volver</a>
	</div>
			
	<?php  //TRATAMIENTO DE ERRORES
			if(isset($_GET["error"]) || isset($_POST["error"])){
		?>
			<div class="error">
				<h4>Error SQL:</h4>
				<p style="color: #000;">
					<?php echo $_GET["error"]; ?>
					<?php echo $_POST["error"]; ?>
				</p>
			</div>
		<?php
			}
		?>		
<script src="../../bootstrap/js/jquery.min.js"></script>
<script src="../../datepicker/js/bootstrap-datepicker.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>

</body>
</html>



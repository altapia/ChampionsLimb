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

	<div class="container">
		<h2 class="text-center">Actualizar Apuestas
			<a href="./mantenimientoPartidos.php" class="btn btn-info btn-sm" >
 		 		<span class="glyphicon glyphicon-arrow-left"></span>
			</a>	
		</h2>
		
		<div class="list-group center-block text-center" style="max-width: 330px">
			<select onchange="location = this.options[this.selectedIndex].value;" class="form-control">
				<option>Fechas...</option>
				<? 
					//Ejecutamos la sentencia SQL
					$result=mysql_query("select distinct fecha from partidos order by fecha asc");
					while ($row=mysql_fetch_array($result)) {
						$date = date_create($row["fecha"]);
					  echo '<option value=actualizarApuestas.php?fecha='.$row["fecha"].'>'.date_format($date, 'd/m/Y');
					  echo '</option>';
					}mysql_free_result($result);
				?>
			</select>
		</div>
	</div>
	
	<div class="list-group col-sm-6 col-sm-offset-3">
		<?php
			if(!isset($_GET["fecha"])){
				$hoy = new DateTime();
				
				$fecha_aux=$hoy->format('Y-m-d');
				$date = date_create($fecha_aux);
			}else{
				$fecha_aux=$_GET["fecha"];
				$date = date_create($fecha_aux);
				
			}
			echo '<h4>Apuestas del '.date_format($date, 'd/m/Y').' </h4>';
                
            echo getApuestasParaUpdate($fecha_aux);
		?>
	</div>
	
	<div class="container text-center col-sm-6 col-sm-offset-3">
		<a href="./mantenimientoPartidos.php" class="btn btn-info">Volver</a>
	</div>
			
<script src="../../bootstrap/js/jquery.min.js"></script>
<script src="../../datepicker/js/bootstrap-datepicker.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script>
	function actualizarApuesta(id,estado){
				if(estado==1){
					iconoApuesta= '<span class="glyphicon glyphicon-ok"></span> ';						
				}else {
					iconoApuesta= '<span class="glyphicon glyphicon-remove"></span> ';				
				}
			
				var parametros = {
					"operacion" : "actualizarApuesta",
					"estado" : estado,
					"id" : id
				};
				$.ajax({
					data:  parametros,
					url:   '../dao/operaciones.php',
					type:  'post',
					async: 'false',
					contentType: "application/x-www-form-urlencoded; charset=ISO-8859-1",
					beforeSend: function () {						
						$('#acciones_'+id).html('<img src="../../images/ajax-loader.gif"/>');
					},
					success:  function (response) {
						//alert(response);
						$('#acciones_'+id).html(iconoApuesta);							
					},
					complete: function(){
						
					}
        		});				
			}
</script>

</body>
</html>



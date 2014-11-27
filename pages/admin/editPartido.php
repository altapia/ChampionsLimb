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
	
	<style type="text/css">
		.error{
		    background-color: #fdf7f7;  
		    border-color: #d9534f;
		    border-left: 3px solid #d9534f;
		    color: #d9534f;
		    padding: 5px;
			margin-top: 20px;
			margin-bottom: 20px;
			margin-left: 10px;
			margin-right: 10px;
		}
	</style>

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

	<div class="container text-center">
		<h2 class="text-center">			
			Editar Partido
			<a href="./selectEditPartido.php" class="btn btn-info btn-sm">
				<span class="glyphicon glyphicon-arrow-left"></span>
			</a>
		</h2>
		<div >
			<button type="button" class="btn btn-default btn-sm" 
				onclick="$('#div_datos').toggle();$('#div_update').toggle();$('#btn_insertAp').toggle();$('#div_apuestas').toggle();">
					<span class="glyphicon glyphicon-pencil"></span>
			</button>
			<button type="button" class="btn btn-default btn-sm" onclick="borrarPartido();">
					<span class="glyphicon glyphicon-trash"></span>
			</button>

		</div>
	</div>
	
	<div class="container text-center" id="div_datos">
		<?php //TITULO PARTIDO CON FECHA Y HORA
			if(isset($_GET["id"])){
				$id= $_GET["id"]; 
				
				$result=mysql_query("
					SELECT p.ID as id, p.FECHA as fecha, p.HORA as hora ,p.local as idLocal, e.NOMBRE as local, e.ESCUDO as escLocal,p.visitante as idVisit,  f.NOMBRE as visit, f.ESCUDO as escVisit, p.fase
					FROM partidos p
						LEFT JOIN equipos e ON e.ID=p.LOCAL
						LEFT JOIN equipos f ON f.ID=p.VISITANTE
					WHERE p.ID=".$id);
	
				if(!$result){
	  				die('Error: ' . mysql_error());
	 	 		}else{
	 	 			$row=mysql_fetch_array($result);	 	 			
					$date = date_create($row["fecha"]);

				?>
					<div class="row h3">
						<div class="col-sm-4 col-xs-12"><?php echo $row["local"];?></div>
					  	<div class="col-sm-1 col-xs-4"><img src='../../images/escudos/<?php echo $row["escLocal"];?>'></div>
					  	<div class="col-sm-2 col-xs-4"><small><?php echo date_format($date, 'd/m/Y').' '.substr($row["hora"],0,5);?></small></div>
					  	<div class="col-sm-1 col-xs-4"><img src='../../images/escudos/<?php echo $row["escVisit"];?>'> </div>
					  	<div class="col-sm-4 col-xs-12"><?php echo $row["visit"];?></div>
					</div>

					<h4>
						<?php echo getListaApostantes($id); ?>
					</h4>
				<?php
				}
			}
		?>
	</div>
	
	
	<div class="container text-center col-sm-6 col-sm-offset-3" id="div_update" style="display: none;">
		<form action="../dao/operaciones.php" method="post">
			<div class="form-group">
			    <label>Equipo Local</label>
			    <?php echo getComboEquipos('local',$row["idLocal"]); ?>
			</div>
			<div class="form-group">
			    <label>Equipo Visitante</label>
    			<?php echo getComboEquipos('visitante',$row["idVisit"]); ?>
			</div>
			<div class="form-group">
			    <label for="fecha">Fecha</label>
			    <input type="text" class="form-control text-center" name="fecha" id="fecha" value="<?php echo $row["fecha"];?>" 
			    	placeholder="yyyy-mm-dd" pattern="\d{4}-\d{2}-\d{2}" required>
			</div>
			<div class="form-group">
			    <label for="hora">Hora</label>
			    <input type="text" class="form-control text-center" name="hora" id="hora" value="<?php echo $row["hora"];?>"
			    	placeholder="HH:MM:SS" pattern="\d{2}:\d{2}:\d{2}" required>
			</div>
			
			<div class="form-group">
			    <label>Fase del torneo</label>
				<?php echo getComboFases('fase', $row["fase"]); ?>
			</div>
			<div class="form-group">
				<?php echo getCheckboxApostantes($id); ?>
			</div>
			
			<input type="hidden" name="operacion" value="updatePartido" />
			<input type="hidden" name="id" value="<?php echo $id; ?>"/>
			<input type="hidden" name="redirect" value="../admin/editPartido.php?id=<?php echo $id;?>"/>
			
			<input type="submit" class="btn btn-primary" value="Actualizar"/>
		</form>
	</div>
	
	<div class="container text-center" id="div_apuestas">
		<div class="list-group text-center col-sm-6 col-sm-offset-3">
		<?php //LISTA DE APUESTAS
			if(isset($_GET["id"])){
				$id= $_GET["id"]; 
				
				$result=mysql_query("
					SELECT a.id, a.partido, a.apuesta, a.apostado, a.acertada, a.cotizacion ,apost.nombre
					FROM apuestas a
						LEFT JOIN partido_apostante pa ON pa.idpartido=a.PARTIDO
						INNER JOIN partido_apost_apuesta pap ON pap.idpartidoapost=pa.ID AND pap.idapuesta=a.ID
						LEFT JOIN apostantes apost ON apost.id=pa.idapostante
					WHERE a.PARTIDO=".$id);
	
				if(!$result){
	  				die('Error: ' . mysql_error());
	 	 		}else{
	 	 			while ($row=mysql_fetch_array($result)) {
						$color='';
						switch ($row["acertada"]) {
							case 1:
								$color=' list-group-item-success';
								break;
							case 2:
								$color=' list-group-item-danger';
								break;
						}
						echo '<a href="./editApuesta.php?id='.$row["id"].'" class="list-group-item'.$color.'">';
						echo '<h5 class="list-group-item-heading">'.$row["apostado"].'&euro; ';
						if($row["cotizacion"]!=null){
							echo ' @'.$row["cotizacion"].' = '.($row["apostado"]*$row["cotizacion"]).'&euro;';
						}
						echo '</h5>';
						echo '<p class="list-group-item-text">'.$row["apuesta"].'<br>';
						echo '<small><strong>'.$row["nombre"].'</strong></small></p>';
						echo '</a>';
					}
				}
				
				//INSERT APUESTA
				?>
				
				</div>
				
				
				<div class="list-group text-center col-sm-6 col-sm-offset-3" >
				<button type="button"id="btn_insertAp" class="btn btn-primary" onclick="$('#btn_insertAp').hide();$('#form_insertAp').show();">Insertar nueva apuesta</button>
				
				<form role="form" class="form-horizontal center-block" style="display:none" action="../dao/insertApuesta.php" id="form_insertAp">			
					<div class="panel panel-primary">
  						<div class="panel-heading">
   							<h3 class="panel-title">Insertar nueva apuesta</h3>
  						</div>
  						<div class="panel-body">
							<div class="form-group">
							    <label for="apuesta">Apuesta</label>
							    <textarea class="form-control" name="apuesta" id="apuesta" required></textarea>
							</div>
							<div class="form-group">
							    <label class="col-sm-4 control-label" for="apostado">Apostado</label>
							    <div class="col-sm-8">
							    	<input type="number" class="form-control" name="apostado" id="apostado" required step="0.01">
							    </div>
							</div>
							<div class="form-group">
							    <label class="col-sm-4 control-label" for="cotizacion">Cotización</label>
							    <div class="col-sm-8">
							    	<input type="number" class="form-control" name="cotizacion" id="cotizacion" step="0.01">
							    </div>
							</div>
							
							<div class="form-group">
							   <label class="col-sm-4 control-label">Apostante</label>
							   <div class="col-sm-8">
									<?php echo getComboApostantes($id,'apostante',null); ?>
								</div>
							</div>
							<input type="hidden" name="partido" value="<?php echo $id; ?>"
							<div class="form-group center-block ">
								<button type="submit" class="btn btn-success">Insertar apuesta</button>
							</div>
						</div>
					</div>
				</form>
				
				<?php
			}
		 
		?>
			
		</div>
	</div>
	
	
	<div class="container text-center col-sm-6 col-sm-offset-3" style="margin-top: 5px;margin-bottom: 5px;">
		<a href="./selectEditPartido.php" class="btn btn-info">
			<span class="glyphicon glyphicon-arrow-left"></span> Volver
		</a>
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
	
	<!-- FORMULARIO PARA BORRAR EL PARTIDO-->
	<form id="formBorrar" action="../dao/operaciones.php" method="post">
		<input type="hidden" name="operacion" value="borrarPartido"/>
		<input type="hidden" name="id" value="<?php echo $id; ?>"/>
		<input type="hidden" name="redirect" value="../admin/selectEditPartido.php"/>
	</form>		
	
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="../../datepicker/js/bootstrap-datepicker.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<script>
	$('#fecha').datepicker({autoclose:true});

	function borrarPartido(){
		if(confirm('¿Borrar partido?')){
			$('#formBorrar').submit();
		}
	}
	
	function editPartido(){
		
	}
	
</script>

</body>
</html>



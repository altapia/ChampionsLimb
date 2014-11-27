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

	<div class="container text-center">
		
		<?php //TITULO PARTIDO CON FECHA Y HORA
			$idPartido=null;
			if(isset($_GET["id"])){
				$id= $_GET["id"]; 
				
				$result=mysql_query("
					SELECT p.ID as idPartido, p.FECHA as fecha, p.HORA as hora ,e.NOMBRE as local, e.ESCUDO as escLocal, f.NOMBRE as visit, f.ESCUDO as escVisit
					FROM partidos p
						LEFT JOIN equipos e ON e.ID=p.LOCAL
						LEFT JOIN equipos f ON f.ID=p.VISITANTE
						INNER JOIN apuestas a ON a.partido=p.id
					WHERE a.ID=".$id);
	
				if(!$result){
	  				die('Error: ' . mysql_error());
	 	 		}else{
	 	 			$row=mysql_fetch_array($result);
	 	 			$idPartido=$row["idPartido"];
				?>
					<h2 class="text-center">Editar Apuesta 
						<a href="./editPartido.php?id=<?php echo $idPartido; ?>" class="btn btn-info btn-sm" >
 		 					<span class="glyphicon glyphicon-arrow-left"></span>
						</a>
					</h2>
					
				<?php
	 	 			
	 	 			echo '<h3>'.$row["local"].' <img src="../../images/escudos/'.$row["escLocal"].'"> vs <img src="../../images/escudos/'.$row["escVisit"].'"> '.$row["visit"];
					$date = date_create($row["fecha"]);
					echo '<br><small>'.date_format($date, 'd/m/Y').' '.substr($row["hora"],0,5).'</small></h3>';
				}
			}
		?>
	</div>
	
	<div class="container text-center">
		<div class="list-group center-block text-center" style="max-width: 330px">
		<?php //APUESTA
			if(isset($_GET["id"])){
				$id= $_GET["id"]; 
				
				$result=mysql_query("
					SELECT a.id, a.partido, a.apuesta, a.apostado, a.acertada, a.cotizacion ,apost.nombre, apost.id as idApostante
					FROM apuestas a
						LEFT JOIN partido_apostante pa ON pa.idpartido=a.PARTIDO
						INNER JOIN partido_apost_apuesta pap ON pap.idpartidoapost=pa.ID AND pap.idapuesta=a.ID
						LEFT JOIN apostantes apost ON apost.id=pa.idapostante
					WHERE a.ID=".$id);
	
				if(!$result){
	  				die('Error: ' . mysql_error());
	 	 		}else{
	 	 			$row=mysql_fetch_array($result);
					
					?>
					
					<form role="form" class="form-horizontal center-block" style="max-width: 330px" action="../dao/updateApuesta.php">			
						<div class="form-group">
						    <label for="apuesta">Apuesta</label>
						    <textarea class="form-control" name="apuesta" id="apuesta" required><?php echo $row["apuesta"];?></textarea>
						</div>
						<div class="form-group">
						    <label class="col-sm-4 control-label" for="apostado">Apostado</label>
						    <div class="col-sm-8">
						    	<input type="number" class="form-control" name="apostado" id="apostado" value="<?php echo $row["apostado"];?>" required step="0.01">
						    </div>
						</div>
						<div class="form-group">
						    <label class="col-sm-4 control-label" for="cotizacion">Cotización</label>
						    <div class="col-sm-8">
						    	<input type="number" class="form-control" name="cotizacion" id="cotizacion" value="<?php echo $row["cotizacion"];?>" step="0.01">
						    </div>
						</div>
						
						<div class="form-group">
						   <label class="col-sm-4 control-label">Apostante</label>
						   <div class="col-sm-8">
								<?php echo getComboApostantes($row["partido"],'apostante', $row["idApostante"]); ?>
							</div>
						</div>
						
						<div class="form-group">
						   <label class="control-label">Estado de la apuesta:</label>
						   <div class="radio text-left">
							  <label>
							    <input type="radio" name="acertada" id="optionsRadios1" value="0" <?php if($row["acertada"]==0) echo 'checked';?> >
							    Por determinar
							  </label>
							</div>
							<div class="radio text-left">
							  <label>
							    <input type="radio" name="acertada" id="optionsRadios2" value="1" <?php if($row["acertada"]==1) echo 'checked';?>>
							    Acertada
							  </label>
							</div>
							<div class="radio text-left">
							  <label>
							    <input type="radio" name="acertada" id="optionsRadios2" value="2 <?php if($row["acertada"]==2) echo 'checked';?>">
							    Fallada
							  </label>
							</div>
						</div>
						
						<input type="hidden" name="partido" value="<?php echo $idPartido; ?>"/>
						<input type="hidden" name="id" value="<?php echo $row["id"]; ?>"/>
						<div class="form-group center-block ">
							<button type="submit" class="btn btn-success">Actualizar apuesta</button>
						</div>
					</form>
					
					<form role="form" class="form-horizontal center-block" style="max-width: 330px" action="../dao/deleteApuesta.php">	
						<input type="hidden" name="id" value="<?php echo $row["id"]; ?>"/>
						<input type="hidden" name="partido" value="<?php echo $idPartido; ?>"/>
						<div class="form-group center-block ">
							<button type="submit" class="btn btn-danger">Borrar apuesta</button>
						</div>
					</form>
					<?php
					
				}
			}
		 
		?>
			
		</div>
	</div>
	
	<div class="container text-center">
		<a href="./editPartido.php?id=<?php echo $idPartido; ?>" class="btn btn-info">Volver</a>
	</div>
			
<script src="../../bootstrap/js/jquery.min.js"></script>
<script src="../../datepicker/js/bootstrap-datepicker.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>

</body>
</html>



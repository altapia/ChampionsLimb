<?php 
	include_once 'validateConfig.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<? include "../../getProperty.php"; ?>
	
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="../../../favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="../../../favicon.png" type="image/png"/>
	<link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../../css/limb.css" rel="stylesheet">

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
		$page="config"; 
		include "../../menu.php"; 
	?>

	<div class="container">
		<h2 class="text-center">Usuarios
			<a href="../configuracion.php" class="btn btn-info btn-sm" >
 		 		<span class="glyphicon glyphicon-arrow-left"></span>
			</a>	
		</h2>

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

		<div class="col-sm-12 hidden-xs">			
			<div class="col-md-2 col-sm-2">    
				<label>Nombre</label>
			</div>
			<div class="col-md-4 col-sm-2">
				<label>Password</label>
			</div>
			<div class="col-md-1 col-sm-1">
				<label>Admin</label>
			</div>
			<div class="col-md-4 col-sm-2">
				<label>E-Mail</label>
			</div>		
		</div>

		<?php //TITULO PARTIDO CON FECHA Y HORA				
			$result=mysql_query("
				SELECT 	 username, password_sha1, userid, admin, email FROM user ORDER BY username ASC");

			if(!$result){
  				die('Error: ' . mysql_error());
 	 		}else{
 	 			while ($row=mysql_fetch_array($result)) {
	 			?>

					<form class="col-sm-12" role="form" action="../../dao/operaciones.php" method="POST" id="form_<?php echo $row['userid'];?>">
						<input type="hidden" name="userid" value='<?php echo $row["userid"];?>'/>
						<input type="hidden" name="operacion" value="actualizarUser"/>
						<div class="form-group col-md-2 col-sm-2">    
							<label class="visible-xs">Nombre</label>
							<input type="text" class="form-control" name="username" value='<?php echo $row["username"];?>'>
						</div>
						<div class="form-group col-md-4 col-sm-2">
							<label class="visible-xs">Password</label>
							<input type="text" class="form-control" name="password_sha1" value='<?php echo $row["password_sha1"];?>'>
						</div>
						<div class="form-group col-md-1 col-sm-2">
							<label class="visible-xs">Admin</label>
							<input type="text" class="form-control" name="admin" value='<?php echo $row["admin"];?>'>
						</div>
						<div class="form-group col-md-4 col-sm-2">
							<label class="visible-xs">E-Mail</label>
							<input type="text" class="form-control" name="email" value='<?php echo $row["email"];?>'>
						</div>					
						<div class="form-group col-md-1 col-sm-1 text-center">
							<button type="submit" class="btn btn-primary" onclick="enviarForm(<?php echo $row['id'];?>)">Actualizar <span class="glyphicon glyphicon-save"></span></button>
						</div>
					</form>
	 			
				<?php
 	 			}
 	 		}
		?>
			
	</div>

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../../../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function enviarForm(id){
			$('#form_'+id).submit();
		}
	</script>
</body>
</html>
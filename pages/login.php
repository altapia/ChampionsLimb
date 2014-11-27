<?php session_start();
  if ($_SESSION['signed_in']) {
    header("Location: ./misapuestas.php");
  }
  
  include "getProperty.php"; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
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
    
   
    
     <script language="javascript">
	 
	 	var plegadoTodo=0;
		function desplegarTodos(){
			
			if(plegadoTodo==0){
				plegadoTodo=1;
				$('#btn_desplegar').html('<i class="icon-minus icon-white"></i> Plegar todos');	
				$(".collapse").collapse("show");  
				$('i[name|="icono_toggle"]').attr("class","icon-minus icon-white");	
			}else{
				plegadoTodo=0;
				$('#btn_desplegar').html('<i class="icon-plus icon-white"></i> Desplegar todos');	
				$(".collapse").collapse("hide"); 
				$('i[name|="icono_toggle"]').attr("class","icon-plus icon-white");	
			}
		}
		
		
		function cambiarIco(i){
			if($('#ico'+i).attr("class")=="icon-plus icon-white"){
				$('#ico'+i).attr("class","icon-minus icon-white");
			}else{
				$('#ico'+i).attr("class","icon-plus icon-white");
			}
		}
		
	</script>
    
</head>
<body>
		<!--Menu-->
	<?php 
		$page="misapuestas"; 
		include "../pages/menu.php"; 
	?>    

		<div class="container" id="login">			
			<h4>Realiza tus apuestas después de identificarte</h4>
			<div class="container">
				<form class="form-horizontal" role="form" method="POST" action="auth.php">
					<div class="form-group">
						<label for="username" class="col-sm-2 control-label">Usuario</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="username" name="username" placeholder="Usuario" requireed>
						</div>
					</div>
				  	<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Contraseña</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
						</div>
					</div>
				 	<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>"/>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<label><a onclick="$('#login').hide();$('#restablecer').show();">Estoy tomasa y no recuerdo mi contrase&ntilde;a</a></label>
							<br>
							<button type="submit" class="btn btn-default">Entrar</button>
						</div>
				  	</div>
				  	
				</form>
			</div>
   
	<?
		session_start(); 
		$error_msg=$_SESSION['flash_error'];
		$is_info=$_SESSION['is_info'];
		if($error_msg<>"" && ($is_info==null || $is_info=="")){
	?>
			<div class="error">
				<h4>Error</h4>
				<p style="color: #000;">
					<? echo $error_msg; ?>					
				</p>
			</div>
   <?
		}else if($is_info <>""){
	?>
			<div class="info">
				<h4>Info</h4>
				<p style="color: #000;">
					<? echo $error_msg; ?>					
				</p>
			</div>
	<?
		}
   ?>
   </div>
	<div class="container" id="restablecer" style="display:none;">
		<div class="container">
			<h4>Restablecer contrase&ntilde;a</h4>		
		</div>


		<form class="form-horizontal" role="form" method="POST" action="restablecer.php">
			<div class="form-group">
				<label for="usernameRest" class="col-sm-2 control-label">Usuario</label>
				<div class="col-sm-4">
					<input type="text" class="form-control" id="usernameRest" name="username" placeholder="Usuario">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-default">Restablecer</button>
					<p>Se te enviará una contrase&ntilde;a nueva a tu dirección de correo</p>
				</div>				
			</div>
		</form>
  	</div>
<script src="../bootstrap/js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>


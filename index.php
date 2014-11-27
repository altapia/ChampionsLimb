<!DOCTYPE html>
<html lang="es">
<head>
	<? include "./pages/getProperty.php"; ?>
	
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="./favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="./favicon.png" type="image/png"/>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
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
	<?php $page="index"; include "./pages/menu.php"; ?>

	<div class="container hidden-xs">
		<h1><? echo getPropiedad("titulo_index"); ?></h1>
		<p><? echo getPropiedad("desc_index"); ?></p>
	</div>
 
    <div class="container col-sm-8 col-sm-offset-2">

 <?
	include "./pages/conexion_bd.php";
	
	if($_POST["query"]==2){

		$usuario= $_POST["usuario"];
		$mensaje= $_POST["mensaje"];

		/*if($usuario=="EUROLimbo"){
			$usuario="EUROPenis";
			$mensaje="Intento de violaci&oacute;n de seguridad.";
		}else if($usuario=="EUROLimbo_anopeludo"){
			 $usuario="EUROLimbo";
		}*/

		if (preg_match('/<(\s)*t/i', $mensaje)) {
			$usuario="EUROPenis";
			$mensaje="Intento de violaci&oacute;n de seguridad.";
		}
		if(!mysql_query("INSERT INTO chat (USUARIO,MENSAJE,FECHA) VALUES ('$usuario', '$mensaje', now()) ")){
			die('Error: ' . mysql_error());
		}

		$_POST["query"]=0;
	}

	if($_POST["query"]==3){
		//Ejecutamos la sentencia SQL
		$result=mysql_query("SELECT  USUARIO as usuario,MENSAJE as mensaje,FECHA as fecha
							FROM chat
							ORDER BY ID DESC
							");

	}else{
		//Ejecutamos la sentencia SQL
		$result=mysql_query("SELECT  USUARIO as usuario,MENSAJE as mensaje,FECHA as fecha
							FROM chat
							ORDER BY ID DESC
							LIMIT 50");

	}
         
?>
        <table class="table table-bordered">
            <tr>
                <td class="text-center" style="background-color:whiteSmoke;" >
					<strong>Chat </strong>
					<a href=""><span class="glyphicon glyphicon-refresh"></span></a>
                </td>
            </tr>
            <tr>
            	<td style="padding: 0px;">
					<div style="min-height:200px;max-height: 300px;overflow-y: scroll;">
						<?
							include "./pages/iconos_usuarios.php";
							echo '<table class="table-striped table-hover" style="width: 100%;" id="table_chat">';
							
							while ($row=mysql_fetch_array($result)){
								
								$date = date_create($row["fecha"]);
								echo '<tr>';
								
								if($row["usuario"]=="EUROLimbo"){
							?>
									<td style="background-color: rgb(255, 232, 190);">
										<div class="h5 col-xs-6">
											<img src="./images/<?php echo get_ico_usuario($row['usuario']);?>"  height="20"/>
											<strong> <?php echo $row["usuario"]; ?>:</strong>
										</div>
										<div class="h5 text-right col-xs-6">
											<small class="text-right">
										  		<span class="glyphicon glyphicon-time"></span>
										  		<?php echo date_format($date, 'd/m/Y H:i');?>
										  	</small>
										</div>
																		
										<div class="col-xs-12" style="border-bottom: 1px dotted #B3A9A9;"> 
										  <p><?php echo $row["mensaje"];?></p>
										</div>
									</td>
							<?php
								}else{
							?>
									<td>
										<div class="h5 col-xs-6">
											<img src="./images/<?php echo get_ico_usuario($row['usuario']);?>"  height="20"/>
											<strong> <?php echo $row["usuario"]; ?>:</strong>
										</div>
										<div class="h5 text-right col-xs-6">
											<small class="text-right">
										  		<span class="glyphicon glyphicon-time"></span>
										  		<?php echo date_format($date, 'd/m/Y H:i');?>
										  	</small>
										</div>
																		
										<div class="col-xs-12" style="border-bottom: 1px dotted #B3A9A9;"> 
										  <p><?php echo $row["mensaje"];?></p>
										</div>
									</td>
							<?php	
								}
								echo '</tr>	';
							}mysql_free_result($result);
							
							if($_POST["query"]!=3){
							?>
								<tr>
									<td colspan="2" style="text-align:center;padding: 0px;">
										<div class="alert alert-success">
											<form action="" method="post">
												<input type="submit" class="btn btn-default btn-xs" value="Cargar todos los mensajes"/>
												<input type="hidden" name="query" value="3"/>
											</form>
										</div>
									</td>
								</tr>
							<?php
								}
							?>	
							
							</table>						
						
					</div>
            	</td>
            </tr>  
        </table>     
		<div class="well row">
		<?php //NO Está LOGADO
			if (!$_SESSION['signed_in']) { 
		?>
				<div class="h4">
					<span class="glyphicon glyphicon-log-in" ></span>  
					Es necesario estar logado para chatear
				</div>
		<?php
			}else{						
		?>
			



					<form action="" method="post" class="" role="form" id="formChat2">
						
						<?php if($_SESSION['is_admin']){ ?>

							<div class="form-group col-xs-6 col-sm-3">
								<select class="form-control" name="usuario">
									<option value="<?php echo ucfirst(($_SESSION['username'])); ?>"><?php echo ucfirst($_SESSION['username']); ?></option>
									<option value="EUROLimbo">EUROLimbo</option>
								</select>	
							</div>

						<?php }else{ ?>					
								<input type="hidden" name="usuario" value="<?php echo ucfirst($_SESSION['username']); ?>">
						<?php } ?>
					  
					  	<div class="form-group col-xs-12 col-sm-12">
					  		<div class="input-group">
  								<div class="input-group-btn">
  									<button type="button" class="btn btn-info" id="btnInsertaImagen">
										<span class="glyphicon glyphicon-picture"></span>
									</button>
    								<button type="button" class="btn btn-info collapsed" data-toggle="collapse" data-target="#divbotones" href="#">
										<span class="glyphicon glyphicon-user"></span>
									</button>
  								</div>
  								<input type="text" class="form-control" id="mensaje2" placeholder="Mensaje" name="mensaje" style="width: 100%;">
  								<div class="input-group-btn">
  									<button type="submit" class="btn btn-primary">Enviar</button>
  								</div>
					  		</div>
							
					  	</div>					
						<input type="hidden" name="query" value="2"/>								
					</form> 		

					<div class='h4 collapse text-center col-xs-12' id='divbotones'>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('barbol.png');"  title="Zato"> 
							<img  src="./images/barbol.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('bear.png');"  title="Tapia"> 
							<img src="./images/bear.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('doctor.png');"  title="Matute"> 
							<img src="./images/doctor.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('gnomo.png');"  title="Nano"> 
							<img src="./images/gnomo.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('ligh.png');"  title="Lucho"> 
							<img src="./images/ligh.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('moro.png');"  title="Borja"> 
							<img src="./images/moro.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('schooles.png');"  title="Poles"> 
							<img src="./images/schooles.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('trompetilla.png');"  title="Ra&uacute;l"> 
							<img src="./images/trompetilla.png" style="max-height:20px"/></button>
						<button type="button"  class="btn btn-default btn-sm" onClick="insertaIcono('saco.png');"  title="Euros"> 
							<img src="./images/saco.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('toilet.png');"  title="Inodoro"> 
							<img src="./images/toilet.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('ok.png');"  title="Bieeeen!"> 
							<img src="./images/ok.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('pene.png');"  title="Bieeeen!"> 
							<img src="./images/pene.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('tetas.png');"  title="Bieeeen!"> 
							<img src="./images/tetas.png" style="max-height:20px"/></button>
						<button type="button" class="btn btn-default btn-sm" onClick="insertaIcono('culo.png');"  title="Bieeeen!"> 
							<img src="./images/culo.png" style="max-height:20px"/></button>
					</div>
							
				</div>
			<?php
				}
			?>
			
		


	</div>

	<!--container -->    
    <script src="bootstrap/js/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript">

		function insertaIcono(cadena){
			var formulario = document.getElementById("mensaje2");
			formulario.value += "<img src=\"./images/" + cadena +  "\" height=\"20\" width=\"20\"/>";
			formulario.focus();
		}

		function insertImagen(){
			var img = prompt("Introduce la URL de la imagen");
			if (img != null) {
				var msgActual = $('#mensaje2').val();
				var imgCompuesta = "<img src=\""+img+"\" style=\"max-height:180px;max-width:180px\"/>";
				$('#mensaje2').val(msgActual+imgCompuesta);
			}
		}

		

		$(document).ready(function(){  	  									
			$("#formChat2").submit(function() { 		 		 		 		 		 		
				if($("#nombre2").val()!='' && $("#mensaje2").val()!='' && $("#nombre2").val()!='EUROLimbo'){		
					return true;
				}	 
				if($("#nombre2").val()==''|| $("#nombre2").val()=='EUROLimbo' ){
					$("#filesetusuario").attr("class","control-group error");
				}else{
					$("#filesetusuario").attr("class","");
				}
				if( $("#mensaje2").val()==''){
					$("#filesetmensaje").attr("class","control-group error");
				}else{
					$("#filesetmensaje").attr("class","");
				}	 				 		 			 	
				return false;
			});

			$('#btnInsertaImagen').click(function() {
				insertImagen();
			});

			//Detectar movil
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 				console.log("movil");

 				$('#table_chat img').each(function() {
 					if($(this).height()!=20){
	 					$(this).attr('src2',$(this).attr('src'));
	 					$(this).attr('src','http://icons.iconarchive.com/icons/rokey/seed/128/bitmap-image-icon.png');
	 					$(this).click(function(){
	 						var srcURL = $(this).attr('src');
	 						$(this).attr('src',$(this).attr('src2'));
	 						$(this).attr('src2',srcURL);
	 					});
 					}
 				});
			}else{
				console.log("no movil");
			}
		});

		
    </script>
</body>
</html>

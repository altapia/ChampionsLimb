
<?php 
	@session_start();
	include_once  "getProperty.php";

	$enlace="..";
	if($page=="index"){
		$enlace=".";	
	}else if ($page=="admin"){
		$enlace="../..";
	}else if ($page=="config"){
		$enlace="../../..";
	}
?>
<script src="<?php echo $enlace;?>/bootstrap/js/jquery.min.js"></script>
<!-- asdass-->
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
  			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
 			 </button>

			<a class="navbar-brand" href="<?php echo $enlace;?>">
			<img src="<?php echo $enlace;?>/images/<? echo getPropiedad("logo"); ?>" style="height:23px;"> 
			<? echo getPropiedad("titulo"); ?><strong>Limb</strong>
  			</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  			<ul class="nav navbar-nav">
  				<?php if(substr($page,0,4)=="fase"){ ?>
					<li class="dropdown active">
				<?php } else { ?>
					<li class="dropdown">
				<?php } ?>
  					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Partidos <b class="caret"></b></a>
  					<ul class="dropdown-menu">  					
  						<?php
  							$aux = substr($page,5,1);
  							if(!is_numeric($aux)){
  								$aux=-1;
  							}
  							
  							echo getFasesMenu(substr($page,5,1), $enlace); 
						?>
  					</ul>
				</li>
				
				<?php if($page=="apuestas"){?> <li class="active">
				<?php } else { ?> <li> <?php } ?>
				<a href="<?php echo $enlace;?>/pages/apuestas.php">Apuestas <span class="glyphicon glyphicon-list-alt"></span></a></li>
				
				<?php if($page=="clasificacion"){?> <li class="active">
				<?php } else { ?><li><?php } ?>
				<a href="<?php echo $enlace;?>/pages/clasificacion.php">Clasificación <span class="glyphicon glyphicon-sort-by-attributes"></span></a></li>

				<?php if($page=="sorteo"){?> <li class="active">
				<?php } else { ?><li><?php } ?>
				<a href="<?php echo $enlace;?>/sorteo/sorteo_live.php">Sorteo <span class="glyphicon glyphicon-gift"></span></a></li>

				<?php if($page=="estadisticas"){?> <li class="active">
				<?php } else { ?><li><?php } ?>
				<a href="<?php echo $enlace;?>/pages/estadisticas.php">Estadísticas <span class="glyphicon glyphicon-stats"></span></a></li>
				
  			</ul>


  			<!-- <ul class="nav navbar-nav navbar-right"> -->
			<ul class="nav navbar-nav navbar-right">
				<?php //NO Está LOGADO
					if (!$_SESSION['signed_in']) { ?>
				 		
						<li class="dropdown" id="menuLogin">							
							<a class="dropdown-toggle" href="#" data-target="#" data-toggle="dropdown" id="navLogin">Login</a>
							<div class="dropdown-menu" style="padding:17px;">
								<form class="form" id="formLogin" action="<?php echo $enlace.'/pages/'; ?>auth.php" method="POST"> 
									<div class="form-group">
										<input name="username" id="username" class="form-control input-sm" type="text" placeholder="Username"> 
									</div>
									<div class="form-group">
										<input name="password" id="password" class="form-control input-sm" type="password" placeholder="Password"><br>
									</div>
									<button type="submit"  class="btn btn-default" id="btnLogin">Login</button>
									<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>"/>
								</form>
							</div>
						</li>

				<?php }else{ /*SI está logado*/ ?> 
						<!-- <p class="navbar-text navbar-right"> -->
						<li>
							<a href="<?php echo $enlace.'/pages/misapuestas.php'; ?>" class="navbar-link"  title="Mis Apuestas" 
									style="padding-left: 10px;padding-right: 5px;">
		 						<span class="glyphicon glyphicon-pencil"></span> Mis apuestas
							</a>
						</li>
						<?php //ADMIN
							if ($_SESSION['is_admin']) { 
						?>
							<li><a href="<?php echo $enlace.'/pages/admin/mantenimientoPartidos.php'; ?>" class="navbar-link"  title="Configuración" 
									style="padding-left: 5px;padding-right: 10px;">
		 						<span class="glyphicon glyphicon-wrench"></span> Admin.
							</a></li>

						<?php } ?>
																			
							<li class="dropdown" id="menuCambioPas">
								<a class="dropdown-toggle" href="#" data-toggle="dropdown" id="navCambio" title="Cambiar contrase&ntilde;a" >
									<strong><?php echo ucfirst($_SESSION['username']); ?></strong>
								</a>
								<div class="dropdown-menu" style="padding:17px;">
									<form class="form" id="form_cambiopass" action="<?php echo $enlace.'/pages/'; ?>cambiopas.php" method="POST"> 
										<div class="form-group">
											<input name="password" id="pass" class="form-control input-sm" type="password" placeholder="Password"> 
										</div>
										<div class="form-group" id="div_pass_re">
											<input id="passRe" class="form-control input-sm" type="password" placeholder="Re-Password"><br>
										</div>
										<button type="submit"  class="btn btn-default" id="btnLogin">Cambiar Contrase&ntilde;a</button>
										<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>"/>
										<input type="hidden" name="user" value="<?php echo $_SESSION['userid'];?>"/>
									</form>
								</div>
								<script type="text/javascript">
									$('#form_cambiopass').submit(function() {										
										if($('#pass').val()!=$('#passRe').val()){
											$('#div_pass_re').addClass('has-error');
											return false;
										}
										return true;
									});
								</script>
							</li>
							<li>
								<a href="<?php echo $enlace.'/pages/logout.php'; ?>" class="navbar-link"  title="Salir" 
									style="padding-left: 10px;padding-right: 0px;">
									<span class="glyphicon glyphicon-log-out"></span>
								</a>
							</li>
						<!-- </p> -->
				<?php } ?>

    		</ul>    		
		</div>
  	</div>
</nav>

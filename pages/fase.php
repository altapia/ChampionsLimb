<!DOCTYPE html>
<html lang="es">
<head>
	<?php 
		include "../pages/getProperty.php"; 
		include "../pages/conexion_bd.php";
	?>
	
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="../favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="../favicon.png" type="image/png"/>
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
	<title><?php echo getPropiedad("titulo_head"); ?></title>

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
		$page="fase_".$_GET["id"]; 
		include "../pages/menu.php"; 
	?>

    <div class="container">    			
		<h2><?php echo getTituloFase($_GET["id"]); ?></h2>
			

<?php
$clave=getClaveFase($_GET["id"]); 
if($_GET["apostante"]!=""){ 		
	echo '<h4>Encuentros de '.$_GET["apostanteNom"].' </h4>';
	$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, e.escudo as escLocal, f.escudo as escVisit 
			FROM partidos p, equipos e, equipos f , partido_apostante pa
			WHERE p.local=e.id 
				and p.visitante=f.id  
				and p.fase="'.$clave.'"
				and pa.idapostante='.$_GET["apostante"].'
				and pa.idpartido = p.id
			ORDER BY p.fecha, p.hora,p.id asc';
}else{		
	echo '<h4>Todos los partidos</h4>';
	$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, e.escudo as escLocal, f.escudo as escVisit 
			FROM partidos p, equipos e, equipos f 
			WHERE p.local=e.id and p.visitante=f.id and p.fase="'.$clave.'"
			ORDER BY p.fecha , p.hora ,p.id asc';
}
$result=mysql_query($query);
echo mysql_error();
if($result!=NULL){
	$num_rows = mysql_num_rows($result);
	if($num_rows!=NULL || $num_rows > 0){
	}

	function selectApostadores($i){
		$select = '<select  disabled="disabled" style="width: 100px;"><option value="0"></option>';
		$query='SELECT id, nombre  FROM apostantes ORDER BY nombre asc';
		$result=mysql_query($query);
		while ($row=mysql_fetch_array($result)) {
			if($row[id]==$i){
				$select=$select.'<option selected="selected" value="'.$row[id].'">'.$row[nombre].'</option>';
			}else{
				$select=$select.'<option value="'.$row[id].'">'.$row[nombre].'</option>';
			}
		}mysql_free_result($result);
		$select=$select.'</select>';
		return $select;
	}

	$fecha_actual = strtotime(date("d-m-Y",time()));
	$aux=0;

?>


<!-- APOSTADORES TABS-->
  <ul class="nav nav-tabs">
  	<? 
		//Obtenemos los apostantesapostadores
		echo getTabsApostadoresPorFase($_GET["id"],$_GET["apostante"]);		
		
	?>
	<p class="navbar-text navbar-right h5">
  		<small>*Horarios en CEST</small>
  	</p>
  </ul>


<?php
$fecha_actual = strtotime(date("d-m-Y",time()));
$grupo=0;
$visible=true;
$hayBTAnterior=false;
$hayPartidosOcultos=false;

while ($row=mysql_fetch_array($result)) {

//FECHA
	$date = date_create($row["fecha"]);	
	if($aux==0 OR $dateaux<>$date){
		$grupo++;
		$dateaux=$date;		
		if($aux!=0){
			echo '</div>';
		}
		$aux=1;

		$fecha_partido = strtotime($row["fecha"]);            							
		echo '<div class="list-group">';

		//echo '----->'.($fecha_partido-$fecha_actual).'<-----';
		if($fecha_partido==$fecha_actual){
			$visible=true;
			echo '<a href="#grupo'.$grupo.'" id="grupo'.$grupo.'"  name="fecha" class="list-group-item" onclick="muestra(\'partido'.$grupo.'\');" style="padding-top: 0px;padding-bottom: 0px;background-color: darkorange;color: white;border-color: darkorange;">';
		}else if($fecha_partido<$fecha_actual){			
			$visible=false;
			//El partido de ayer se muestra minimizado
			if(($fecha_partido-$fecha_actual)==-86400){
				$hayBTAnterior=true;
		?>
						<a href="#" id="muestraAnteriores" class="list-group-item text-center" onclick="muestraAnteriores();" style="padding-top: 0px;padding-bottom: 0px;background-color: gainsboro;border-color: gainsboro;">
							<span class="glyphicon glyphicon-chevron-up"></span>  
							<strong>Anteriores</strong>
						</a>
					</div>
					<div class="list-group">
		<?php
				echo '<a href="#grupo'.$grupo.'" id="grupo'.$grupo.'" name="fecha" class="list-group-item" onclick="muestra(\'partido'.$grupo.'\');" style="padding-top: 0px;padding-bottom: 0px;background-color: gainsboro;border-color: gainsboro;">';
			}else{
				$hayPartidosOcultos=true;
				echo '<a href="#grupo'.$grupo.'" id="grupo'.$grupo.'" name="fecha" class="list-group-item" onclick="muestra(\'partido'.$grupo.'\');" style="padding-top: 0px;padding-bottom: 0px;background-color: gainsboro;border-color: gainsboro;display:none;">';
			}
			
		}else{
			$visible=true;
			echo '<a href="#" class="list-group-item active" onclick="muestra(\'partido'.$grupo.'\');" style="padding-top: 0px;padding-bottom: 0px">';
		}
		echo ucfirst(getDiaSemana($date)).' ';
		echo getFechaFormat($date);
		echo '</a>';
	}

?>
	<a data-toggle="collapse" data-target='<?php echo "#comment".$row["id"];?>'  
		href='<?php echo "#".$row["id"];?>' class="list-group-item text-center" style="min-height:65px; 
		<?php
			if(!$visible){
				echo "display:none;";
			}
		?>
		" name='<?php echo "partido".$grupo;?>'  >
		<div class="h4 col-xs-12">
    		<div class="col-sm-5 col-xs-3">
				<span class="hidden-xs"><?php echo $row["local"]; ?></span>
				<img class="image-chica" src="../images/escudos/<?php echo $row['escLocal']; ?>">
			</div>
			<div class="col-sm-2 col-xs-6 text-center h3" style="margin-top: 0px;margin-bottom: 0px;">
    			<strong><?php echo substr($row["hora"],0,5); ?></strong>
    		</div>
			<div class="col-sm-5 col-xs-3">
				<img class="image-chica" src="../images/escudos/<?php echo $row['escVisit']; ?>">
				<span class="hidden-xs"><?php echo $row["visitante"];?></span>
			</div>	
		</div>	
		<div class="row">
			<?php echo getApostadoresSimple($row["id"]); ?>
		</div>
		
	</a>
	<div id='comment<?php echo $row["id"]; ?>' idPartido='<?php echo $row["id"]; ?>' name="tabla_apuestas" class="collapse" style="margin-bottom:0px;border-radius:0px 0px 4px 4px;">

		<table class="table table-condensed">
			<thead>
				<tr>						
					<th colspan="2" style="text-align:center">Apuesta</th>
					<th style="text-align:center">Cotización</th>
					<th style="text-align:center">Apostado</th>
					<th class="hidden-xs" style="text-align:center">Ganancia</th>
					<th class="hidden-xs" style="text-align:center;">Apostante</th>
				</tr>
			</thead>
			<tbody id="datos_apuestas_<?php echo $row["id"]?>">
			</tbody>
		</table>
	</div>
<? 		
		}
		mysql_free_result($result);
		
		if($hayPartidosOcultos && !$hayBTAnterior){
?>
			<div class="list-group">
				<a href="#" id="muestraAnteriores" class="list-group-item text-center" onclick="muestraAnteriores();" style="padding-top: 0px;padding-bottom: 0px;background-color: gainsboro;border-color: gainsboro;">
					<span class="glyphicon glyphicon-chevron-up"></span>  
					<strong>Anteriores</strong>
				</a>
			</div>
<?php
		}
?>
    	    
</div>
</div>
<? }?>




 </div>
 </div>
 	<script src="../bootstrap/js/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script>
		function cambiarIco(i){
			if($('#ico'+i).attr("class")=="glyphicon glyphicon-plus icon-white"){
				$('#ico'+i).attr("class","glyphicon glyphicon-minus icon-white");
			}else{
				$('#ico'+i).attr("class","glyphicon glyphicon-plus icon-white");
			}
		}
		

		//Cuando se abra la tabla de apuestas, se cargan las apuestas por ajax.
		$('.collapse').on('show.bs.collapse', function () {			
  			getApuestas($(this).attr('idPartido'));
		})

		function getApuestas(idPartido){
			var parametros = {
				"operacion" : "getApuestasParaUpdate",
				"id" : idPartido
			};
			$.ajax({
				data:  parametros,
				url:   '../pages/dao/operaciones.php',
				type:  'post',
				async: 'false',
				contentType: "application/x-www-form-urlencoded; charset=ISO-8859-1",
				beforeSend: function () {						
					$('#datos_apuestas_'+idPartido).html('<tr><td colspan="6" class="text-center" style=""><img src="../images/ajax-loader.gif"></td></tr>');
				},
				success:  function (response) {
					$('#datos_apuestas_'+idPartido).html(response);							
				},
				complete: function(){
					
				}
    		});				
		}
		
		function muestra(nombre){
			
			$('a[name="'+nombre+'"]').each(function(i,o){
				if ($(this).css('display')!='none'){
					var comment =$(this).attr('href').substr(1);
					//Si está desplegado se oculta
					if($('#comment'+comment).hasClass('in')){
						$('#comment'+comment).collapse('hide');
						/*$('#comment'+comment).on('hidden.bs.collapse', function () {
  							cuenta--;
  							if(cuenta==0){
  								$('a[name="'+nombre+'"]').toggle();
  								cuenta=-1;
  							}
						})*/
					}
				}
			});
			
			
			$('a[name="'+nombre+'"]').toggle();
		
		}

		function muestraAnteriores(){
			$('#muestraAnteriores').hide();
			$('a[name="fecha"]').show();
		}
	</script>
</body>
</html>


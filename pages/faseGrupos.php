<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="ISO-8859-1">
	<title>Champions Limb</title>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link href="../bootstrap_new/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			padding-top: 60px;
		}
	</style>
	<link href="../bootstrap_new/css/bootstrap-responsive.min.css" rel="stylesheet">
    
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
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href=".." style="vertical-align:middle"><img src="../images/champions-league-logo_trans.png" style="height:30px;"> CHAMPIONS<strong>Limb</strong></a>
				<ul class="nav">
					<!--<li><a href="../">Home</a></li>-->
					<li class="dropdown active" id="menu1">
                    	<a class="dropdown-toggle" data-toggle="dropdown"  href="#">
                    		 Partidos
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu active">
							<li class="active"><a href="#">Fase de grupos</a></li>
							<li><a href="../pages/octavos.php">Octavos de final</a></li>
							<li><a href="../pages/cuartos.php">Cuartos de final</a></li>
<!--							<li><a href="../pages/semifinal.php">Semifinal</a></li>
							<li><a href="../pages/final.php">Final</a></li>-->
						</ul>
					</li>
					<li><a href="../pages/apuestas.php">Apuestas</a></li>
          <li><a href="../pages/clasificacion.php">Clasificación</a></li>
				</ul>
			</div>
		</div>
	</div>

<div class="span8">
    <div class="container">
        <h2>Fase de grupos</h2>
					
<? include "../pages/conexion_bd.php"; ?>


<!-- Select grupo -->
 <div class="alert alert-success">

    
  <!-- Select fecha-->
  <!-- <strong> Seleccione una jornada:</strong> -->
  <select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
	<option>Jornadas...</option>
	<? 
		//Ejecutamos la sentencia SQL
		$result=mysql_query("select distinct fecha from partidos where fase like 'GRUPO%' order by fecha asc");
		$jornada=0;
		while ($row=mysql_fetch_array($result)) {
			$jornada=$jornada+1;
			$date = date_create($row["fecha"]);
		  echo '<option value=faseGrupos.php?fecha='.$row["fecha"].'&jornada='.$jornada.'>Jornada '.$jornada.' ('.date_format($date, 'd/m/Y');
		  $row=mysql_fetch_array($result);
		  $date = date_create($row["fecha"]);
		  echo '-'.date_format($date, 'd/m/Y').')</option>';
		}mysql_free_result($result);
	?>
</select>

  <!-- Select fecha-->
  <!-- <strong> Seleccione una jornada:</strong> -->
  <select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
	<option>Fechas...</option>
	<? 
		//Ejecutamos la sentencia SQL
		$result=mysql_query("select distinct fecha from partidos where fase like 'GRUPO%' order by fecha asc");
		while ($row=mysql_fetch_array($result)) {
			$date = date_create($row["fecha"]);
		  echo '<option value=faseGrupos.php?fecha='.$row["fecha"].'>'.date_format($date, 'd/m/Y');
		  echo '</option>';
		}mysql_free_result($result);
	?>
</select>

<!-- Select fecha-->
  <!-- <strong> Seleccione un apostante:</strong> -->
  <select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
	<option>Apostantes...</option>
	<? 
		//Ejecutamos la sentencia SQL
		$result=mysql_query("select nombre, id from apostantes order by nombre asc");		
		while ($row=mysql_fetch_array($result)) {						
		  echo '<option value="faseGrupos.php?apostante='.$row["id"].'&apostanteNom='.$row["nombre"].'">'.$row["nombre"].'</option>';
		}mysql_free_result($result);
	?>
</select>
</div>
<!-- FIN Select grupo -->

<? 
if($_GET["grupo"]!="" || $_GET["fecha"]!="" || $_GET["apostante"]!=""){ 
	if($_GET["grupo"]!=""){
		if($_GET["grupo"]=="Todos"){
			echo '<h3>Todos los Encuentros</h3>';
			$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal, f.escudo as escVisit 
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id
					ORDER BY p.fecha, p.hora,p.id asc';
		}else{
			echo '<h3>GRUPO '.$_GET["grupo"].'</h3>';
			$query='SELECT p.id, e.nombre as local , f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal , f.escudo as escVisit
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and e.grupo =\''.$_GET["grupo"].'\' 
					ORDER BY p.fecha, p.hora,p.id asc';
		}
		$result=mysql_query($query);
	}else if($_GET["fecha"]!=""){
		if($_GET["jornada"]!=""){
			echo '<h3>Encuentros de la jornada '.$_GET["jornada"].' </h3>';
			$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal, f.escudo as escVisit 
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and (p.fecha=\''.$_GET["fecha"].'\' or p.fecha=ADDDATE(\''.$_GET["fecha"].'\',1)) 
					ORDER BY p.fecha, p.hora, p.id asc';
		}else{
			$date = date_create($_GET["fecha"]);
			echo '<h3>Encuentros del '.date_format($date, 'd/m/Y').' </h3>';
			$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal, f.escudo as escVisit 
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and p.fecha=\''.$_GET["fecha"].'\' 
					ORDER BY p.fecha, p.hora,p.id asc';
		}
	}else if($_GET["apostante"]!=""){
		
		echo '<h3>Encuentros de '.$_GET["apostanteNom"].' </h3>';
			$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal, f.escudo as escVisit 
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and p.apostante='.$_GET["apostante"].' 
					ORDER BY p.fecha, p.hora,p.id asc';
	}
	//Ejecutamos la sentencia SQL
		$result=mysql_query($query);
echo mysql_error();
?>
        
        <table class="table table-condensed">
            <thead>
                <tr>
                	<th style="text-align:center">
                    	<a class="btn btn-mini btn-info" href="javascript:desplegarTodos();" id="btn_desplegar">
                        	<i class="icon-plus icon-white"></i> 
                            Desplegar todos
                        </a>
                    </th>						
                    <th colspan="3" style="text-align:center">Partido</th>
                    <th style="text-align:center">Apostante</th>
                </tr>
            </thead>
            <tbody>
            
<?
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
?>

<? 

$fecha_actual = strtotime(date("d-m-Y",time()));
            
				while ($row=mysql_fetch_array($result)) {						
					echo '<tr>';
						echo '<td rowspan="2" style="text-align: center;vertical-align: middle;">'; 
							echo '<a class="btn btn-mini btn-info" data-toggle="collapse" data-target="#comment'.$row["id"].'"onclick="cambiarIco('.$row["id"].')" href="#'.$row["id"].'">'; 
							echo '<i id="ico'.$row["id"].'" name="icono_toggle" class="icon-plus icon-white"></i></a>';							
							echo '</td>';
						echo '<td rowspan="2" style="text-align: right;vertical-align: middle;">';						
						echo $row["local"].' <img src="../images/escudos/'.$row["escLocal"].'"></td>';
						$date = date_create($row["fecha"]);
						echo '<td style="text-align: center;vertical-align: middle;">'.date_format($date, 'd/m/Y').'</td>';
						echo '<td rowspan="2" style="text-align: left;vertical-align: middle;"><img src="../images/escudos/'.$row["escVisit"].'"> '.$row["visitante"].'</td>';
						echo '<td rowspan="2" style="text-align: center;vertical-align: middle;">'.selectApostadores($row["apostante"]).'</td>';
						$fecha_partido = strtotime($row["fecha"]);            
							if($fecha_partido==$fecha_actual){
								echo ' <span class="badge badge-warning" style="padding-right: 1px;padding-left: 1px;"><i class="icon-fire icon-white"></i> </span>';
							}
						echo '</td>';
					echo '</tr>';
					echo '<tr><td style="text-align: center;vertical-align: middle;">'.substr($row["hora"],0,5).'</td></tr>';
					echo '<tr><td colspan="5" style="padding: 0px 0px 0px 0px"><div id="comment'.$row["id"].'" name="tabla_apuestas" class="collapse" style="margin-bottom:0px;margin-left:40px;margin-right:40px;border-radius:0px 0px 4px 4px;">'; 
							?>
           <table class="table table-condensed">
            <thead>
                <tr>						
                    <th colspan="2" style="text-align:center">Apuesta</th>
                    <th style="text-align:center">Cotización</th>
                    <th style="text-align:center">Apostado</th>
                    <th style="text-align:center">Ganancia</th>
                </tr>
            </thead>
            <tbody>
            <?
				$queryApuestas='select apuesta, apostado, cotizacion, acertada from apuestas where partido ='.$row["id"].' order by id asc';
				$resultApuestas=mysql_query($queryApuestas);
				$totalGan=0;
				while ($rowApu=mysql_fetch_array($resultApuestas)) {
					
					echo '<tr>';
						echo '<td style="text-align:center">';
							if($rowApu["acertada"]==1){
								echo '<i class="icon-ok"></i>';
							}else if($rowApu["acertada"]==2){
								echo '<i class="icon-remove"></i>';
							}else{
								echo '<i class="icon-time"></i>';
							}
						echo '</td>';
						echo '<td>'.$rowApu["apuesta"].'</td>';
						echo '<td style="text-align:center">'.$rowApu["cotizacion"].'</td>';
						echo '<td style="text-align:center">'.$rowApu["apostado"].'&euro;</td>';
						echo '<td style="text-align:center">';
							$ganancia=0;
							if($rowApu["acertada"]==1){
								$ganancia= ($rowApu["apostado"] * $rowApu["cotizacion"])-$rowApu["apostado"];
							}else if($rowApu["acertada"]==2){
								$ganancia=$rowApu["apostado"] * -1;
							}
							echo $ganancia.'&euro;';
							$totalGan=$totalGan+ $ganancia;
						echo '</td>';
                   	echo '</tr>';
				}mysql_free_result($resultApuestas);
				echo mysql_error();
				echo '<tr><td style="text-align:right" colspan="4"><strong>Ganancia Total</strong></td><td>'.$totalGan.'</td></tr>';
			?>
                  
            </tbody>
        </table>            
                            <? 
							echo '</div></td></tr>';
				}mysql_free_result($result);
			?>
            	
            </tbody>
        </table>                   
<? }?>
 </div>
 </div>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../bootstrap_new/js/bootstrap.min.js"></script>
</body>
</html>


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

	<!-- Google Charts -->
<?
	include "../pages/conexion_bd.php";
?>

	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {

			var data = google.visualization.arrayToDataTable([
          		['Fecha', 'Tapia', 'Nano', 'Rulo', 'Zato', 'Poles', 'Borja', 'Matute', 'Lucho', 'Total por día'],
			['Inicio', 0, 0, 0, 0, 0, 0, 0, 0, 0],
		<?

			$i=0;
			$apostante=0;
			$array = array(
				    1 => 0,
				    2 => 0,
				    3 => 0,
				    4 => 0,
				    5 => 0,
				    6 => 0,
				    7 => 0,
				    8 => 0
				);
			$total_dia=0;
		
		
		
		$queryIni="select 
      			apos, 
      			ap.nombre, 
      			fec, 
      			sum(neto) as neto 
      		from
				(
				SELECT 	
					p.apostante as apos, 
					p.fecha as fec, 
					round(if(a.acertada=1,a.apostado*a.cotizacion-a.apostado,a.apostado*-1),2) as neto
				FROM 
					apuestas a, 
					partidos p 
				WHERE 
					a.partido = p.id and 
					a.acertada>0";
		
		$queryFase ="";
		if($_GET["fase"]=="grupos"){
			$queryFase=" and p.fase like 'GRUPO%' ";
		}else if($_GET["fase"]=="octavos"){
			$queryFase=" and p.fase = 'OCTAVOS' ";
		}if($_GET["fase"]=="cuartos"){
			$queryFase=" and p.fase = 'CUARTOS' ";
		}if($_GET["fase"]=="semi"){
			$queryFase=" and p.fase = 'SEMI' ";
		}if($_GET["fase"]=="final"){
			$queryFase=" and p.fase = 'FINAL' ";
		}
		
		$queryFin=") as culo,
				apostantes ap 
			where 
				apos = ap.id
			group by apos, 
					fec 
			order by 
				fec, 
				apos;";
		
      		$result2=mysql_query($queryIni.$queryFase.$queryFin);
		
			 while ($row=mysql_fetch_array($result2)){
				$apostante=$row["apos"];
				if($i==0){
			 		$fecha_ini=$row["fec"];
					$date = date_create($row["fec"]);
					echo '[\''.date_format($date, 'd/m/Y').'\'';
				}
				$i=1;
				
				if($fecha_ini==$row["fec"]){
					$total_dia=$total_dia+$row["neto"];
					$array[$apostante]=$array[$apostante]+$row["neto"];
				}else{
					for($ii=1;$ii<9;$ii++){
						if($array[$ii]==null){
							echo ',0';
						}else{
							echo ','.$array[$ii];
						}
					}
					echo ','.$total_dia;
					echo '],';
					$date = date_create($row["fec"]);
					echo '[\''.date_format($date, 'd/m/Y').'\'';

					$fecha_ini=$row["fec"];
					$total_dia=$total_dia+$row["neto"];
					$array[$apostante]=$array[$apostante]+$row["neto"];
				}
			}mysql_free_result($result2);
			for($ii=1;$ii<9;$ii++){
						if($array[$ii]==null){
							echo ',0';
						}else{
							echo ','.$array[$ii];
						}
					}
					echo ','.$total_dia;		
			echo ']]);';
      	 ?>

	var options = {
          title : 'Evolución de ganancias netas por día (Evolución del total)',
          vAxis: {title: "Euros"},
          hAxis: {title: "Día"},
          seriesType: "bars",
          series: {8: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);

        var options2 = {
          title : 'Evolución de ganancias netas por día (Evolución por apostante)',
          vAxis: {title: "Euros"},
          hAxis: {title: "Día"},
          seriesType: "line",
          series: {8: {type: "bars"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div2'));
        chart.draw(data, options2);

/************/

	
	var data2 = google.visualization.arrayToDataTable([
          		['Fecha', 'Tapia', 'Nano', 'Rulo', 'Zato', 'Poles', 'Borja', 'Matute', 'Lucho', 'Total por día'],
							['Inicio', 0, 0, 0, 0, 0, 0, 0, 0, 0],
<? 
		$i = 0;
		$apostante = 0;
		$array = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0);
		$total_dia = 0;
		
		$queryIni="select 
				apos, 
				ap.nombre, 
				fec, 
				sum(neto) as neto 
			from
				(
				SELECT 
					p.apostante as apos, 
					p.fecha as fec, 
					round(if(a.acertada=1,a.apostado*a.cotizacion-a.apostado,a.apostado*-1),2) as neto
				FROM 
					apuestas a, 
					partidos p 
				WHERE 
					a.partido = p.id and 
					a.acertada>0";
		$queryFase ="";
		if($_GET["fase"]=="grupos"){
			$queryFase=" and p.fase like 'GRUPO%' ";
		}else if($_GET["fase"]=="octavos"){
			$queryFase=" and p.fase = 'OCTAVOS' ";
		}if($_GET["fase"]=="cuartos"){
			$queryFase=" and p.fase = 'CUARTOS' ";
		}if($_GET["fase"]=="semi"){
			$queryFase=" and p.fase = 'SEMI' ";
		}if($_GET["fase"]=="final"){
			$queryFase=" and p.fase = 'FINAL' ";
		}
		$queryFin=") as culo,
				apostantes ap 
			where 
				apos = ap.id
			group by 
				apos, 
				fec 
			order by 
				fec, 
				apos;";
		
		$result2=mysql_query($queryIni.$queryFase.$queryFin);
		
		while ($row = mysql_fetch_array($result2)) {
			$apostante = $row["apos"];
			if ($i == 0) {
				$fecha_ini = $row["fec"];
				$date = date_create($row["fec"]);
				echo '[\'' . date_format($date, 'd/m/Y') . '\'';
			}
			$i = 1;

			if ($fecha_ini == $row["fec"]) {
				$total_dia = $total_dia + $row["neto"];
				$array[$apostante] = $row["neto"];
			} else {
				for ($ii = 1; $ii < 9; $ii++) {
					if ($array[$ii] == null) {
						echo ',0';
					} else {
						echo ',' . $array[$ii];
					}
				}
				for ($ii = 1; $ii < 9; $ii++) {
					$array[$ii] = 0;
				}

				echo ',' . $total_dia;
				echo '],';
				$total_dia = 0;
				$date = date_create($row["fec"]);
				echo '[\'' . date_format($date, 'd/m/Y') . '\'';

				$fecha_ini = $row["fec"];
				$total_dia = $total_dia + $row["neto"];
				$array[$apostante] = $row["neto"];
			}
		}mysql_free_result($result2);
		for ($ii = 1; $ii < 9; $ii++) {
			if ($array[$ii] == null) {
				echo ',0';
			} else {
				echo ',' . $array[$ii];
			}
		}
		echo ',' . $total_dia;
		echo ']]);';
    ?>

	var options = {
          title : 'Ganancias netas por día',
          vAxis: {title: "Euros"},
          hAxis: {title: "Día"},
          seriesType: "bars",
          series: {8: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div3'));
        chart.draw(data2, options);

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
					<li class="dropdown" id="menu1">
                    	<a class="dropdown-toggle" data-toggle="dropdown"  href="#">
                    		 Partidos
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu active">
							<li><a href="../pages/faseGrupos.php">Fase de grupos</a></li>
							<li><a href="../pages/octavos.php">Octavos de final</a></li>
							<li><a href="../pages/cuartos.php">Cuartos de final</a></li>
							<li><a href="../pages/semifinal.php">Semifinal</a></li>
				<!--			<li ><a href="../pages/final.php">Final</a></li>-->
						</ul>
					</li>
					<li><a href="../pages/apuestas.php">Apuestas</a></li>
                    <li class="active"><a href="#">Clasificación</a></li>
				</ul>
			</div>
		</div>
	</div>


<? 
	include_once "../pages/iconos_usuarios.php";
?>

<div class="span10">
	<div class="container">
		<h2>Clasificación de apostantes</h2>
		
		<ul class="nav nav-tabs">
			<li <? if($_GET["fase"]=="total" || $_GET["fase"]=="" ){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=total">Total</a>
			</li>
			<li <? if($_GET["fase"]=="grupos"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=grupos">Grupos</a>
			</li>
			<li <? if($_GET["fase"]=="octavos"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=octavos">Octavos</a>
			</li>
			<li <? if($_GET["fase"]=="cuartos"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=cuartos">Cuartos</a>
			</li>
			<li <? if($_GET["fase"]=="semi"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=semi">Semifinal</a>
			</li>
			<li <? if($_GET["fase"]=="final"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=final">Final</a>
			</li>
		</ul>

<? 
	
	if($_GET["fase"]=="grupos"){ echo '<h3>Fase de grupos</h3>';}
	else if($_GET["fase"]=="octavos"){ echo '<h3>Octavos de final</h3>';}
	else if($_GET["fase"]=="cuartos"){ echo '<h3>Cuartos de final</h3>';}
	else if($_GET["fase"]=="semi"){ echo '<h3>Semifinal</h3>';}
	else if($_GET["fase"]=="final"){ echo '<h3>Final</h3>';}
	else echo '<h3>Todas las fases</h3>';

	
	//Clasificación fase de grupos
	$queryParte1=
		"SELECT 
				*,  
				@rownum:=@rownum+1 AS pos 
		FROM 
			(
			SELECT 
				culo.apos, 
				ap.nombre, 
				round(sum(culo.ganancia),2) as neto 
			FROM
				(
				SELECT 
					p.apostante as apos , 
					if(a.acertada=1, a.apostado*a.cotizacion-a.apostado,a.apostado*-1)  as ganancia  
				FROM 
					partidos p, 
					apuestas a 
				WHERE 
					p.id = a.partido and 
					a.acertada>0";
				
	$queryParte2=")  as culo, 
				apostantes ap 
			WHERE 
				ap.id=culo.apos 
			GROUP BY apos 
			ORDER BY neto desc
			) puntuacio, (SELECT @rownum:=0) r;";
	
	$queryFase ="";
	if($_GET["fase"]=="grupos"){
		$queryFase=" and p.fase like 'GRUPO%' ";
	}else if($_GET["fase"]=="octavos"){
		$queryFase=" and p.fase = 'OCTAVOS' ";
	}if($_GET["fase"]=="cuartos"){
		$queryFase=" and p.fase = 'CUARTOS' ";
	}if($_GET["fase"]=="semi"){
		$queryFase=" and p.fase = 'SEMI' ";
	}if($_GET["fase"]=="final"){
		$queryFase=" and p.fase = 'FINAL' ";
	}
			
	$result=mysql_query($queryParte1.$queryFase.$queryParte2);
				
?>
<br>
<table class="table table-condensed">
<thead>
    <tr>
        <th style="text-align:center">Pos.</th>
   		<th style="text-align:center">Apostante</th>
  		<th style="text-align:center">Ganancia neta</th>
    </tr>
</thead>
<tbody>
	
<? 
	
	$tot_gan=0;
	
	while ($row=mysql_fetch_array($result)){
		$tot_gan=$tot_gan+$row["neto"];
		echo '<tr>';
		echo '<td style="text-align:center">'.$row["pos"];
		echo '</td>';
		echo '<td style="text-align:center"> <img src="../images/'.get_ico_usuario($row["nombre"]).'" height="20"/> '.$row["nombre"].'</td>';
		echo '<td style="text-align:center">'.$row["neto"].'&euro;</td>';
		echo '</tr>	';
	}
	
	mysql_free_result($result);
	
	echo '<tr>';
	echo '<td colspan="2" style="text-align:center"><strong>Ganancia Total</strong></td>';
	echo '<td style="text-align:center"><strong>'.$tot_gan.'&euro;</strong></td>';
	echo '</tr>     ';
	
?>

  			</tbody>
		</table>
                  
                 
		<div id="chart_div" style="width: 900px; height: 500px;"></div>        
		<div id="chart_div2" style="width: 900px; height: 500px;"></div>
		<div id="chart_div3" style="width: 900px; height: 500px;"></div>
	</div>
</div>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="../bootstrap_new/js/bootstrap.min.js"></script>

</body>
</html>



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

      	<?
      	$result2=mysql_query("select apos, ap.nombre, fec, sum(neto) as neto from
	(SELECT p.apostante as apos, p.fecha as fec, round(if(a.acertada=1,a.apostado*a.cotizacion-a.apostado,a.apostado*-1),2) as neto
	FROM apuestas a, partidos p WHERE a.partido = p.id and a.acertada>0) as culo,
	apostantes ap where apos = ap.id
	group by apos, fec order by fec, apos;");
		?>
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
	<?
	$result2=mysql_query("select apos, ap.nombre, fec, sum(neto) as neto from
	(SELECT p.apostante as apos, p.fecha as fec, round(if(a.acertada=1,a.apostado*a.cotizacion-a.apostado,a.apostado*-1),2) as neto
	FROM apuestas a, partidos p WHERE a.partido = p.id and a.acertada>0) as culo,
	apostantes ap where apos = ap.id
	group by apos, fec order by fec, apos;");
	?>
	
	var data2 = google.visualization.arrayToDataTable([
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
			 /*while ($row=mysql_fetch_array($result2)){
				$apostante=$apostante+1;
				if($i==0){
			 		$fecha_ini=$row["fec"];
					$date = date_create($row["fec"]);
					echo '[\''.date_format($date, 'd/m/Y').'\'';
				}
				$i=1;

				if($fecha_ini==$row["fec"]){
					$total_dia=$total_dia+$row["neto"];
					$array[$apostante]=$row["neto"];
					echo ','.$array[$apostante];
				}else{
					echo '],';
					$date = date_create($row["fec"]);
					echo '[\''.date_format($date, 'd/m/Y').'\'';

					$fecha_ini=$row["fec"];
					$total_dia=$total_dia+$row["neto"];
					$array[$apostante]=$row["neto"];
					echo ','.$array[$apostante];
				}
				if($apostante==8){
					$apostante=0;
					echo ','.$total_dia;
					$total_dia=0;
					}
			}mysql_free_result($result2);
			if($apostante>0){
				for (;$apostante<8;$apostante++){
					echo ',0';
				}
				echo ','.$total_dia;
			}
			echo ']]);';*/
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
					$array[$apostante]=$row["neto"];
				}else{
					for($ii=1;$ii<9;$ii++){
						if($array[$ii]==null){
							echo ',0';
						}else{
							echo ','.$array[$ii];
						}
					}
					for($ii=1;$ii<9;$ii++){
						$array[$ii]=0;
					}
					
					echo ','.$total_dia;
					echo '],';
					$total_dia=0;
					$date = date_create($row["fec"]);
					echo '[\''.date_format($date, 'd/m/Y').'\'';

					$fecha_ini=$row["fec"];
					$total_dia=$total_dia+$row["neto"];
					$array[$apostante]=$row["neto"];
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
							<!--<li><a href="../pages/cuartos.php">Cuartos de final</a></li>
							<li><a href="../pages/semifinal.php">Semifinal</a></li>
							<li ><a href="../pages/final.php">Final</a></li>-->
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


<!-- FASE DE GRUPOS-->
<h3>Fase de grupos</h3>

<?
	
	//Clasificación fase de grupos
	$result=mysql_query("Select *,  @rownum:=@rownum+1 AS pos from (
select culo.apos, ap.nombre, round(sum(culo.ganancia),2) as neto from 
(select p.apostante as apos , if(a.acertada=1, a.apostado*a.cotizacion-a.apostado,a.apostado*-1)  as ganancia  
from partidos p, apuestas a where p.id = a.partido and a.acertada>0)  as culo, apostantes ap where ap.id=culo.apos group by apos order by neto desc)
puntuacio, (SELECT @rownum:=0) r;");			
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
  while ($row=mysql_fetch_array($result))
	{
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
				<!--container -->
			</div>
		</div>
	</div>

	<script
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../bootstrap_new/js/bootstrap.min.js"></script>
</body>
</html>



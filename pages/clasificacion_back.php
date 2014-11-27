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


    <!-- CANVAS JS-->
    <script type="text/javascript">
		window.onload = function () {

//**********GANANACIA DE APOSTNATE POR PARTIDO APOSTADO

//SE GENERA EL ARRAY DE DATOS EN PHP
<?php
	$query='SELECT a.nombre,partido, ROUND(ganancia,2) as ganancia FROM (
				SELECT 
				    partido, 
				    SUM(
				        CASE acertada
				            WHEN 1 THEN (cotizacion*apostado)-apostado
				            WHEN 2 THEN (-1*apostado)
				        END 
				    ) as ganancia
				FROM apuestas
				WHERE acertada >0
				GROUP BY partido) as aux
			LEFT JOIN partido_apostante pa ON pa.idpartido=aux.partido
			LEFT JOIN apostantes a ON a.id=pa.idapostante
			INNER JOIN partidos p ON p.id=aux.partido';
			if(isset($_GET["fase"]) && $_GET["fase"]!='total'){

				if($_GET["fase"]=='octavos_c'){
					$query.=' and (p.fase=\'GRUPOS\' or p.fase=\'OCTAVOS\')';
				}else{
					$query.=' and p.fase=\''.$_GET["fase"].'\'';
				}
			}
			
			$query.=' ORDER BY a.nombre, partido';


	$result=mysql_query($query);
	if(!$result){
		die('Error: ' . mysql_error());
	}

	$apostador='';
	echo 'var arrDatos=[';
	$i=1;
	while ($row=mysql_fetch_array($result)){
		if($apostador!=$row["nombre"]){
			$i=1;
			if($apostador!=''){
				echo ']},';
			}
			echo '{type: "spline",name:"'.$row["nombre"].'",showInLegend: true,dataPoints:[{x:'.$i.',y:'.$row["ganancia"].'}';
			$apostador=$row["nombre"];

		}else{
			echo ',{x:'.$i.',y:'.$row["ganancia"].'}';
		}
		$i++;
	}mysql_free_result($result);	
	echo ']}];';
?>

			var chart = new CanvasJS.Chart("chart_div_canvasjs", {            
				title:{
					text: "Evolución de apostante por partido"
				},
				data:arrDatos,				
				axisY:{
					//prefix: "€",
					suffix: "€",
					stripLines:[{
					 			value:0,
								color:"red",
					            }
					]
				},
				axisX:{
					interval: 1
				},toolTip:{             
        			content: function(e){
          				var content;
          				content = e.entries[0].dataSeries.name + " <strong>"+e.entries[0].dataPoint.y  ;
          				return content;
        			},
      			}
			});
			chart.render();





//**********GANANACIA ACUMULADA DEL APOSTNATE POR PARTIDO APOSTADO

//SE GENERA EL ARRAY DE DATOS EN PHP
<?php
	$query='SELECT a.nombre,partido, ROUND(ganancia,2) as ganancia FROM (
				SELECT 
				    partido, 
				    SUM(
				        CASE acertada
				            WHEN 1 THEN (cotizacion*apostado)-apostado
				            WHEN 2 THEN (-1*apostado)
				        END 
				    ) as ganancia
				FROM apuestas
				WHERE acertada >0
				GROUP BY partido) as aux
			LEFT JOIN partido_apostante pa ON pa.idpartido=aux.partido
			LEFT JOIN apostantes a ON a.id=pa.idapostante
			INNER JOIN partidos p ON p.id=aux.partido';
	


	
	if(isset($_GET["fase"]) && $_GET["fase"]!='total'){

		if($_GET["fase"]=='octavos_c'){
			$query=$query.' and (p.fase=\'GRUPOS\' or p.fase=\'OCTAVOS\')';
		}else{
			$query=$query.' and p.fase=\''.$_GET["fase"].'\'';
		}
	}
	
	$query.=' ORDER BY a.nombre, partido';



	$result=mysql_query($query);
	if(!$result){
		die('Error: ' . mysql_error());
	}

	$apostador='';
	echo 'var arrDatos2=[';
	$i=1;
	$gan=0;
	while ($row=mysql_fetch_array($result)){
		if($apostador!=$row["nombre"]){
			$i=1;
			$gan=$row["ganancia"];
			if($apostador!=''){
				echo ']},';
			}
			echo '{type: "spline",name:"'.$row["nombre"].'",showInLegend: true,dataPoints:[{x:'.$i.',y:'.$gan.'}';
			$apostador=$row["nombre"];

		}else{
			$gan=$gan+$row["ganancia"];
			echo ',{x:'.$i.',y:'.$gan.'}';
		}
		$i++;
	}mysql_free_result($result);	
	echo ']}];';
?>

			var chart2 = new CanvasJS.Chart("chart2_div_canvasjs", {            
				title:{
					text: "Evolución acumulada de apostante por partido"
				},
				data:arrDatos2,				
				axisY:{
					//prefix: "€",
					suffix: "€",
					stripLines:[{
					 			value:0,
								color:"red",
					            }
					]
					},
				axisX:{
					interval: 1
				},toolTip:{             
        			content: function(e){
          				var content;
          				content = e.entries[0].dataSeries.name + " <strong>"+e.entries[0].dataPoint.y  ;
          				return content;
        			},
      			}
			});
			chart2.render();			
		}
    </script>



</head>
<body>
<!--Menu-->
	<?php 
		$page="clasificacion"; 
		include "../pages/menu.php"; 
	?>


<? 
	include_once "../pages/iconos_usuarios.php";
?>

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
			<li <? if($_GET["fase"]=="octavos_c"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=octavos_c">Octavos Clasif.</a>
			</li>
			<li <? if($_GET["fase"]=="cuartos"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=cuartos">Cuartos</a>
			</li>
			<li <? if($_GET["fase"]=="semifinal"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=semifinal">Semifinal</a>
			</li>
			<li <? if($_GET["fase"]=="final"){ echo 'class="active"';}?>>
				<a href="./clasificacion.php?fase=final">Final</a>
			</li>
		</ul>

<? 
	
	if($_GET["fase"]=="grupos"){ echo '<h3>Fase de grupos</h3>';}
	else if($_GET["fase"]=="octavos"){ echo '<h3>Octavos de final</h3>';}
	else if($_GET["fase"]=="octavos_c"){ echo '<h3>Clasificación para Cuartos <small> Grupos + Octavos</small></h3>';}
	else if($_GET["fase"]=="cuartos"){ echo '<h3>Cuartos de final</h3>';}
	else if($_GET["fase"]=="semifinal"){ echo '<h3>Semifinal</h3>';}
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
			FROM (
				SELECT 
					ap.id as apos , 
					if(a.acertada=1, a.apostado*a.cotizacion-a.apostado,a.apostado*-1)  as ganancia  
				FROM 
					partidos p, 
					apuestas a,
					partido_apostante pa,
					apostantes ap,
					partido_apost_apuesta pap
				WHERE 
					p.id = a.partido 
					and a.acertada>0
					and pa.idpartido=p.id
					and pa.idapostante=ap.id
					and pap.idapuesta=a.id
					and pap.idpartidoapost=pa.id";
				
	$queryParte2=")  as culo, 
				apostantes ap 
			WHERE 
				ap.id=culo.apos 
			GROUP BY apos 
			ORDER BY neto desc
			) puntuacio, (SELECT @rownum:=0) r;";
	
	$queryFase ="";
	if($_GET["fase"]=="grupos"){
		$queryFase=" and p.fase = 'GRUPOS' ";
	}else if($_GET["fase"]=="octavos"){
		$queryFase=" and p.fase = 'OCTAVOS' ";
	}else if($_GET["fase"]=="octavos_c"){
		$queryFase=" and (p.fase = 'OCTAVOS' or p.fase = 'GRUPOS')";
	}else if($_GET["fase"]=="cuartos"){
		$queryFase=" and p.fase = 'CUARTOS' ";
	}else if($_GET["fase"]=="semifinal"){
		$queryFase=" and p.fase = 'SEMIFINAL' ";
	}else if($_GET["fase"]=="final"){
		$queryFase=" and p.fase = 'FINAL' ";
	}
			
	$result=mysql_query($queryParte1.$queryFase.$queryParte2);
	if(!$result){
		die('Error: ' . mysql_error());
	}		
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
                  
        
        	<div id="chart_div_canvasjs" class="col-xs-12 col-sm-10 col-sm-offset-1" style="height: 450px;"></div>
        	<div class="h1 col-xs-12"></div>
        	<div id="chart2_div_canvasjs" class="col-xs-12 col-sm-10 col-sm-offset-1" style="height: 450px;"></div>
        

		<!-- <div id="chart_div" style="width: 900px; height: 500px;"></div>        
		<div id="chart_div2" style="width: 900px; height: 500px;"></div>
		<div id="chart_div3" style="width: 900px; height: 500px;"></div>

	-->
	</div>



 	<script src="../bootstrap/js/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../canvasjs/canvasjs.min.js"></script>

</body>
</html>



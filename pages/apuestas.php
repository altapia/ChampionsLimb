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
		$page="apuestas"; 
		include "../pages/menu.php"; 
	?>

<div class="container">
	<h2>Apuestas</h2>

	<div class="form-group col-sm-2">
		<select onchange="location = this.options[this.selectedIndex].value;" class="form-control input-xs">
			<option>Fechas...</option>
			<? 
				//Ejecutamos la sentencia SQL
				$result=mysql_query("select distinct fecha from partidos order by fecha asc");
				while ($row=mysql_fetch_array($result)) {
					$date = date_create($row["fecha"]);
				  echo '<option value=apuestas.php?fecha='.$row["fecha"].'>'.date_format($date, 'd/m/Y');
				  echo '</option>';
				}mysql_free_result($result);
			?>
		</select>
	</div>

<?php

	if( $_GET["fecha"]==""){
		$fecha_aux = date("Y-m-d");
	}else{
		$fecha_aux =  $_GET["fecha"];
	}
	$date = date_create($fecha_aux);

?>
	<div class="col-sm-12">
		<h4>Apuestas del <?php echo date_format($date, 'd/m/Y');?></h4>
	</div>
<?php
	$query='
		SELECT 
			a.partido, 
			a.apuesta, 
			a.apostado, 
			a.cotizacion, 
			a.acertada as acertada, 
			e.nombre as local, 
			e.escudo as escLocal, 
			f.nombre as visitante, 
			f.escudo as escVisit,
		    apos.nombre	as apostante		
		FROM 
			apuestas a
			LEFT JOIN partidos p ON p.id=a.partido
			LEFT JOIN equipos e ON e.id=p.local 
			LEFT JOIN equipos f ON f.id=p.visitante
			LEFT JOIN partido_apostante par_apos ON par_apos.idpartido=p.id
			LEFT JOIN apostantes apos on apos.id=par_apos.idapostante
		WHERE p.fecha=\''.$fecha_aux.'\' 
		ORDER BY p.fecha, p.hora, a.partido ASC';

	//Ejecutamos la sentencia SQL

		$result=mysql_query($query);
		$numero_filas = mysql_num_rows($result);
		echo mysql_error();
?>
        
        <table class="table table-condensed">
            <thead>
                <tr>						
                    <th class="text-center">Partido</th>
                    <th class="text-center">Apuesta</th>
                    <th class="text-center">Apostado</th>
                    <th class="text-center hidden-xs">Cotización</th>
                    <th class="text-center">Ganancia</th>
                </tr>
            </thead>
            <tbody>
            
<?php 

  	$partido=0;
  	$numApuestas=0;
  	$filaPartido="";
  	$filasApuestas="";
  	$iniFilaPartido="";
  	$signoCotizacion='';
	while ($row=mysql_fetch_array($result)) {	
		
		if($row["cotizacion"]!=''){
			$signoCotizacion='@';
		}else{
			$signoCotizacion='';
		}

		$iconoApuesta='';														
		if($row["acertada"]==1){
			$iconoApuesta= '<span class="label label-success"><span class="glyphicon glyphicon-ok"></span></span> ';
		}else if($row["acertada"]==2){
			$iconoApuesta= '<span class="label label-danger"><span class="glyphicon glyphicon-remove"></span></span> ';
		}else{
			$iconoApuesta= '<span class="glyphicon glyphicon-time"></span> ';
		}
												
		if($partido==$row["partido"]){
				$numApuestas=$numApuestas+1;							
				$filasApuestas=$filasApuestas.'<tr><td style="vertical-align: middle;">'.$iconoApuesta.$row["apuesta"].'</td>
											<td style="text-align: center;vertical-align: middle;">'.$row["apostado"].'&euro;
											 <span class="visible-xs">'.$signoCotizacion.$row["cotizacion"].'</span>
											</td>
											<td class="hidden-xs" style="text-align: center;vertical-align: middle;">';
				$filasApuestas.=$signoCotizacion.$row["cotizacion"].'</td>';

				$ganancia=0;
				if($row["acertada"]<2){
					if( $row["cotizacion"]==null) {
						$ganancia=0;
					}else{
						$ganancia= ($row["apostado"] * $row["cotizacion"])-$row["apostado"];
					}
				}else if($row["acertada"]==2){
					$ganancia=$row["apostado"] * -1;
				}
				$filasApuestas.='<td style="text-align: center;vertical-align: middle;">'.$ganancia.'&euro;</td>';
		}else{
			if($partido!=0){
				$iniFilaPartido='<tr><td rowspan="'.$numApuestas.'"'; 
				echo $iniFilaPartido;
				echo $filaPartido;
				echo $filasApuestas;
			}						
			$partido=$row["partido"];
			$numApuestas=1;						 
			$filaPartido='style="text-align: center;vertical-align: middle;"><span class="hidden-sm hidden-xs">'.$row["local"].' </span><img src="../images/escudos/'.$row["escLocal"].'" style="margin:5px;"><br class="visible-xs"> <span class="label label-primary">'.$row["apostante"].'</span> <br class="visible-xs"><img src="../images/escudos/'.$row["escVisit"].'" style="margin:5px;"><span class="hidden-sm hidden-xs"> '.$row["visitante"].'</span></td>';
			
			
				
			$filasApuestas='<td style="vertical-align: middle;">'
							.$iconoApuesta.$row["apuesta"].'</td>
							<td style="text-align: center;vertical-align: middle;">'.$row["apostado"].'&euro;
							 <span class="visible-xs">'.$signoCotizacion.$row["cotizacion"].'</span>
							</td>
							<td class="hidden-xs" style="text-align: center;vertical-align: middle;">';					

			$filasApuestas.=$signoCotizacion.$row["cotizacion"].'</td>';

			$ganancia=0;
			if($row["acertada"]<2){
				if( $row["cotizacion"]==null) {
					$ganancia=0;
				}else{
					$ganancia= ($row["apostado"] * $row["cotizacion"])-$row["apostado"];
				}
			}else if($row["acertada"]==2){
				$ganancia=$row["apostado"] * -1;
			}
			$filasApuestas.='<td style="text-align: center;vertical-align: middle;">'.$ganancia.'&euro;</td></tr>';
		}															
	}mysql_free_result($result);			
	
	if($numero_filas>0){
		$iniFilaPartido='<tr><td rowspan="'.$numApuestas.'"'; 	
		echo $iniFilaPartido;
		echo $filaPartido;
		echo $filasApuestas;				
	}
				
?>
        </tbody>
    </table>                   

 </div>

<script src="../bootstrap/js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>


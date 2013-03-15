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
						<ul class="dropdown-menu">
							<li><a href="../pages/faseGrupos.php">Fase de grupos</a></li>
							<li><a href="../pages/octavos.php">Octavos de final</a></li>
							<li><a href="../pages/cuartos.php">Cuartos de final</a></li>
<!--							<li><a href="../pages/semifinal.php">Semifinal</a></li>
							<li><a href="../pages/final.php">Final</a></li>-->
						</ul>
					</li>
					<li class="active"><a href="../pages/apuestas.php">Apuestas</a></li>
          <li><a href="../pages/clasificacion.php">Clasificación</a></li>
				</ul>
			</div>
		</div>
	</div>

<div class="span10">
    <div class="container span8">
        <h2>Apuestas</h2>
					
<? include "../pages/conexion_bd.php"; ?>


<!-- Select grupo -->
 <div class="alert alert-success">

    
  <!-- Select fecha-->
  <!-- <strong> Seleccione una jornada:</strong> -->
  <select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
	<option>Jornadas...</option>
	<? 
		//Ejecutamos la sentencia SQL
		$result=mysql_query("select distinct fecha from partidos order by fecha asc");
		$jornada=0;
		while ($row=mysql_fetch_array($result)) {
			$jornada=$jornada+1;
			$date = date_create($row["fecha"]);
		  echo '<option value=apuestas.php?fecha='.$row["fecha"].'&jornada='.$jornada.'>Jornada '.$jornada.' ('.date_format($date, 'd/m/Y');
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
		$result=mysql_query("select distinct fecha from partidos order by fecha asc");
		while ($row=mysql_fetch_array($result)) {
			$date = date_create($row["fecha"]);
		  echo '<option value=apuestas.php?fecha='.$row["fecha"].'>'.date_format($date, 'd/m/Y');
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
		  echo '<option value="apuestas.php?apostante='.$row["id"].'&apostanteNom='.$row["nombre"].'">'.$row["nombre"].'</option>';
		}mysql_free_result($result);
	?>
</select>
<!-- Fase -->
<select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
		<option>Fase...</option>
		<option value="apuestas.php?fase=OCTAVOS">Octavos</option>
		<option value="apuestas.php?fase=CUARTOS">Cuartos</option>
		<option value="apuestas.php?fase=SEMIFINAL">Semifinal</option>
		<option value="apuestas.php?fase=FINAL">Final</option>
	</select> 
</div>
<!-- FIN Select grupo -->

<?

if( $_GET["fecha"]=="" && $_GET["grupo"]=="" && $_GET["apostante"]=="" && $_GET["fase"]==""){
	$fecha_aux = date("Y-m-d");
}else{
	$fecha_aux =  $_GET["fecha"];
}

if($_GET["grupo"]!="" || $fecha_aux!="" || $_GET["apostante"]!="" || $_GET["fase"]!=""){ 
	if($_GET["grupo"]!=""){
		if($_GET["grupo"]=="Todos"){
			echo '<h3>Todos los Encuentros</h3>';
			$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal, f.escudo as escVisit 
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id
					ORDER BY p.fecha, p.id asc';
		}else{
			echo '<h3>GRUPO '.$_GET["grupo"].'</h3>';
			$query='SELECT p.id, e.nombre as local , f.nombre as visitante, p.fecha, p.hora, p.apostante, p.resultado, e.escudo as escLocal , f.escudo as escVisit
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and e.grupo =\''.$_GET["grupo"].'\' 
					ORDER BY p.fecha, p.id asc';
		}
		$result=mysql_query($query);
	}else if($fecha_aux!=""){
		if($_GET["jornada"]!=""){
			echo '<h3>Apuestas de la jornada '.$_GET["jornada"].' </h3>';
			$query='
			select a.partido, a.apuesta, a.apostado, a.cotizacion, a.acertada as acertada, e.nombre as local, e.escudo as escLocal, f.nombre as visitante, f.escudo as escVisit
			from apuestas a, partidos p, equipos e, equipos f
			where p.id=a.partido and (p.fecha=\''.$fecha_aux.'\' or p.fecha=ADDDATE(\''.$fecha_aux.'\',1))
			and e.id=p.local and f.id=p.visitante
			order by p.fecha, p.hora, a.partido asc';
		}else{
			$date = date_create($fecha_aux);
			echo '<h3>Apuestas del '.date_format($date, 'd/m/Y').' </h3>';
			$query='
			select a.partido, a.apuesta, a.apostado, a.cotizacion, a.acertada as acertada, e.nombre as local, e.escudo as escLocal, f.nombre as visitante, f.escudo as escVisit
			from apuestas a, partidos p, equipos e, equipos f
			where p.id=a.partido and p.fecha=\''.$fecha_aux.'\' 
			and e.id=p.local and f.id=p.visitante
			order by p.fecha, p.hora, a.partido asc';
		}
	}else if($_GET["apostante"]!=""){
		
		echo '<h3>Apuestas de '.$_GET["apostanteNom"].' </h3>';
			$query='
			select a.partido, a.apuesta, a.apostado, a.cotizacion, a.acertada as acertada, e.nombre as local, e.escudo as escLocal, f.nombre as visitante, f.escudo as escVisit
			from apuestas a, partidos p, equipos e, equipos f
			where p.id=a.partido and p.apostante='.$_GET["apostante"].'
			and e.id=p.local and f.id=p.visitante
			order by p.fecha, p.hora, a.partido asc';
	}else if($_GET["fase"]!=""){
		
		echo '<h3>Apuestas de '.$_GET["fase"].' </h3>';
			$query='
			select a.partido, a.apuesta, a.apostado, a.cotizacion, a.acertada as acertada, e.nombre as local, e.escudo as escLocal, f.nombre as visitante, f.escudo as escVisit
			from apuestas a, partidos p, equipos e, equipos f
			where p.id=a.partido and  p.fase=\''.$_GET["fase"].'\'  
			and e.id=p.local and f.id=p.visitante
			order by p.fecha, p.hora, a.partido asc';
	}
	//Ejecutamos la sentencia SQL

		$result=mysql_query($query);
		$numero_filas = mysql_num_rows($result);
		echo mysql_error();
?>
        
        <table class="table table-condensed">
            <thead>
                <tr>						
                    <th style="text-align:center">Partido</th>
                    <th style="text-align:center">Apuesta</th>
                    <th style="text-align:center">Apostado</th>
                    <th style="text-align:center">Cotización</th>
                </tr>
            </thead>
            <tbody>
            
<? 

      	$partido=0;
      	$numApuestas=0;
      	$filaPartido="";
      	$filasApuestas="";
      	$iniFilaPartido="";
				while ($row=mysql_fetch_array($result)) {		
					
					$iconoApuesta='';														
					if($row["acertada"]==1){
						$iconoApuesta= '<span class="badge badge-success" style="padding-right: 1px;padding-left: 1px;"><i class="icon-ok icon-white"></i></span> ';
					}else if($row["acertada"]==2){
						$iconoApuesta= '<span class="badge badge-important" style="padding-right: 1px;padding-left: 1px;"><i class="icon-remove icon-white"></i></span> ';
					}else{
						$iconoApuesta= '<span class="badge badge-warning" style="padding-right: 1px;padding-left: 1px;"><i class="icon-time icon-white"></i></span> ';
					}
															
					if($partido==$row["partido"]){
							$numApuestas=$numApuestas+1;							
							$filasApuestas=$filasApuestas.'<tr><td style="text-align: center;vertical-align: middle;">'.$iconoApuesta.$row["apuesta"].'</td>
														<td style="text-align: center;vertical-align: middle;">'.$row["apostado"].'</td>
														<td style="text-align: center;vertical-align: middle;">'.$row["cotizacion"].'</td></tr>';
					}else{
						if($partido!=0){
							$iniFilaPartido='<tr><td rowspan="'.$numApuestas.'"'; 
							echo $iniFilaPartido;
							echo $filaPartido;
							echo $filasApuestas;
						}						
						$partido=$row["partido"];
						$numApuestas=1;						 
						$filaPartido='style="text-align: center;vertical-align: middle;">'.$row["local"].' <img src="../images/escudos/'.$row["escLocal"].'"> vs <img src="../images/escudos/'.$row["escVisit"].'"> '.$row["visitante"].'</td>';
						
						
							
						$filasApuestas='<td style="text-align: center;vertical-align: middle;">'
										.$iconoApuesta.$row["apuesta"].'</td>
										<td style="text-align: center;vertical-align: middle;">'.$row["apostado"].'</td>
										<td style="text-align: center;vertical-align: middle;">'.$row["cotizacion"].'</td></tr>';						
							
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
<? }?>
 </div>
 </div>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../bootstrap_new/js/bootstrap.min.js"></script>
</body>
</html>


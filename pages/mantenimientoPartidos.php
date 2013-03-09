<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="ISO-8859-1">
	<title>Champions Limb</title>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			padding-top: 60px;
		}
	</style>
	<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/bootstrap-datepicker.js"></script>
    <script language="javascript">
		/*$('.datepicker').datepicker();
		$('#dp1').datepicker().on('changeDate', function(ev){
			if (ev.date.valueOf() < startDate.valueOf()){
			  alert("aa");
			}
  		});*/
			
		var totalAct=0;
			
		$(function(){
			$('#dp1').datepicker({weekStart:1,format: 'yyyy-mm-dd'});
			$('#dp2').datepicker({weekStart:1,format: 'yyyy-mm-dd'});
		});
		
		function submitForm(){
			var sele = $("#selectLocal :selected").val();
			//alert(sele);
			var sele2 = $("#selectVisit :selected").val();
			var sele3 = $("#selectFase :selected").val();

			
			//alert(sele2);
			$("#local").val(sele);
			$("#visitante").val(sele2);
			$("#fase").val(sele3);
			$("#formulario").submit();
		};
		
		function borrar(i){
			window.location="./mantenimientoPartidos.php?query=borrar&grupo=<? echo $_GET["grupo"] ?>&idborrar="+i;
			
		}
		
		function borrarAP(i){
			window.location="./mantenimientoPartidos.php?query=deleteAP&id="+i;			
		}
		
		function actualizar(formulario){
			$('#form_' + formulario).submit();
		}
		
		function actualizarTodos(){
			/*$('#form_29 :input').each(function(){
					alert($(this).val());
			});*/
			totalAct=0;
			var wid_padre = $("#prog_bar_padre").css('width');
			var value_padre= parseInt(wid_padre, 10);
			
			var total= $('form[name|="form_actualizar"]').length;
			
			var incremento=value_padre/total;
			
			$('form[name|="form_actualizar"]').each(function(){
				//	 alert($(this).children('select[name|="equipoVisit"]').val());
				 var id= $(this).children('input[name|="id"]').val();
				 var parametros = {
					"fecha" : $(this).children('input[name|="fecha"]').val(),
					"equipoLocal" : $(this).children('select[name|="equipoLocal"]').val(),
					"equipoVisit" : $(this).children('select[name|="equipoVisit"]').val(),
					"apostador" : $(this).children('select[name|="apostador"]').val(),
					"hora" : $(this).children('input[name|="hora"]').val(),
					"id" : $(this).children('input[name|="id"]').val()
				};

				$.ajax({
					data:  parametros,
					url:   'actualizarPartidos.php',
					type:  'post',
					async: 'false',
					beforeSend: function () {
							$('#modal_ajax').modal('show');
					},
					success:  function (response) {
					
						$("#resultado").html($("#resultado").html()+'<br>'+response);
							
					},
					complete: function(){
						//alert('actualizado id: '+ id);
						
						var wid = $("#prog_bar").css('width');
						var value= parseInt(wid, 10);
						//var value = wid.substring(0,wid.indexof('px'));
						//alert(value+'--'+(value+(100/total)));
						
						var aux = value + incremento;
						$("#prog_bar").css('width',aux);
						//$("#resultado").html($("#resultado").html()+'<br>'+wid+'--'+total+'--'+aux);
						//$("#prog_bar").attr('style','width: '+(value+(100/total))+'%');
						
						totalAct=totalAct+1;
						if(totalAct==total){
							window.location.reload();
						}
					}
        		});
				
			});
	
			//window.location.reload();
		}
		
	</script>
</head>
<body> 
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="brand" href="../" style="vertical-align:middle"><img src="../images/champions-league-logo_trans.png" height="30"> CHAMPIONS<strong>Limb</strong></a>
				<ul class="nav">
					<!-- <li><a href="../">Home</a></li> -->
					<li class="dropdown active" id="menu1">
                    	<a class="dropdown-toggle" data-toggle="dropdown"  href="#">
                    		 Partidos
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu active">
							<li><a href="../pages/faseGrupos.php">Fase de grupos</a></li>
							<li><a href="../pages/octavos.php">Octavos de final</a></li>
							<!-- <li class="active"><a href="#">Cuartos de final</a></li>
							<li><a href="../pages/semifinal.php">Semifinal</a></li>
							<li><a href="../pages/final.php">Final</a></li> -->
						</ul>
					</li>
                    <li><a href="../pages/clasificacion.php">Clasificación</a></li>
				</ul>
			</div>
		</div>
	</div>

			
                        
<? include "../pages/conexion_bd.php"; ?>

<?
	function selectApostadores($i){
		$select = '<select name="apostador" style="width: 100px;"><option value="0"></option>';
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
	
	function selectEquipos($i,$local){
		if($local==1){
			$select ='<select class="span2" name="equipoLocal"><option></option>';
		}else{
			$select ='<select class="span2" name="equipoVisit"><option></option>';
		}
		if($_GET["grupo"]==''){
			$query='select id, nombre from equipos order by nombre asc;';
		}else{
			$query='select id, nombre from equipos where grupo=\''.$_GET["grupo"].'\' order by nombre asc;';
		}
		$result=mysql_query($query);
		while ($row=mysql_fetch_array($result)) {
			if($i==$row["id"]){
				$select=$select.'<option value="'.$row["id"].'" selected="selected">'.$row["nombre"].'</option>';
			}else{
				$select=$select.'<option value="'.$row["id"].'">'.$row["nombre"].'</option>';
			}
		}mysql_free_result($result);
		
		$select=$select.'</select>';
		return $select;
	}
?>

<?
	if($_GET["query"]=="insert"){
		$local= $_GET["local"];
		$visitante= $_GET["visitante"];
		$fecha= $_GET["fecha"];
		$hora= $_GET["hora"];
		$fase= $_GET["fase"];
		//$fase= 'GRUPO'.$_GET["grupo"];
		
		if(!mysql_query("INSERT INTO partidos (LOCAL, VISITANTE, FECHA, HORA, FASE) VALUES ('$local', '$visitante', '$fecha', '$hora', '$fase') ")){
  			die('Error: ' . mysql_error());
 	 	}
	}else if($_GET["query"]=="borrar"){
		$idborrar=$_GET["idborrar"];
		if(!mysql_query("DELETE FROM partidos where id=$idborrar ")){
  			die('Error: ' . mysql_error());
 	 	}
	}else if($_GET["query"]=="update"){
		$fecha= $_GET["fecha"];
		$equipoLocal= $_GET["equipoLocal"];
		$equipoVisit= $_GET["equipoVisit"];
		$apostador= $_GET["apostador"];
		if($apostador=='0'){ $apostador='NULL';}
		$resultado= $_GET["resultado"];
		$hora=$_GET["hora"];
		$id=$_GET["id"];
		if(!mysql_query("UPDATE partidos SET fecha='$fecha', local=$equipoLocal, visitante=$equipoVisit, apostante=$apostador, resultado='$resultado', hora='$hora'
							WHERE id=$id")){
  			die('Error: ' . mysql_error());
 	 	}
	}else if($_GET["query"]=="insertAP"){
		$apuesta= $_GET["apuesta"];
		$apostado= $_GET["apostado"];
		$cotizacion= $_GET["cotizacion"];
		$partido= $_GET["partido"];
		
		if(!mysql_query("INSERT INTO apuestas (apuesta, apostado, cotizacion, partido) VALUES ('$apuesta', $apostado, $cotizacion, $partido) ")){
  			die('Error: ' . mysql_error());
 	 	}
	}else if($_GET["query"]=="updateAP"){
		$apuesta= $_GET["apuesta"];
		$apostado= $_GET["apostado"];
		$cotizacion= $_GET["cotizacion"];
		$acertada= $_GET["acertada"];
		$id= $_GET["id"];
		if(!mysql_query("UPDATE apuestas SET apuesta='$apuesta', apostado=$apostado, cotizacion=$cotizacion, acertada=$acertada WHERE id=$id")){
  			die('Error: ' . mysql_error());
 	 	}
	}else if($_GET["query"]=="deleteAP"){
		$id= $_GET["id"];
		if(!mysql_query("DELETE FROM apuestas where id=$id")){
  			die('Error: ' . mysql_error());
 	 	}
	}
?>

<div class="span8">
	<div class="container">
		<h1>Mantenimiento de partidos</h1>
		
		<div class="alert alert-success">
			
					
		
    <select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
        <option>Jornadas...</option>
        <? 
            //Ejecutamos la sentencia SQL
            $result=mysql_query("select distinct fecha, SUBSTRING(fase,1,5) as fase from partidos order by fecha asc");
            $jornada=0;
            while ($row=mysql_fetch_array($result)) {
                $jornada=$jornada+1;
                $date = date_create($row["fecha"]);
                
                echo '<option value=mantenimientoPartidos.php?fecha='.$row["fecha"].'&jornada='.$jornada.'>Jornada '.$jornada.' ('.date_format($date, 'd/m/Y');
           	 	$row=mysql_fetch_array($result);
               	$date = date_create($row["fecha"]);
               	//echo substr($row["fase"],0,5);
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
		   echo '<option value="mantenimientoPartidos.php?fecha='.$row["fecha"].'">'.date_format($date, 'd/m/Y').'</option>';
		}mysql_free_result($result);
	?>
</select>

	<select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">
		<option>Fase...</option>
		<option value="mantenimientoPartidos.php?fase=OCTAVOS">Octavos</option>
		<option value="mantenimientoPartidos.php?fase=CUARTOS">Cuartos</option>
		<option value="mantenimientoPartidos.php?fase=SEMIFINAL">Semifinal</option>
		<option value="mantenimientoPartidos.php?fase=FINAL">Final</option>
	</select> 

</div>
<? 
if($_GET["grupo"]!="" || $_GET["fecha"]!="" || $_GET["fase"]!=""){ 
	if($_GET["grupo"]!=""){
		if($_GET["grupo"]=="Todos"){
			echo '<h3>Todos los partidos</h3>';
			$query='select p.id, e.escudo as escudoLocal,f.escudo as escudoVisit, e.nombre as local,e.id as localid, f.nombre as visitante, f.id as visitid, p.fecha, p.hora , p.apostante, p.resultado
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id
					ORDER BY p.fecha, p.id asc';
		}else{
			echo '<h3>GRUPO '.$_GET["grupo"].'</h3>';
			$query='select p.id, e.escudo as escudoLocal,f.escudo as escudoVisit, e.nombre as local,e.id as localid, f.nombre as visitante, f.id as visitid, p.fecha, p.hora , p.apostante, p.resultado
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and e.grupo =\''.$_GET["grupo"].'\' 
					ORDER BY p.fecha, p.id asc';
		}
		$result=mysql_query($query);
	}else if($_GET["fecha"]!="") {
		if($_GET["jornada"]!=""){
		echo '<h3>Partidos de la jornada '.$_GET["jornada"].' </h3>';
			$query='select p.id, e.escudo as escudoLocal,f.escudo as escudoVisit, e.nombre as local,e.id as localid, f.nombre as visitante, f.id as visitid, p.fecha, p.hora , p.apostante, p.resultado
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and (p.fecha=\''.$_GET["fecha"].'\' or p.fecha=ADDDATE(\''.$_GET["fecha"].'\',1)) 
					ORDER BY p.fecha, p.id asc';
		}else{
			$date = date_create($_GET["fecha"]);
			echo '<h3>Encuentros del '.date_format($date, 'd/m/Y').' </h3>';
			$query='select p.id, e.escudo as escudoLocal,f.escudo as escudoVisit, e.nombre as local,e.id as localid, f.nombre as visitante, f.id as visitid, p.fecha, p.hora , p.apostante, p.resultado
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and p.fecha=\''.$_GET["fecha"].'\'  
					ORDER BY p.fecha, p.id asc';
		}
	}else{
		echo '<h3>Encuentros de '.$_GET["fase"].' </h3>';
			$query='select p.id, e.escudo as escudoLocal,f.escudo as escudoVisit, e.nombre as local,e.id as localid, f.nombre as visitante, f.id as visitid, p.fecha, p.hora , p.apostante, p.resultado
					FROM partidos p, equipos e, equipos f 
					WHERE p.local=e.id and p.visitante=f.id and p.fase=\''.$_GET["fase"].'\'  
					ORDER BY p.fecha, p.id asc';
	}
	
	//Ejecutamos la sentencia SQL
	$result=mysql_query($query);




/*****/

?>	
		
<table class="table table-bordered">
    <thead>
    <!-- 
        <tr>	
        
	       	<th style="text-align:center"></th>		
	       	<th style="text-align:center">Fecha y Hora</th>					
	        <th style="text-align:center">Local</th>
	        <th style="text-align:center">Visitante</th> 
	        <th style="text-align:center">Apostante</th>    
	        <th style="text-align:center"></th> 
                 
        </tr>   -->     
    </thead> 
    <tbody>

	<?

while ($row=mysql_fetch_array($result)) {
		
		
		echo '<tr><td colspan="6" style="vertical-align: middle;">';
		echo '<form class="form form-inline"id="form_'.$row["id"].'" name="form_actualizar" style="margin: 0px;">';
		echo'<a class="btn btn-mini btn-danger" href="javascript:borrar('.$row["id"].');"><i class="icon-remove icon-white"></i> Borrar</a> ';
			echo '<a class="btn btn-mini btn-success" href="javascript:actualizar('.$row["id"].');">
				<i class="icon-upload icon-white"></i> Actualizar</a> ';
		$date = date_create($row["fecha"]);
		echo '<input class="input-small" id="dp2" type="text" name ="fecha" value="'.date_format($date, 'Y-m-d').'"/> ';
		echo '<input class="input-small" type="text" name ="hora" value="'.$row["hora"].'"/> ';
		echo selectEquipos($row["localid"],1);
		echo ' <img src="../images/escudos/'.$row["escudoLocal"].'"> ';
		echo '<img src="../images/escudos/'.$row["escudoVisit"].'"> ';
		echo selectEquipos($row["visitid"],2);
		echo selectApostadores($row["apostante"]);
		echo ' <a class="btn btn-mini btn-info" href="#myModal'.$row["id"].'" role="button" data-toggle="modal">
			<i class="icon-star icon-white"></i> Apuestas</a> ';
		echo '<input type="hidden" name="query" value="update"/> ';
		echo '<input type="hidden" name="id" value="'.$row["id"].'"/> ';
		echo '</form>';
		echo '</td></tr>';
		

}mysql_free_result($result);
?>
	<tr><td colspan="6" style="background-color:#CCC;text-align: center;vertical-align: middle;">
    		<a class="btn btn-mini btn-success" href="javascript:actualizarTodos();">
				<i class="icon-upload icon-white"></i> 
                Actualizar Todos
            </a>
		</td>
	</tr>
	<form id="formulario">
            <tr>
                <td colspan="2" style="text-align: center;vertical-align: middle;"><input id="dp1"name="fecha" type="text"  value="2012-12-05"/></td>
                <td rowspan="2" id="selectLocal" style="text-align: center;vertical-align: middle;"><? echo selectEquipos(0,1); ?></td>
                <td rowspan="2" id="selectVisit" style="text-align: center;vertical-align: middle;"><? echo selectEquipos(0,2); ?></td>
                <td rowspan="2" id="selectApostante" style="text-align: center;vertical-align: middle;"><? echo selectApostadores($row["apostante"]); ?></td>';
                <td rowspan="2" id="selectFase" style="text-align: center;vertical-align: middle;">
                	<select>
                		<option value="GRUPOA">Grupo A</option>
                		<option value="GRUPOB">Grupo B</option>
                		<option value="GRUPOC">Grupo C</option>
                		<option value="GRUPOD">Grupo D</option>
                		<option value="GRUPOE">Grupo E</option>
                		<option value="GRUPOF">Grupo F</option>
                		<option value="GRUPOG">Grupo G</option>
                		<option value="GRUPOH">Grupo H</option>
                		<option value="OCTAVOS">Octavos</option>
                		<option value="CUARTOS">Cuartos</option>
                		<option value="SEMIFINAL">Semifinal</option>
                		<option value="FINAL">Final</option>
                	</select> 
                	</td>';
            </tr>
            <tr><td colspan="2" style="text-align: center;vertical-align: middle;">
			<input name="hora" type="text" placeholder="hh:mm:ss" value="20:45:00"/></td></tr>
            <tr><td colspan="6" style="text-align: center;">
			<button type="button" class="btn btn-success" onClick="submitForm();">Insertar</button></td></tr>
            <input type="hidden" name="local" id="local"/>
            <input type="hidden" name="visitante" id="visitante"/>
            <input type="hidden" name="fase" id="fase"/>
            <input type="hidden" name="grupo" value="<? echo $_GET["grupo"]; ?>"/>
            <input type="hidden" name="query" value="insert"/>
        </form>
    </tbody> 
</table>    
<? }else{ ?>  
		
<table class="table table-bordered">
    <thead>
     
        <tr>	
        
	       	<th style="text-align:center"></th>		
	       	<th style="text-align:center">Fecha y Hora</th>					
	        <th style="text-align:center">Local</th>
	        <th style="text-align:center">Visitante</th> 
	        <th style="text-align:center">Apostante</th>    
	        <th style="text-align:center">Fase / Grupo</th> 
                 
        </tr>    
    </thead> 
    <tbody>
      <form id="formulario">
            <tr>
                <td colspan="2" style="text-align: center;vertical-align: middle;"><input id="dp1"name="fecha" type="text"  value="2012-12-05"/></td>
                <td rowspan="2" id="selectLocal" style="text-align: center;vertical-align: middle;"><? echo selectEquipos(0,1); ?></td>
                <td rowspan="2" id="selectVisit" style="text-align: center;vertical-align: middle;"><? echo selectEquipos(0,2); ?></td>
                <td rowspan="2" id="selectApostante" style="text-align: center;vertical-align: middle;"><? echo selectApostadores($row["apostante"]); ?></td>';
                <td rowspan="2" id="selectFase" style="text-align: center;vertical-align: middle;">
                	<select>
                		<option value="GRUPOA">Grupo A</option>
                		<option value="GRUPOB">Grupo B</option>
                		<option value="GRUPOC">Grupo C</option>
                		<option value="GRUPOD">Grupo D</option>
                		<option value="GRUPOE">Grupo E</option>
                		<option value="GRUPOF">Grupo F</option>
                		<option value="GRUPOG">Grupo G</option>
                		<option value="GRUPOH">Grupo H</option>
                		<option value="OCTAVOS">Octavos</option>
                		<option value="CUARTOS">Cuartos</option>
                		<option value="SEMIFINAL">Semifinal</option>
                		<option value="FINAL">Final</option>
                	</select> 
                </td>';
            </tr>
            <tr><td colspan="2" style="text-align: center;vertical-align: middle;">
			<input name="hora" type="text" placeholder="hh:mm:ss" value="20:45:00"/></td></tr>
            <tr><td colspan="6" style="text-align: center;">
			<button type="button" class="btn btn-success" onClick="submitForm();">Insertar</button></td></tr>
            <input type="hidden" name="local" id="local"/>
            <input type="hidden" name="visitante" id="visitante"/>
            <input type="hidden" name="fase" id="fase"/>
            <input type="hidden" name="grupo" value="<? echo $_GET["grupo"]; ?>"/>
            <input type="hidden" name="query" value="insert"/>
        </form>
    </tbody> 
</table>    
 
<? }?> 



  
		</div> 
		</div> 
	</div>


<!-- divs modales -->
<?
if($query!=''){
$result=mysql_query($query);
while ($row=mysql_fetch_array($result)) {
$resultAP=mysql_query('SELECT id, apuesta, cotizacion, apostado, acertada FROM apuestas WHERE partido = '.$row["id"].' order by id');
		
?>
<div class="modal hiden fade in" id="myModal<? echo $row["id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true" style="display: none;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Apuestas para el partido </h3>
  </div>
  <div class="modal-body">
        <table class="table table-condensed"> 
        			<thead> 
                <tr>
                		<th style="width: 20px;"></th>
                        <th style="width: 120px;">Apuesta</th>
                        <th style="width: 30px;">Cotización</th>
                        <th style="width: 30px;">Apostado</th>
                        <th style="width: 70px;">Estado</th>
                        <th style="width: 40px;"></th>
                </tr>
                </thead> 
                <tbody>
                <?
	                while ($rowAP=mysql_fetch_array($resultAP)) {		                	
	                		echo '<tr><td colspan="6">';
	                			echo '<form class="form-inline" id="apues'.$rowAP["id"].'" action="" style="margin: 0px;">';
	                			echo '<a class="btn btn-mini btn-danger" href="javascript:borrarAP('.$rowAP["id"].');">
	                						<i class="icon-remove icon-white"></i></a> ';
		                		echo '<input type="text" class="input-medium" name="apuesta" value="'.$rowAP["apuesta"].'"> ';
		                		echo '<input type="text" class="input-mini" name="cotizacion" value="'.$rowAP["cotizacion"].'"> ';
		                		echo '<input type="text" class="input-mini" name="apostado" value="'.$rowAP["apostado"].'"> ';		                		
		                		echo '<select class="input-small" name="acertada">
		                						<option value="0"'; 
		                						if($rowAP["acertada"]==0){echo 'selected="selected"';}
		                						echo'>Pendiente</option>';
		                						echo '<option value="1"'; 
		                						if($rowAP["acertada"]==1){echo 'selected="selected"';}
		                						echo'>Acertada</option>';
		                						echo '<option value="2"'; 
		                						if($rowAP["acertada"]==2){echo 'selected="selected"';}
		                						echo'>Fallada</option>		                						
		                					</select> ';
                				echo '<button type="submit" class="btn btn-mini btn-success" >Actualizar</button> ';
		                		echo '<input type="hidden" name="id" value="'.$rowAP["id"].'"/>';		
		                		echo '<input type="hidden" name="query" value="updateAP"/>';	
								echo '<input type="hidden" name="fecha" value="'.$_GET["fecha"].'"/> ';
								echo '<input type="hidden" name="fase" value="'.$_GET["fase"].'"/> ';
								echo '<input type="hidden" name="partido" value="'.$row["id"].'"/> ';	
		                		echo '</form>';
	                		echo '</td></tr>';
							
	                }mysql_free_result($resultAP);
					
                ?>
                <tr>
                	<td colspan="6" style="background-color:#CCC;text-align: center;vertical-align: middle;">
    					<a class="btn btn-mini btn-success" href="javascript:actualizarTodosApuestas();">
							<i class="icon-upload icon-white"></i> 
                			Actualizar Todos
            			</a>
					</td>
               </tr>
              </tbody>
        </table>
	
		<form class="form-inline" action="">
        	<input type="text" class="input" name="apuesta" placeholder="Apuesta"/>
        	<input type="text" class="input-small" name="cotizacion" placeholder="Cotización"/>
        	<input type="text" class="input-small" name="apostado" placeholder="Apostado"/>
        	<button type="submit" class="btn">Insertar Apuesta</button>
        	<input type="hidden" name="partido" value="<? echo $row["id"]; ?>"/>
        	<input type="hidden" name="query" value="insertAP"/>
            <input type="hidden" name="fecha" value="<? echo $_GET["fecha"]; ?>"/>
            <input type="hidden" name="fase" value="<? echo $_GET["fase"]; ?>"/>
        </form>

  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>    
  </div>
  
</div>
<?
}mysql_free_result($result);
}
?>
<!--FIN divs modales -->
  
   <!-- MODAL de carga ajax-->

    <div class="modal hiden fade" id="modal_ajax" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="javascript:window.location.reload();">×</button>
            <h3 id="myModalLabel">Actualizando partidos ...</h3>
          </div>
          <div class="modal-body">
            <p id="resultado"></p>
            <div id="prog_bar_padre" class="progress progress-striped active">
 				 <div id="prog_bar" class="bar" style="width: 0%;"></div>
			</div>
          </div>
          <div class="modal-footer">
          </div>
    </div>
<!--container -->

<?  if($_GET["partido"]!=""){ ?>
<script language="javascript">
 	$('#myModal<? echo $_GET["partido"]; ?>').modal('toggle')
</script>
<? } ?>
</body>
</html>



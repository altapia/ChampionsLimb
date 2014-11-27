<?php session_start(); 
	include_once("validate.php");	
	include "getProperty.php"; 
	include "../pages/conexion_bd.php";
	$user=$_SESSION['userid'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="ISO-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="icon" href="../favicon.png" type="image/png"/>
	<link rel="shortcut icon" href="../favicon.png" type="image/png"/>
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/limb.css" rel="stylesheet">

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
		$page="misapuestas"; 
		include "../pages/menu.php"; 
	?>

<div class="container">
	<h2>Mis Apuestas</h2>
	
	
	<?php
				
	$fecha_hoy = date("Y-m-d");
	$hora_hoy = date("H:i:s");
	//echo '---->>>'.$user.'<<<-----';
		
	$query='SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, e.escudo as escLocal, f.escudo as escVisit, p.fase, fas.max_apuesta 
			FROM partidos p, equipos e, equipos f , partido_apostante pa, fases fas
			WHERE p.local=e.id 
				and p.visitante=f.id
				and pa.idapostante='.$user.'
				and pa.idpartido = p.id
				and (fecha > \''.$fecha_hoy.'\' 
					or (fecha = \''.$fecha_hoy.'\' and hora >\''.$hora_hoy.'\')
					)
				and fas.clave=p.fase
			ORDER BY p.fecha, p.hora,p.id asc;';
	//echo $query;
		$result=mysql_query($query);
		echo mysql_error();
		$hayresultados=0;
		$aux=0;
		while ($row=mysql_fetch_array($result)) {
			$hayresultados=1;
			//FECHA
			$date = date_create($row["fecha"]);
			if($aux==0 OR $dateaux<>$date){
				$dateaux=$date;		
				if($aux!=0){
					echo '</div>';
				}
				$aux=1;
				echo '<div class="list-group">';
				echo '<a href="#" class="list-group-item active" style="padding-top: 0px;padding-bottom: 0px">';
				echo getFechaFormat($date);
				echo '</a>';
			}

?>
	<a data-toggle="collapse" data-target='<?php echo "#comment".$row["id"];?>'  
		href='<?php echo "#".$row["id"];?>' class="list-group-item text-center" style="min-height:65px">
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
			Importe disponible: <strong><span id="id_<?php echo $row['id']; ?>"><?php echo $row["max_apuesta"]; ?></span>&euro;</strong>
		</div>
		
	</a>
	<div id='comment<?php echo $row["id"]; ?>' idPartido='<?php echo $row["id"]; ?>' name="tabla_apuestas" class="collapse">

		<table class="table table-condensed">
			<thead>
				<tr>						
					<th colspan="2" style="text-align:center">Apuesta</th>
					<th style="text-align:center">Cotización</th>
					<th colspan="1" style="text-align:center">Apostado</th>									
					<th></th>
				</tr>
			</thead>
			<tbody>

		<?php
			$queryAp='SELECT 
						a.id,
						a.apuesta, 
						a.apostado,
						a.cotizacion 
					FROM apuestas a, partido_apostante pa, partido_apost_apuesta pap 
					WHERE 
						pa.idapostante='.$user.' and
						pa.idpartido='.$row["id"].' and
						pa.ID=pap.idpartidoapost and
						a.ID = pap.idapuesta
					ORDER BY a.ID';
			$resultAp=mysql_query($queryAp);			
			while ($rowAp=mysql_fetch_array($resultAp)) {
				$total_apostado+=$rowAp["apostado"];
				echo '<tr>
						<td colspan="2">'.$rowAp["apuesta"].'</td>';
				
				echo '<td class="text-center">';
				if($rowAp["cotizacion"]!=null && $rowAp["cotizacion"]!=''){
					echo '@'.$rowAp["cotizacion"];
				}
				echo '</td>';


				echo '<td class="text-center"><span name="culo_'.$row["id"].'">'.$rowAp["apostado"].'</span>&euro;</td>
						<td class="text-center"> ';
						if($rowAp["cotizacion"]==''){
							echo '<a class="btn btn-xs btn-danger" href="javascript:borrar('.$rowAp["id"].');"><span class="glyphicon glyphicon-remove"></span><span class="hidden-xs"> Borrar</span></a>';
						}else{
							echo '<a class="btn btn-xs btn-danger" disabled="disabled" href="#" title="La apuesta ya ha sido creada"><span class="glyphicon glyphicon-remove"></span><span class="hidden-xs"> Borrar</span></a>';
						}
						echo '</td>
					</tr>';
			}mysql_free_result($resultAp);
?>
			
			<tr>
				<td colspan="5" class="warn" id="td_insert_<?php echo $row['id'];?>">
					<form class="form-inline" id="form_insert_<?php echo $row['id'];?>" method="GET" action="./dao/insertApuesta.php">
						<div class="col-sm-7 h4">
							<input type="text" class="form-control" style="width:100%" name="apuesta" id="apuesta" placeholder="Descripción apuesta" required>
						</div>
						<div class="col-sm-3 col-xs-6 h4 ">
							<input type="number" class="form-control" name="apostado" id="apostado" required step="0.01" min="0">
						</div>
						<div class="col-sm-2 text-center col-xs-6 h4 ">
							<button type="submit" class="btn btn-success">Insertar</button>	
						</div>
						<input type="hidden" name="partido" value="<?php echo $row['id'];?>"/>
						<input type="hidden" name="apostante" value="<?php echo $user;?>"/>						
						<input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'];?>"/>			

					</form>
				</td>
			</tr>
			
			</tbody>
		</table>
	</div>
  <? 		
	}mysql_free_result($result);
	
	if($hayresultados==0){
?>
	<div class="info">
		<h4>Atención:</h4>
		<p style="color: #000;">
			No tienes partidos asociados pendientes
		</p>
	</div>
<?php
	}
?>
						

</div>

<script src="../bootstrap/js/jquery.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script language="javascript">
<?php
	//session_start();
	if($_SESSION['flash_error']!=''){
		echo 'alert("'.$_SESSION['flash_error'].'");';
	};
	$_SESSION['flash_error']='';
?>

	function borrar(i){
		window.location="./dao/deleteApuesta.php?id="+i+"&url=<?php echo $_SERVER['REQUEST_URI'];?>";	
	}

	//Preapra la cantidad a apostar
	$(document).ready(function() {
		$( "div[name='tabla_apuestas']" ).each(function(){
			var partido = $(this).attr("idpartido");
			var totalPartido = 0;
			var nameSpan = 'culo_'+partido;
			$("span[name="+nameSpan+"]").each(function(){
				totalPartido=totalPartido + parseFloat($(this).html());
			});
			var maxapost =$('#id_'+partido).html();
			//alert(maxapost+'-'+totalPartido);
			var apostable=(parseFloat(maxapost)-parseFloat(totalPartido)).toFixed(2);
			$('#id_'+partido).html(apostable);

			if(apostable<=0){
				$('#td_insert_'+partido).hide();
			}else{				
				$('form#form_insert_'+partido +' input[name="apostado"]').attr('max',apostable);
			}
		});
	});
	
	$('#form_cambiopass').submit(function() {
		if($('#inputPassword').val()!=$('#inputPasswordRe').val()){
			$("#msg_error").html("<strong>Error!</strong> Las contrase&ntilde;as deben coincidir").show();
			return false;
		}
		return true;
	});
	
	$( "form[name^='form_']" ).submit(function() {
		var idForm= $(this).attr("idform");
		$("div[name^='span_"+idForm+"']").html("").hide();
		var hayerr=false;
		$( "form[name^='form_"+idForm+"'] :input" ).each(function(){				
			var name= $(this).attr("name");
			if(name=='apuesta'){
				if($(this).val()==''){
					$("div[name^='span_"+idForm+"']").html("<strong>Error!</strong> Debe introducir una descripci&oacute;n de la apuesta").show();	
					hayerr=true;					
					return false;
				}
			}else if(name=='apostado'){
				if($(this).val()==''){
					$("div[name^='span_"+idForm+"']").html("<strong>Error!</strong> Debe introducir la cantidad de la apuesta").show();
					hayerr=true;
					return false;
				}else if(!$.isNumeric($(this).val())){						
					$("div[name^='span_"+idForm+"']").html("<strong>Error!</strong> La cantidad apostada debe ser un valor num&eacute;rico.").show();
					hayerr=true;
					return false;
				}else{
					var apostado=$(this).val();
					//alert($(this).val());
					$("form[name^='form_"+idForm+"'] :input" ).each(function(){
						if($(this).attr("name")=='max_apuesta'){
							/*************/
							var total_apostado=0;
							$("form[name^='form_"+idForm+"'] :input" ).each(function(){
								if($(this).attr("name")=='total_apostado'){
									total_apostado=parseFloat($(this).val());
								}
							});
							
							/***********/
							
							//alert('apos: '+apostado+'max:'+$(this).val()+'sum:'+total_apostado);
							if((parseFloat(apostado) + total_apostado) > parseFloat($(this).val())){
								$("div[name^='span_"+idForm+"']").html("<strong>Error!</strong> La cantidad apostada supera el m&aacute;ximo permitido de "+$(this).val()+"&euro;").show();
								hayerr=true;
								return false;
							}
						}
					});
				}
			}
		});
			if(hayerr){
				return false;
			}
	});

</script>
</body>
</html>



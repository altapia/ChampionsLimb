<?php
include "../pages/conexion_bd.php";



switch ($_GET["q"]) {
    case "apostantes":
        getApostantes();
        break;
	case "prox_jornada":
        getProximaJornada();
        break;
	case "prox_partido_sorteo":
        getProximoPartidoSorteo();
        break;
	case "set_apostante_partido":		
        setApostantePartido();
        break;
	default:
	?>
      
       		<html><head><link href='../bootstrap/css/bootstrap.min.css' rel='stylesheet'>
       		<style type='text/css'>
				html,body {height: 100%;}
				#wrap {min-height: 100%;height: auto !important;height: 100%;margin: 0 auto -60px;}
				#push,
				#footer {height: 60px;}
				#footer {background-color: #f5f5f5;}
				@media (max-width: 767px) {#footer {margin-left: -20px; margin-right: -20px; padding-left: 20px;padding-right: 20px;}}
				.container {width: auto; max-width: 680px;}
				.container .credit {margin: 20px 0;text-align:center;}
			</style>
 			</head><body>
 			<div id='wrap'>
      			<div class='container'>
		       		<h3>Los servicios disponibles son:</h3>
		       		<ul>
			       		<li>
			       			<a href='./api.php?q=apostantes'>apostantes</a>: Listado de todos los apostantes disponibles
			       		</li>
			       		<li>
			       			<a href='./api.php?q=prox_jornada'>prox_jornada</a>: Listado de todos los partidos de la pr&oacute;xima jornada
			       		</li>
			       		<li>
			       			<a href='./api.php?q=prox_partido_sorteo'>prox_partido_sorteo</a>: Datos del pr&oacute;ximo partido sin apostante asignado
			       		</li>
			       		<li>
			       			<a href='./api.php?q=set_apostante_partido'>set_apostante_partido</a>: Asigna el partido indicado al apostante indicado
			       		</li>
		       		</ul>
		       		<p>
		       			Ejemplo: <code>http://hotelpene.com/mundialLimb/pages/api.php?q=prox_partido_sorteo</code>
		       		</p>
       			</div>
				<div id='push'></div>
    		</div>
       		<div id='footer'>
      			<div class='container'>
       				 <p class='muted credit'>&copy; <a href='http://hotelpene.com/mundialLimb'>MundialLimb 2014</a></p>
      			</div>
   			</div>
       		</body></html>
	<?php
}

function getApostantes(){
//Número de apostantes en la fase
		$resultApostEnFase=mysql_query("SELECT num_apostantes, id FROM fases WHERE activa=1 ORDER BY id DESC");
		$rowApostEnfase=mysql_fetch_array($resultApostEnFase);		
 	 	$numApostEnFase = $rowApostEnfase["num_apostantes"];
 	 	$idFase = $rowApostEnfase["id"];
		

		//Número de apostantes en total
 	 	$resultApostTotal=mysql_query('SELECT count(*) as num FROM apostantes');
		$rowApostTotal=mysql_fetch_array($resultApostTotal);
		mysql_free_result($resultApostTotal);
 	 	$numApostTotal = $rowApostTotal["num"];	


		$query="";
		//echo $numApostEnFase.'<-->'.$numApostTotal;
		if($numApostEnFase>=$numApostTotal){
			$query="SELECT nombre, id FROM apostantes ORDER BY nombre ASC";
		}else{

			$query="SELECT nombre, id 
					FROM (
						SELECT 
							ap.id, 
							ap.nombre, 
							SUM(
								CASE a.acertada 
						    		WHEN 1 THEN ((a.apostado*a.cotizacion)-a.apostado)
						    		WHEN 2 THEN (-1*a.apostado)
						    		ELSE 0 END
							)as ganancia 
						FROM apuestas a
							INNER JOIN partidos p ON p.id= a.partido
							INNER JOIN fases f ON f.clave=p.fase
							INNER JOIN partido_apost_apuesta paa ON paa.idapuesta=a.id
							INNER JOIN partido_apostante pa ON pa.id=paa.idpartidoapost
							INNER JOIN apostantes ap ON ap.id = pa.idapostante
						WHERE ";

			//Si estamos en Fase 3 (CUARTOS DE FINAL) se tienen en cuenta todos los partidos anteriores
			if($idFase==3){
				$query.=" f.id<3 ";
			}else{
				$query.=" f.id=(".$idFase."-1) ";
			}
							
			$query.=" GROUP BY ap.nombre
					ORDER BY ganancia DESC 
					LIMIT ".$numApostEnFase."
				) cosas 
				ORDER BY nombre asc";
					
		}		
		  
		$result=mysql_query($query);
		$total=array();
		while($row=mysql_fetch_object($result)){
			array_push($total,$row);	
		}
		mysql_free_result($result);
	
		echo json_encode($total);

}

function getProximaJornada(){
	/*
	 * Se obtiene la ronda actual
	 * */
	$queryJornadas='SELECT DISTINCT p.fecha FROM partidos p ORDER BY p.fecha;';
	$resultJornadas=mysql_query($queryJornadas);	
	$fecha_actualYMD = strtotime(date("Y-m-d",time()));

	while ($rowJornada=mysql_fetch_array($resultJornadas)) {
			$fecha_jornada = strtotime($rowJornada["fecha"]);
			if($fecha_actualYMD<=$fecha_jornada){							
				break;
			}
	}mysql_free_result($resultJornadas);

	
	$fecha_jornada=date('Y-m-d',$fecha_jornada);
	//echo 'fecha jornada: '.$fecha_jornada;



	/*
	 * Se obtienen los partidos de la ronda establecida
	*/
	
	$queryPartidos="SELECT p.id, e.nombre as local, f.nombre as visitante, p.fecha, p.hora, e.escudo as escLocal, f.escudo as escVisit 
							FROM partidos p, equipos e, equipos f 
							WHERE p.local=e.id and p.visitante=f.id and p.fecha='".$fecha_jornada."'
							ORDER BY p.fecha , p.hora ,p.id asc;";
	//echo $queryPartidos;
	$result = mysql_query($queryPartidos);
							
	if(!$result){
		die('Error: ' . mysql_error());
	}else{
		$total=array();
		while ($row = mysql_fetch_object($result)) {
			array_push($total,$row);
		}
		mysql_free_result($result);
		
		echo json_encode($total);
		}
}

function getProximoPartidoSorteo(){
	/*
	 * Se obtienen el próximo partido sin apostante
	*/
	
	$queryPartidoSorteo="SELECT p.id,p.fecha, p.hora, e.nombre as local, f.nombre as visitante,  e.escudo as escLocal, f.escudo as escVisit 
					FROM partidos p
					LEFT JOIN partido_apostante pa ON p.id =pa.idpartido
					LEFT JOIN equipos e ON p.local=e.id
					LEFT JOIN equipos f ON p.visitante=f.id
					WHERE pa.idpartido is null
					ORDER BY p.fecha , p.hora ,p.id asc
					LIMIT 1;";
	//echo $queryPartidoSorteo;
	$result = mysql_query($queryPartidoSorteo);
							
	if(!$result){
		die('Error: ' . mysql_error());
	}else{
		$total=array();
		while ($row = mysql_fetch_object($result)) {
			$row->local=utf8_encode($row->local);
			$row->visitante=utf8_encode($row->visitante);
			array_push($total,$row);
		}
		mysql_free_result($result);
		
		echo json_encode($total);
		}
}

/*
 * Devuelve estado=1 si ha habido error. estado=0 sino
 */
function setApostantePartido(){
	
	$idApostante=$_GET["id_apostante"];
	$idPartido=$_GET["id_partido"];
	
	if($idApostante==null || $idApostante==''){
		$respuesta=array("estado"=>1,"error"=>"No se ha indicado el id del apostante");
		echo json_encode($respuesta);
		return;
	}
	if($idPartido==null || $idPartido==''){
		$respuesta=array("estado"=>1,"error"=>"No se ha indicado el id del partido");
		echo json_encode($respuesta);
		return;
	}
	
	$respuesta=array();
	if(!mysql_query("INSERT INTO partido_apostante (idpartido, idapostante)  VALUES ($idPartido,$idApostante)")){
		//die('Error: ' . mysql_error());
		$respuesta=array("estado"=>1,"error"=>mysql_error());
	}else{
		$respuesta=array("estado"=>0,"error"=>"");
	}
	echo json_encode($respuesta);
}

?>

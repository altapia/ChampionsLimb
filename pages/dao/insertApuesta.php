<?php

	include "../conexion_bd.php";

	$apuesta= "'".$_GET["apuesta"]."'";
	$apostado= $_GET["apostado"];
	$cotizacion= $_GET["cotizacion"];
	if($cotizacion==null){
		$cotizacion='null';
	}
	
	$partido= $_GET["partido"];
	$apostante= $_GET["apostante"];
	
	
	//Se inserta la apuesta
	if(!mysql_query("INSERT INTO apuestas (apuesta, apostado, cotizacion, partido) VALUES (".$apuesta.",". $apostado."
					, ".$cotizacion.", ".$partido.");")){
		header("Location: ../admin/editPartido.php?id=".$partido."&error=InsertPartido-". mysql_error());
 	}
	$idApuesta = mysql_insert_id();
 	
 	//Obtenemos el ID de la relación partido-apostante
 	$resultApuest=mysql_query("select id from partido_apostante where idpartido=".$partido." and idapostante=".$apostante.";");
	if(!$resultApuest){
		header("Location: ../admin/editPartido.php?id=".$partido."&error=SelectRelacion-". mysql_error());
 	}
 	$row1=mysql_fetch_row($resultApuest);

	//Insertamos la relacion partido-apostante-partido
 	if(!mysql_query("INSERT INTO partido_apost_apuesta (idapuesta, idpartidoapost) VALUES (".$idApuesta.",".$row1[0]."); ")){
		header("Location: ../admin/editPartido.php?id=".$partido."&error=Insert-partido_apost_apuesta-". mysql_error());
 	}
	
	
 	if(isset($_GET["url"])){
 		header("Location: ".$_GET["url"]);	
 	}else{		
		header("Location: ../admin/editPartido.php?id=".$partido);
	}
	
?>;
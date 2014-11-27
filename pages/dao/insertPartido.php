<?php 

	include "../conexion_bd.php";
	
	$local= $_GET["local"];
	$visitante= $_GET["visitante"];
	$fecha= $_GET["fecha"];
	$hora= $_GET["hora"];
	$fase= $_GET["fase"];
	
	if(!mysql_query("INSERT INTO partidos (LOCAL, VISITANTE, FECHA, HORA, FASE) VALUES ('$local', '$visitante', '$fecha', '$hora', '$fase') ")){
		//die('Error: ' . mysql_error());
		header("Location: ../admin/mantenimientoPartidos.php?error=". mysql_error());
 	}

	header("Location: ../admin/mantenimientoPartidos.php");
	
?>

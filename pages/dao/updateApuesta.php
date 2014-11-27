<?php

	include "../conexion_bd.php";

	$apuesta= "'".$_GET["apuesta"]."'";
	$apostado= $_GET["apostado"];
	$cotizacion= $_GET["cotizacion"];
	if($cotizacion==null){
		$cotizacion='null';
	}
	$acertada= $_GET["acertada"];
	$partido= $_GET["partido"];
	$id= $_GET["id"];
	
	if(!mysql_query("UPDATE apuestas SET apostado=".$apostado.", cotizacion=".$cotizacion.", acertada=".$acertada.", apuesta=".$apuesta." WHERE id=".$id)){
		header("Location: ../admin/editApuesta.php?id=".$id."&error=". mysql_error());
	}
	header("Location: ../admin/editPartido.php?id=".$partido);
?>
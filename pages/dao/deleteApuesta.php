<?php

	include "../conexion_bd.php";

	$partido= $_GET["partido"];
	$id= $_GET["id"];
	
	if(!mysql_query("DELETE FROM apuestas WHERE id=".$id)){
		header("Location: ../admin/editApuesta.php?id=".$id."&error=". mysql_error());
	}

	if(isset($_GET["url"])){
 		header("Location: ".$_GET["url"]);	
 	}else{		
		header("Location: ../admin/editPartido.php?id=".$partido);
	}
	
?>
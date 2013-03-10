<?

include "../pages/conexion_bd.php";

$fecha= $_POST["fecha"];
		$equipoLocal= $_POST["equipoLocal"];
		$equipoVisit= $_POST["equipoVisit"];
		$apostador= $_POST["apostador"];
		if($apostador=='0'){ $apostador='NULL';}
		$hora=$_POST["hora"];
		$id=$_POST["id"];
		
		//echo '"UPDATE partidos SET fecha='.$fecha.', local='.$equipoLocal.', visitante='.$equipoVisit.', apostante='.$apostador.', hora='.$hora.' WHERE id='.$id.'"';
		
		if(!mysql_query("UPDATE partidos SET fecha='$fecha', local=$equipoLocal, visitante=$equipoVisit, apostante=$apostador, hora='$hora' WHERE id=$id")){
  			die('Error: ' . mysql_error());
 	 	}else{
			echo 'Actualización correcta: '+$id;
		}

			
?>
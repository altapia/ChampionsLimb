<?

include "../pages/conexion_bd.php";

		$apuesta= $_POST["apuesta"];
		$apostado= $_POST["apostado"];
		$cotizacion= $_POST["cotizacion"];
		$acertada= $_POST["acertada"];
		$id= $_POST["id"];
		if(!mysql_query("UPDATE apuestas SET apuesta='$apuesta', apostado=$apostado, cotizacion=$cotizacion, acertada=$acertada WHERE id=$id")){
  			die('Error: ' . mysql_error());
 	 	}else{
			echo 'Actualización correcta: '+$id;
		}
?>
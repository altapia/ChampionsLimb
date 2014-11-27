<?php
	include "../conexion_bd.php";
	
	if($_POST["operacion"]=="borrarPartido"){
		borrarPartido($_POST["id"], $_POST["redirect"]);
	}else if($_POST["operacion"]=="updatePartido"){
		 updatePartido();
	}else if($_POST["operacion"]=="getApuestasParaUpdate"){
		 getApuestasParaUpdate($_POST["id"]);
	}else if($_POST["operacion"]=="actualizarApuesta"){
		 actualizarApuesta($_POST["id"],$_POST["estado"]);
	}else if($_POST["operacion"]=="actualizarPropiedades"){
		 actualizarPropiedades($_POST["id"],$_POST["valor"]);
	}else if($_POST["operacion"]=="actualizarFases"){
		 actualizarFases($_POST["id"],$_POST["titulo"],$_POST["activa"],$_POST["clave"],$_POST["max_apuesta"],$_POST["num_apostantes"]);
	}else if($_POST["operacion"]=="actualizarEquipos"){
		 actualizarEquipos($_POST["id"],$_POST["nombre"],$_POST["grupo"],$_POST["escudo"]);
	}else if($_POST["operacion"]=="actualizarUser"){		
		 actualizarUser($_POST["username"],$_POST["password_sha1"],$_POST["userid"],$_POST["admin"],$_POST["email"]);
	}else{
		echo 'Operación no reconocida';
	}
	
	function borrarPartido($id, $redirect){
		//echo $redirect.'<--';
		if(!mysql_query("DELETE FROM partidos where id=$id ")){
			header("Location: ".$redirect."?error=". mysql_error());
			exit;
			//die('Error: ' . mysql_error());
		}
		header("Location: ".$redirect);
		
	}
	
	function updatePartido(){
		$equipoLocal= $_POST["local"];
		$equipoVisit= $_POST["visitante"];
		$fecha= $_POST["fecha"];
		$hora=$_POST["hora"];
		$fase=$_POST["fase"];
		$id=$_POST["id"];
		$redirect=$_POST["redirect"];
		
		if(!mysql_query("UPDATE partidos SET fecha='$fecha', local=$equipoLocal, visitante=$equipoVisit, hora='$hora', fase='$fase' WHERE id=$id")){
  			header("Location: ".$redirect."?error=". mysql_error());
  			//die('Error: ' . mysql_error());
  			exit;
 	 	}
		if(!mysql_query("DELETE FROM partido_apostante WHERE idpartido=$id")){
  			header("Location: ".$redirect."?error=". mysql_error());	
  			//die('Error: ' . mysql_error());
  			exit;
 	 	}
		for($i=0;8>=$i;$i++){
			if($_POST["apos_".$i]!=null && $_POST["apos_".$i]!=''){
				if(!mysql_query("INSERT INTO partido_apostante (idpartido, idapostante)  VALUES (".$id.",".$_POST["apos_".$i].");")){
	  				header("Location: ".$redirect."?error=". mysql_error());	
	  				//die('Error: ' . mysql_error());
	  				exit;
	 	 		}
			}
		}
		header("Location: ".$redirect);
	}
	
	function getApuestasParaUpdate($idPartido){
		$queryApuestas='select a.apuesta, a.apostado, a.cotizacion, a.acertada, apost.nombre 
						from apuestas a, partido_apostante pa, partido_apost_apuesta pap, apostantes apost
						where pa.idpartido='.$idPartido.' 
							and pa.id = pap.idpartidoapost
							and pap.idapuesta = a.id
							and pa.idapostante=apost.id
						order by apost.nombre asc';
		$resultApuestas=mysql_query($queryApuestas);
		$totalGan=0;
		if(!$resultApuestas){
			die('Error: ' . mysql_error());
		}
		while ($rowApu=mysql_fetch_array($resultApuestas)) {
			
			$resultado.='<tr>';
				$resultado.='<td style="text-align:center">';
					if($rowApu["acertada"]==1){
						$resultado.='<span class="glyphicon glyphicon-ok"></span>';
					}else if($rowApu["acertada"]==2){
						$resultado.='<span class="glyphicon glyphicon-remove"></span>';
					}else{
						$resultado.='<span class="glyphicon glyphicon-time"></span>';
					}
				$resultado.='</td>';
				
				//Arreglos ENCODING
				$apuestaAux=str_replace('ñ', '&ntilde', $rowApu["apuesta"]);
				$apuestaAux=str_replace('á', '&aacute', $apuestaAux);
				$apuestaAux=str_replace('é', '&aacute', $apuestaAux);
				$apuestaAux=str_replace('í', '&aacute', $apuestaAux);
				$apuestaAux=str_replace('ó', '&aacute', $apuestaAux);
				$apuestaAux=str_replace('ú', '&aacute', $apuestaAux);
				
				$resultado.='<td>'.$apuestaAux.'</td>';
				$resultado.='<td style="text-align:center">'.$rowApu["cotizacion"].'</td>';
				$resultado.='<td style="text-align:center">'.$rowApu["apostado"].'&euro;</td>';
				
				if($rowApu["acertada"]==0){
					$resultado.='<td class="hidden-xs" style="text-align:center;color:grey">';
				}else{
					$resultado.='<td class="hidden-xs" style="text-align:center;">';
				}
					$ganancia=0;
					if($rowApu["acertada"]<2){
						if( $rowApu["cotizacion"]==null) {
							$ganancia=0;
						}else{
							$ganancia= ($rowApu["apostado"] * $rowApu["cotizacion"])-$rowApu["apostado"];
						}
					}else if($rowApu["acertada"]==2){
						$ganancia=$rowApu["apostado"] * -1;
					}
					$resultado.='<span>'.$ganancia.'&euro;</span>';
					$totalGan=$totalGan+ $ganancia;
				$resultado.='</td>';
				$resultado.='<td class="hidden-xs" style="text-align:center"><span>'.$rowApu["nombre"].'</span></td>';
           	$resultado.='</tr>';
		}mysql_free_result($resultApuestas);
		$resultado.=mysql_error();
		$resultado.='<tr class="hidden-xs"><td style="text-align:right" colspan="5"><strong>Ganancia Total</strong></td><td>'.$totalGan.'&euro;</td></tr>';
		$resultado.='<tr class="visible-xs"><td style="text-align:right" colspan="3"><strong>Ganancia Total</strong></td><td>'.$totalGan.'&euro;</td></tr>';
		
		echo $resultado;
	}

	function actualizarApuesta($id,$acertada){
		if(!mysql_query("UPDATE apuestas SET acertada=".$acertada." WHERE id=".$id)){
			die('Error: ' . mysql_error());
		}	
		return 'ok';
	}

	function actualizarPropiedades($id, $valor){
		if(!mysql_query("UPDATE propiedades SET valor='".$valor."' WHERE id=".$id)){
			header("Location: ../admin/config/propiedades.php?error=". mysql_error());
			exit;
		}	
		header("Location: ../admin/config/propiedades.php");
	}

	function actualizarFases($id, $titulo, $activa, $clave, $max_apuesta, $num_apostantes){
		if(!mysql_query("UPDATE fases SET  titulo='$titulo', activa=$activa, clave='$clave', max_apuesta=$max_apuesta, num_apostantes=$num_apostantes WHERE id=".$id)){
			header("Location: ../admin/config/fases.php?error=". mysql_error());
			exit;
		}	
		header("Location: ../admin/config/fases.php");
	}

	function actualizarEquipos($id, $nombre, $grupo, $escudo){
		if(!mysql_query("UPDATE equipos SET  nombre='$nombre', grupo='$grupo', escudo='$escudo' WHERE id=".$id)){
			header("Location: ../admin/config/equipos.php?error=". mysql_error());
			exit;
		}	
		header("Location: ../admin/config/equipos.php");
	}

	function actualizarUser($username, $password_sha1, $userid, $admin, $email){
		if(!mysql_query("UPDATE user SET username='$username', password_sha1='$password_sha1', admin='$admin', email='$email' WHERE userid=".$userid)){
			header("Location: ../admin/config/usuarios.php?error=". mysql_error());
			exit;
		}	
		header("Location: ../admin/config/usuarios.php");
	}
?>
<?
include "conexion_bd.php";
		
	function getPropiedad($nom){
		
		$result=mysql_query("SELECT valor FROM propiedades where nombre='".$nom."';");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$row=mysql_fetch_array($result);
 	 		return $row["valor"];
		}
		
	}
	
	function getFasesMenuIndex($id, $enlace){
		
		$result=mysql_query("SELECT id, titulo FROM fases where activa>0;");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$resultado="";
 	 		while ($row=mysql_fetch_array($result)) {
 	 			if($row["id"]!=null && $id==$row["id"]){
 	 				$resultado=$resultado.'<li class="active">';
 	 			}else{
 	 				$resultado=$resultado.'<li>';
 	 			}
 	 			$resultado=$resultado.'<a href="'.$enlace.'/pages/fase.php?id='.$row["id"].'">'.$row["titulo"].'</a></li>'; 	 			
			}
 	 		return $resultado;
		}
		
	}
	
	function getFasesMenu($id, $enlace){
		
		$result=mysql_query("SELECT id, titulo FROM fases where activa>0;");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$resultado="";
 	 		while ($row=mysql_fetch_array($result)) {
 	 			if($row["id"]!=null && $id==$row["id"]){
 	 				$resultado=$resultado.'<li class="active">';
 	 			}else{
 	 				$resultado=$resultado.'<li>';
 	 			}
 	 			$resultado=$resultado.'<a href="'.$enlace.'/pages/fase.php?id='.$row["id"].'">'.$row["titulo"].'</a></li>'; 	 			
			}
 	 		return $resultado;
		}
		
	}
	
	function getTituloFase($id){
		
		$result=mysql_query("SELECT titulo FROM fases where id=".$id.";");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$row=mysql_fetch_array($result);
 	 		return $row["titulo"];
		}
		
	}
	
	function getClaveFase($id){
		
		$result=mysql_query("SELECT clave FROM fases where id=".$id.";");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$row=mysql_fetch_array($result);
 	 		return $row["clave"];
		}
		
	}
	
	function getFasesApuestas($id){
		$result=mysql_query("SELECT id, titulo, clave FROM fases where activa>0;");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$resultado="";
 	 		while ($row=mysql_fetch_array($result)) {
 	 			if($row["id"]!=null && $id==$row["id"]){
 	 				$resultado=$resultado.'<li class="active">';
 	 			}else{
 	 				$resultado=$resultado.'<li>';
 	 			}
 	 			$resultado=$resultado.'<a href="./apuestas.php?fase='.$row["clave"].'&id='.$row["id"].'">'.$row["titulo"].'</a></li>';
			}
 	 		return $resultado;
		}
	}
	
		function getFasesMantApuestas($id){
		$result=mysql_query("SELECT id, titulo, clave FROM fases where activa>0;");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$resultado="";
 	 		while ($row=mysql_fetch_array($result)) {
 	 			if($row["id"]!=null && $id==$row["id"]){
 	 				$resultado=$resultado.'<li class="active">';
 	 			}else{
 	 				$resultado=$resultado.'<li>';
 	 			}
 	 			$resultado=$resultado.'<a href="./mantenimientoApuestas.php?fase='.$row["clave"].'&id='.$row["id"].'">'.$row["titulo"].'</a></li>';
			}
 	 		return $resultado;
		}
	}
	
	function getApostadores($id){
		$result=mysql_query("select a.nombre from partido_apostante pa, apostantes a where pa.idpartido=".$id." and pa.idapostante=a.id;");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$resultado='<table class="table-condensed">';
 	 		while ($row=mysql_fetch_array($result)) {
 	 			$resultado=$resultado."<tr><td>".$row["nombre"]."</td></td>";
			}
 	 		return $resultado."</table>";
		}
	}

	function getApostadoresSimple($id){
		$result=mysql_query("select a.nombre from partido_apostante pa, apostantes a where pa.idpartido=".$id." and pa.idapostante=a.id;");
		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$resultado='';
 	 		$aux = 0;
 	 		while ($row=mysql_fetch_array($result)) {
 	 			if($aux==0){
 	 				$aux=1;
 	 				$resultado=$row["nombre"];
 	 			}else{
 	 				$resultado=$resultado.", ".$row["nombre"];
 	 			}
			}
 	 		return $resultado;
		}
	}

	function getApostadoresMantenimiento($id){
		$result=mysql_query("select a.nombre, a.id from apostantes a ;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$result2=mysql_query("select a.id from partido_apostante pa, apostantes a where pa.idpartido=".$id." and pa.idapostante=a.id;");
			if(!$result2){
  				die('Error: ' . mysql_error());
 	 		}else{
 	 			$aux[mysql_num_rows($result)];
				$i=0;
				while ($row2=mysql_fetch_array($result2)) {
					$aux[$i]=$row2["id"];
					$i=$i+1;
				}
				if($i>0){
					while ($row=mysql_fetch_array($result)) {
						$resultado=$resultado.'<label class="checkbox inline"><input type="checkbox" id="inlineCheckbox1" ';
						for($j=0 ; $i>=$j; $j++){
							if($row["id"]==$aux[$j]){
								$resultado=$resultado.' checked="checked" ';
								break;
							}
						}
						$resultado=$resultado.' name="apos_'.$row["id"].'" value="'.$row["id"].'">'.$row["nombre"].'</label>';
					}
				}else{
		 	 		while ($row=mysql_fetch_array($result)) {
		 	 			$resultado=$resultado.'<label class="checkbox inline"><input type="checkbox" name="apos_'.$row["id"].'" id="inlineCheckbox1" value="'.$row["id"].'">'.$row["nombre"].'</label>';
					}
				}
	 	 		return $resultado ;
	 	 	}
		}
	}
	
	function getApostantesSelectMantenimiento($id){
		$result=mysql_query("select a.nombre, a.id from apostantes a ;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$result2=mysql_query("select a.id from partido_apostante pa, apostantes a where pa.idpartido=".$id." and pa.idapostante=a.id;");
			if(!$result2){
  				die('Error: ' . mysql_error());
 	 		}else{
 	 			$aux[mysql_num_rows($result)];
				$i=0;
				while ($row2=mysql_fetch_array($result2)) {
					$aux[$i]=$row2["id"];
					$i=$i+1;
				}
				if($i>0){
					$resultado="<select class='input-small' name='apostante' >";
					while ($row=mysql_fetch_array($result)) {
						for($j=0 ; $i>=$j; $j++){
							if($row["id"]==$aux[$j]){
								$resultado=$resultado.'<option value="'.$row["id"].'">'.$row["nombre"].'</option> ';
								break;
							}
						}
					}
				}
	 	 		return $resultado."</select>";
	 	 	}
		}
	}
	
	function getFasesSelectMantenimiento(){
		$result=mysql_query("select f.clave, f.titulo from fases f;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
			$resultado="<select name='grupo' >";
			while ($row=mysql_fetch_array($result)) {
				$resultado=$resultado.'<option value="'.$row["clave"].'">'.$row["titulo"].'</option> ';
			}
		}
		return $resultado."</select>";
	}
	
	function getFasesSelectMantenimientoNav($fase){
		$result=mysql_query("select f.clave, f.titulo from fases f;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
			$resultado='<select ONCHANGE="location = this.options[this.selectedIndex].value;" class="input-medium">';
			$resultado=$resultado."<option value='mantenimientoPartidos.php'>Fase...</option>";			
			while ($row=mysql_fetch_array($result)) {
				$resultado=$resultado.'<option ';
				if($fase==$row["clave"]){
					$resultado=$resultado.' selected="selected" ';
				}
				$resultado=$resultado.'value="mantenimientoPartidos.php?fase='.$row["clave"].'">'.$row["titulo"].'</option> ';
			}
		}
		return $resultado."</select>";
	}
	
	/***************************/
	/*AÑADIDO EN MUNDIAL LIMB */
	/**************************/
	
	function getFechaTexto($fecha){
		$date = date_create($fecha);
		$result=date_format($date, 'd').' de ';
		$mes=date_format($date, 'm');
		
		switch ($mes) {
			case 1:
				$result=$result."Enero";
				break;
			case 2:
				$result=$result."Febrero";
				break;
			case 3:
				$result=$result."Marzo";
				break;
			case 4:
				$result=$result."Abril";
				break;
			case 5:
				$result=$result."Mayo";
				break;
			case 6:
				$result=$result."Junio";
				break;
			case 7:
				$result=$result."Julio";
				break;
			case 8:
				$result=$result."Agosto";
				break;
			case 9:
				$result=$result."Septiembre";
				break;
			case 10:
				$result=$result."Octubre";
				break;							
			case 11:
				$result=$result."Noviembre";
				break;
			case 12:
				$result=$result."Diciembre";
				break;
		}
								
		$result=$result.' de '. date_format($date, 'Y');
		return $result;
	}
	
	function getFechaFormat($date){		
		$result=date_format($date, 'd').' de ';
		$mes=date_format($date, 'm');
		
		switch ($mes) {
			case 1:
				$result=$result."Enero";
				break;
			case 2:
				$result=$result."Febrero";
				break;
			case 3:
				$result=$result."Marzo";
				break;
			case 4:
				$result=$result."Abril";
				break;
			case 5:
				$result=$result."Mayo";
				break;
			case 6:
				$result=$result."Junio";
				break;
			case 7:
				$result=$result."Julio";
				break;
			case 8:
				$result=$result."Agosto";
				break;
			case 9:
				$result=$result."Septiembre";
				break;
			case 10:
				$result=$result."Octubre";
				break;							
			case 11:
				$result=$result."Noviembre";
				break;
			case 12:
				$result=$result."Diciembre";
				break;
		}
								
		$result=$result.' de '. date_format($date, 'Y');
		return $result;
	}

	function getComboEquipos($nombreSelect, $selected){
		$result=mysql_query("SELECT id, nombre FROM equipos;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
			$resultado='<select name="'.$nombreSelect.'" class="form-control" required>';
			$resultado=$resultado."<option value=''>Selecciona...</option>";			
			while ($row=mysql_fetch_array($result)) {
				$resultado=$resultado.'<option ';
				if($selected==$row["id"]){
					$resultado=$resultado.' selected ';
				}
				$resultado=$resultado.'value="'.$row["id"].'">'.$row["nombre"].'</option> ';
			}
		}
		return $resultado."</select>";
	}

	function getComboFases($nombreSelect, $selected){
		$result=mysql_query("select f.clave, f.titulo from fases f;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
			$resultado="<select name='".$nombreSelect."' class='form-control' required >";
			while ($row=mysql_fetch_array($result)) {
				if($selected==$row["clave"]){
					$resultado=$resultado.'<option value="'.$row["clave"].'" selected>'.$row["titulo"].'</option> ';					
				}else{
					$resultado=$resultado.'<option value="'.$row["clave"].'">'.$row["titulo"].'</option> ';
				}
			}
		}
		return $resultado."</select>";
	}
	
	function getListaPartidosEditar($pFech){
		$result=mysql_query("SELECT p.ID as id, p.FECHA as fecha, p.HORA as hora ,e.NOMBRE as local, e.ESCUDO as escLocal, f.NOMBRE as visit, f.ESCUDO as escVisit, count(a.ID) as numApuestas
							FROM partidos p 
								LEFT JOIN apuestas a ON p.ID=a.PARTIDO
								LEFT JOIN equipos e ON e.ID=p.LOCAL
								LEFT JOIN equipos f ON f.ID=p.VISITANTE
							WHERE p.FECHA='".$pFech."'
							GROUP BY e.NOMBRE, e.ESCUDO, f.NOMBRE, f.ESCUDO
							ORDER BY p.FECHA, p.HORA;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
			
			$fecha=null;
			while ($row=mysql_fetch_array($result)) {
				$resultado=$resultado.'<option value="'.$row["clave"].'">'.$row["titulo"].'</option> ';
				
				if($fecha!=$row["fecha"]){
					$fechaTexto=getFechaTexto($row["fecha"]);
					$resultado=$resultado.'<li class="list-group-item" style="background-color: #2a6496;color: white;height: 21px;padding: 0px;">'.$fechaTexto.'</li>';
					$fecha=$row["fecha"];
				}
				
				$resultado=$resultado.
					'<a href="./editPartido.php?id='.$row["id"].'" class="list-group-item">
						<h5 class="list-group-item-heading">'.
							' <img src="../../images/escudos/'.$row["escLocal"].'"> vs <img src="../../images/escudos/'.$row["escVisit"].'"> '.' 
							<span class="badge">'.$row["numApuestas"].'</span>
						</h5>
						<p class="list-group-item-text">'.substr($row["hora"],0,5).'</p>
					</a>';
					
				/*CON NOMBRE DE LOS EQUIPOS
				 * 	$resultado=$resultado.
					'<a href="./editPartido.php?id='.$row["id"].'" class="list-group-item">
						<h5 class="list-group-item-heading">'.
							$row["local"].' <img src="../../images/escudos/'.$row["escLocal"].'"> vs <img src="../../images/escudos/'.$row["escVisit"].'"> '.$row["visit"].' 
							<span class="badge">'.$row["numApuestas"].'</span>
						</h5>
						<p class="list-group-item-text">'.substr($row["hora"],0,5).'</p>
					</a>';*/
			
			}
		}
		return $resultado;
		
	}
	
	function getComboApostantes($id, $nombreSelect, $apostante){
		
		$result=mysql_query("select a.nombre, a.id from apostantes a ;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$result2=mysql_query("select a.id from partido_apostante pa, apostantes a where pa.idpartido=".$id." and pa.idapostante=a.id;");
			if(!$result2){
  				die('Error: ' . mysql_error());
 	 		}else{
 	 			$aux[mysql_num_rows($result)];
				$i=0;
				while ($row2=mysql_fetch_array($result2)) {
					$aux[$i]=$row2["id"];
					$i=$i+1;
				}
				if($i>0){
					$resultado='<select name="'.$nombreSelect.'" class="form-control" required>';
					while ($row=mysql_fetch_array($result)) {
						for($j=0 ; $i>=$j; $j++){
							if($row["id"]==$aux[$j]){
								if($apostante!=null && $apostante==$row["id"]){
									$resultado=$resultado.'<option value="'.$row["id"].'" selected>'.$row["nombre"].'</option> ';
								}else{
									$resultado=$resultado.'<option value="'.$row["id"].'">'.$row["nombre"].'</option> ';
								}
								break;
							}
						}
					}
				}
	 	 		return $resultado."</select>";
	 	 	}
		}
		
	}
	
	function getCheckboxApostantes($id){
		$result=mysql_query("select a.nombre, a.id from apostantes a ;");

		if(!$result){
  			die('Error: ' . mysql_error());
 	 	}else{
 	 		$result2=mysql_query("select a.id from partido_apostante pa, apostantes a where pa.idpartido=".$id." and pa.idapostante=a.id;");
			if(!$result2){
  				die('Error: ' . mysql_error());
 	 		}else{
 	 			$aux[mysql_num_rows($result)];
				$i=0;
				while ($row2=mysql_fetch_array($result2)) {
					$aux[$i]=$row2["id"];
					$i=$i+1;
				}
				if($i>0){
					while ($row=mysql_fetch_array($result)) {
						$resultado=$resultado.'<label class="checkbox-inline"><input type="checkbox" id="inlineCheckbox1" ';
						for($j=0 ; $i>=$j; $j++){
							if($row["id"]==$aux[$j]){
								$resultado=$resultado.' checked="checked" ';
								break;
							}
						}
						$resultado=$resultado.' name="apos_'.$row["id"].'" value="'.$row["id"].'">'.$row["nombre"].'</label>';
					}
				}else{
		 	 		while ($row=mysql_fetch_array($result)) {
		 	 			$resultado=$resultado.'<label class="checkbox-inline"><input type="checkbox" name="apos_'.$row["id"].'" id="inlineCheckbox1" value="'.$row["id"].'">'.$row["nombre"].'</label>';
					}
				}
	 	 		return $resultado ;
	 	 	}
		}
	}
	
	function getListaApostantes($idPartido){
		$result=mysql_query("SELECT a.nombre 
							FROM partido_apostante pa
								LEFT JOIN apostantes a ON pa.idapostante=a.id
							WHERE pa.idpartido=".$idPartido." 
							ORDER BY a.nombre;");
		if(!$result){
			die('Error: ' . mysql_error());
 		}else{
 			$i=0;
 			while ($row=mysql_fetch_array($result)) {
 				if($i>0){
 					$resultado=$resultado.' - ';
 				}
 				$i++;
 				$resultado=$resultado.$row["nombre"];
			}
		}
		return $resultado;
	}
	
	function getApuestasParaUpdate($fecha_aux){
		$query='
			SELECT a.id, a.partido, a.apuesta, a.apostado, a.cotizacion, a.acertada as acertada, e.nombre as local, 
					e.escudo as escLocal, f.nombre as visitante, f.escudo as escVisit
			FROM partidos p
			INNER JOIN apuestas a ON a.PARTIDO=p.ID
			LEFT JOIN equipos e ON e.id= p.local
			LEFT JOIN equipos f ON f.id= p.visitante
			WHERE p.fecha=\''.$fecha_aux.'\' 
			ORDER BY p.fecha, p.hora, a.partido asc';
			
		
		$result=mysql_query($query);
		$numero_filas = mysql_num_rows($result);
		echo mysql_error();
		
		$partido=0;
      	$numApuestas=0;
      	$filaPartido="";
      	$filasApuestas="";
      	$iniFilaPartido="";
      	
		while ($row=mysql_fetch_array($result)) {		
			$iconoApuesta='';	
			$botonAccion='';
			if($row["acertada"]==1){
				$iconoApuesta= '<span class="glyphicon glyphicon-ok"></span> ';
				$botonAccion='<td id="acciones_'.$row["id"].'" style="text-align:right;" colspan="2">'.$iconoApuesta.'</td>';
			}else if($row["acertada"]==2){
				$iconoApuesta= '<span class="glyphicon glyphicon-remove"></span> ';
				$botonAccion='<td id="acciones_'.$row["id"].'" style="text-align:right;" colspan="2">'.$iconoApuesta.'</td>';
			}else{
				$iconoApuesta= '<span class="glyphicon glyphicon-time"></span> ';
				
				$botonAccion='<td id="acciones_'.$row["id"].'" class="text-right col-xs-3">';
				$botonAccion.='<button type="button" class="btn btn-success" onclick="actualizarApuesta(\''.$row["id"].'\',\'1\');"><span class="glyphicon glyphicon-ok"></span></button> ';
				$botonAccion.='<button type="button" class="btn btn-danger" onclick="actualizarApuesta(\''.$row["id"].'\',\'2\');"><span class="glyphicon glyphicon-remove"></span></button>';
				$botonAccion.='</td>';
			}

			if($partido==$row["partido"]){
				$resultado.='<tr><td>'.$row["apuesta"].'</td>'.$botonAccion.'</tr>';
			}else{
				if($partido!=0){
					$resultado.='</tbody></table>';
				}
				
				
				$resultado.='<table class="table table-condensed table-hover table-striped">';
				$resultado.='<thead><tr><th colspan="2" style="text-align: center;vertical-align: middle;">';
				$resultado.='<span>'.$row["local"]. '</span><img src="../../images/escudos/'.$row["escLocal"].'"> vs <img src="../../images/escudos/'.$row["escVisit"].'"><span> '.$row["visitante"].'</span>';
				$resultado.='</th></tr></thead><tbody>';
				$resultado.='<tr><td>'.$row["apuesta"].'</td>'. $botonAccion.'</tr>';
			}
			
			$partido=$row["partido"];
		}mysql_free_result($result);	
			
		$resultado.='</tbody> </table> ';
		return $resultado;
		}

	function getApuestasFases($idPartido){
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
						$resultado.='<i class="icon-ok"></i>';
					}else if($rowApu["acertada"]==2){
						$resultado.='<i class="icon-remove"></i>';
					}else{
						$resultado.='<i class="icon-time"></i>';
					}
				$resultado.='</td>';
				$resultado.='<td>'.$rowApu["apuesta"].'</td>';
				$resultado.='<td style="text-align:center">'.$rowApu["cotizacion"].'</td>';
				$resultado.='<td style="text-align:center">'.$rowApu["apostado"].'&euro;</td>';
				$resultado.='<td style="text-align:center">';
					$ganancia=0;
					if($rowApu["acertada"]==1){
						$ganancia= ($rowApu["apostado"] * $rowApu["cotizacion"])-$rowApu["apostado"];
					}else if($rowApu["acertada"]==2){
						$ganancia=$rowApu["apostado"] * -1;
					}
					$resultado.='<span class="hidden-phone">'.$ganancia.'&euro;</span>';
					$totalGan=$totalGan+ $ganancia;
				$resultado.='</td>';
				$resultado.='<td style="text-align:center"><span class="hidden-phone">'.$rowApu["nombre"].'</span></td>';
           	$resultado.='</tr>';
		}mysql_free_result($resultApuestas);
		$resultado.=mysql_error();
		$resultado.='<tr><td style="text-align:right" colspan="4"><strong>Ganancia Total</strong></td><td>'.$totalGan.'&euro;</td></tr>';
		
		return $resultado;
	}


	function getTabsApostadoresPorFase($idFase, $apostante){		
		//Número de apostantes en la fase
		$resultApostEnFase=mysql_query('SELECT num_apostantes FROM fases WHERE id = '.$idFase);
		$rowApostEnfase=mysql_fetch_array($resultApostEnFase);		
 	 	$numApostEnFase = $rowApostEnfase["num_apostantes"];
		

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
					ORDER BY nombre asc ";
					
		}		
		  
		$result = "";
		$resultApost=mysql_query($query);
		while($row=mysql_fetch_array($resultApost)){			
			if($apostante==$row["id"]){
				$result.= '<li class="active"><a href="./fase.php?id='.$idFase.'&apostante='.$row["id"].'&apostanteNom='.$row["nombre"].'">'.$row["nombre"].'</a></li>';
			}else{						
		  		$result.= '<li><a href="./fase.php?id='.$idFase.'&apostante='.$row["id"].'&apostanteNom='.$row["nombre"].'">'.$row["nombre"].'</a></li>';
			}
		}	
		mysql_free_result($resultApost);
		return $result;
	}	
	
	function getDiaSemana($dia){
		$diasem=date_format($dia, 'w');
		$result='';
		switch ($diasem) {
		    case 0:
		        $result='domingo';
		        break;
		    case 1:
		        $result='lunes';
		        break;
		    case 2:
		        $result='martes';
		        break;	
		    case 3:
		        $result='miércoles';
		        break;
		    case 4:
		        $result='jueves';
		        break;
		    case 5:
		        $result='viernes';
		        break;
		    case 6:
		        $result='sábado';
		        break;		        		        		        		        	        
		}
		return $result;
		
	}
?>


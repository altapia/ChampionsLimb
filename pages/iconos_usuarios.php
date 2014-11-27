<?
date_default_timezone_set('Europe/Madrid');

function get_ico_usuario($nombre){
	$ico="logo.png";
	if(strtolower($nombre)=="zato"){
		$ico="barbol.png";
	}else if(strtolower($nombre)=="tapia"){
		$ico="bear.png";
	}else if(strtolower($nombre)=="nano"){
		$ico="gnomo.png";
	}else if(strtolower($nombre)=="jorge"){
		$ico="schooles.png";
	}else if(strtolower($nombre)=="poles"){
		$ico="schooles.png";
	}else if(strtolower($nombre)=="matute"){
		$ico="doctor.png";
	}else if(strtolower($nombre)=="lucho"){
		$ico="ligh.png";
	}else if(strtolower($nombre)=="borja"){
		$ico="moro.png";
	}else if(strtolower($nombre)=="raúl"){
		$ico="trompetilla.png";
	}else if(strtolower($nombre)=="rulo"){
		$ico="trompetilla.png";
	}else if(strtolower($nombre)=="eurolimbo"){
		$ico="logo.png";
	}				
	return $ico;
}

?>
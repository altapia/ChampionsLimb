function insertaBotonera(){
	document.write("<p><a class='btn-mini btn-info' data-toggle='collapse' data-target='#divbotones' href='#'><i class='icon-user icon-white'></i> Iconos</a></p>");
	document.write("<div class='collapse' id='divbotones'>");
	document.write("<div class='line' style='margin: 2px;'>");
	document.write("<div class='btn-group' style='margin: 2px;'>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('barbol.png')\"  title='Zato'> <img src='./images/barbol.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('bear.png')\"  title='Tapia'> <img src='./images/bear.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('doctor.png')\"  title='Matute'> <img src='./images/doctor.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('gnomo.png')\"  title='Nano'> <img src='./images/gnomo.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('ligh.png')\"  title='Lucho'> <img src='./images/ligh.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('moro.png')\"  title='Borja'> <img src='./images/moro.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('schooles.png')\"  title='Poles'> <img src='./images/schooles.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('trompetilla.png')\"  title='Ra&uacute;l'> <img src='./images/trompetilla.png' height='20' width='20'/></button>");
	document.write("<button type='button'  class='btn' onClick=\"insertaIcono('saco.png')\"  title='Euros'> <img src='./images/saco.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('toilet.png')\"  title='Inodoro'> <img src='./images/toilet.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('ok.png')\"  title='Bieeeen!'> <img src='./images/ok.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('pene.png')\"  title='Bieeeen!'> <img src='./images/pene.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('tetas.png')\"  title='Bieeeen!'> <img src='./images/tetas.png' height='20' width='20'/></button>");
	document.write("<button type='button' class='btn' onClick=\"insertaIcono('culo.png')\"  title='Bieeeen!'> <img src='./images/culo.png' height='20' width='20'/></button>");
	document.write("</div>");
	document.write("</div>");
	document.write("</div>");
}

function insertaIcono(cadena){
	var formulario = document.getElementById("mensaje2");
	formulario.value += "<img src=\"./images/" + cadena +  "\" height=\"20\" width=\"20\"/>";
	formulario.focus();
}
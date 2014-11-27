function parseHtmlChars(cadena){
	cadena=cadena.replace('&Aacute;','Á');
	cadena=cadena.replace('&Eacute;','É');
	cadena=cadena.replace('&Iacute;','Í');
	cadena=cadena.replace('&Oacute;','Ó');
	cadena=cadena.replace('&Uacute;','Ú');
	cadena=cadena.replace('&aacute;','á');
	cadena=cadena.replace('&eacute;','é');
	cadena=cadena.replace('&iacute;','í');
	cadena=cadena.replace('&oacute;','ó');
	cadena=cadena.replace('&uacute;','ú');
	cadena=cadena.replace('&Auml;','Ä');
	cadena=cadena.replace('&Euml;','Ë');
	cadena=cadena.replace('&Iuml','Ï');
	cadena=cadena.replace('&Ouml;','Ö');
	cadena=cadena.replace('&Uuml;','Ü');
	cadena=cadena.replace('&auml;','ä');
	cadena=cadena.replace('&euml;','ë');
	cadena=cadena.replace('&iuml','ï');
	cadena=cadena.replace('&ouml;','ö');
	cadena=cadena.replace('&uuml;','ü');
	cadena=cadena.replace('&Acirc;','Â');
	cadena=cadena.replace('&Ecirc;','Ê');
	cadena=cadena.replace('&Icirc','Î');
	cadena=cadena.replace('&Ocirc;','Ô');
	cadena=cadena.replace('&Ucirc;','Û');
	cadena=cadena.replace('&acirc;','â');
	cadena=cadena.replace('&ecirc;','ê');
	cadena=cadena.replace('&icirc','î');
	cadena=cadena.replace('&ocirc;','ô');
	cadena=cadena.replace('&ucirc;','û');
	
	return cadena;

}
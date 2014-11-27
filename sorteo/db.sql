CREATE TABLE sort_apostantes_restantes (
  id int(11) NOT NULL AUTO_INCREMENT,
  json_apostante varchar(300) COLLATE utf8_bin NOT NULL,
  json_apostantes_en_rosco varchar(300) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=238 ;


CREATE TABLE sort_sorteados (
  id int(11) NOT NULL AUTO_INCREMENT,
  json_partido varchar(300) COLLATE utf8_bin NOT NULL,
  json_apostante varchar(300) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=37 ;

CREATE TABLE sort_sorteo (
  id int(11) NOT NULL AUTO_INCREMENT,
  json_partido_actual varchar(300) COLLATE utf8_bin NOT NULL,
  json_partido_prox varchar(300) COLLATE utf8_bin NOT NULL,
  json_apostante_actual varchar(300) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=223 ;
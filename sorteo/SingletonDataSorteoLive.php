<?php 


class SingletonDataSorteoLive
{
   private static $instancia;
   //Contiene apostantes, sorteados, actual y próximo
   private $data;

   private $apostantesRestantes;
   
   private $i;
   
   

   private function __construct()
   {
      
   }

   public static function getInstance()
   {
      if (  !self::$instancia instanceof self)
      {
         
         self::$instancia = new self;
      }
      
      return self::$instancia;
   }

   //carga en base de datos los datos desde la interfaz championslimb
   public function start(){
      //Cargo los datos iniciales

      // $con=mysqli_connect("localhost","root","root"); 

      // mysql_connect("localhost","root","root"); 
      // //selecciÃ³n de la base de datos con la que vamos a trabajar 
      
      // mysql_select_db("sorteo");

      require ("conexion_bd.php");
      //echo mysql_errno($con) . ": " . mysql_error($con). "\n";       
      $apostantes = json_decode(getApostantes());
      $prox_partido = json_decode(getProximoPartidoSorteo());
      mysql_query('delete from sort_apostantes_restantes');
      mysql_query('delete from sort_sorteados');
      mysql_query('delete from sort_sorteo');
      mysql_query("insert into sort_apostantes_restantes(json_apostante,json_apostantes_en_rosco) values('" . str_replace ( '"' , '%' , json_encode($apostantes)) . "','" . str_replace ( '"' , '%' , json_encode($apostantes)) . "')");
      
      mysql_query("insert into sort_sorteo(json_partido_prox) values('" . str_replace ( '"' , '%' , json_encode($prox_partido[0])) ."')"); 
      
       
      $this->data = array("live"=>true,"apostantes"=>$apostantes, "partidos_sorteados"=>array(), "apostantes_sorteados"=>array(), "partido_actual"=>NULL, "apostante_actual"=>NULL , "proximo"=>$prox_partido);
      $this->apostantesRestantes=$apostantes;
      //echo (json_encode($data));



      return json_encode($this->data);
   }

   public function fin(){
     mysql_query('delete from sort_apostantes_restantes');
     mysql_query('delete from sort_sorteados');
     mysql_query('delete from sort_sorteo');
  }

   public function getData(){
      return json_encode($this->data);
   }


   public function sortea(){
      error_log("función sortea");
      $result=mysql_query('SELECT json_apostante from sort_apostantes_restantes ');
      $resultSorteo=mysql_query('SELECT json_partido_actual, json_partido_prox, json_apostante_actual from sort_sorteo ');
      $resultSorteados=mysql_query('SELECT json_partido, json_apostante from sort_sorteados ');
      while ($row=mysql_fetch_array($result)){
         error_log("Iterando sort_apostantes_restantes".json_decode(str_replace('%','"',$row['json_apostante'])));
         $apostantesRestantes=json_decode(str_replace ( '%', '"' , $row['json_apostante']));
      }
      while ($row=mysql_fetch_array($resultSorteo)){
         $partido_actual=str_replace ( '%', '"' , $row['json_partido_actual']);
         $apostante_actual=str_replace ( '%', '"' , $row['json_apostante_actual']);
         $partido_prox=str_replace ( '%', '"' , $row['json_partido_prox']);
      }
      $partidos_sorteados=array();
      $apostantes_sorteados=array();
      while ($row=mysql_fetch_array($resultSorteados)){
         array_push($partidos_sorteados,str_replace ( '%', '"' , $row['json_partido']));
         array_push($apostantes_sorteados,str_replace ( '%', '"' , $row['json_apostante']));
      }

      if($partido_actual!=NULL && $apostante_actual !=NULL){
         error_log("Ejecuto insert: insert into sort_sorteados(json_partido,json_apostante) values('" . 
            str_replace ( '"' , '%' , $partido_actual) ."','". 
            str_replace ( '"' , '%' , $apostante_actual) ."')" );
         mysql_query("insert into sort_sorteados(json_partido,json_apostante) values('" . 
            str_replace ( '"' , '%' , $partido_actual) ."','". 
            str_replace ( '"' , '%' , $apostante_actual) ."')");
        
      }

      if (sizeof($apostantesRestantes)>0){
        

         $random = rand(0,sizeof($apostantesRestantes)-1);
         
         $apostante_actual = json_encode($apostantesRestantes[$random]);
         error_log("\n\nAPOSTANTE ACTUAL!!!:".$apostante_actual."\n");
         if($partido_prox!=null){
            $partido_actual = $partido_prox;
         }
        //Dejo la modificación de json_partido_actual para lo último, porque es el atributo que usa el ajax para refrescar los clientes
         // mysql_query("update sort_sorteo set json_partido_actual = '" . 
         //    str_replace ( '"' , '%' , $partido_actual). "'");
         mysql_query("update sort_sorteo set json_apostante_actual = '" . 
            str_replace ( '"' , '%' , $apostante_actual) . "'");
         $json_aa=json_decode($apostante_actual,true);
         $json_pa=json_decode($partido_actual,true);
         error_log(" updates ejecutados: apostante_actual.id:". $json_aa['id'] ."; partidoActual.id:". $json_pa['id']);
         //Confirmo a la api el sorteo:
         setApostantePartido($json_aa['id'] ,$json_pa['id']);
         //file_get_contents("http://hotelpene.com/mundialLimb/pages/api.php?q=set_apostante_partido&id_apostante=". $json_aa['id'] ."&id_partido=".$json_pa['id']);
         $prox_partido = json_decode(getProximoPartidoSorteo());
         //$ppp = '{"id":"'.rand(0,200).'","local":"Equipo local '.rand(0,200).'","visitante":"Equipo visitante '.rand(0,200).'","fecha":"2014-03-11","hora":"20:45:00","escLocal":"50037.png","escVisit":"52280.png"}';
        
         error_log("update sort_sorteo set json_partido_prox = '" . 
            str_replace ( '"' , '%' , json_encode($prox_partido[0])) . "'");
         mysql_query("update sort_sorteo set json_partido_prox = '" . 
            str_replace ( '"' , '%' , json_encode($prox_partido[0])) . "'");
         
         $apostantesRestantesFinales = array();
         error_log("apostantes restantes size: ". sizeof($apostantesRestantes));
         for($i=0;$i<sizeof($apostantesRestantes);$i++){
         	echo("\nItero con " . $i);
         	if ($i!=$random){
         		array_push($apostantesRestantesFinales,$apostantesRestantes[$i]);
         	}
         }
         
     
         error_log ("update sort_apostantes_restantes set json_apostante = '" . str_replace ( '"' , '%' , json_encode($apostantesRestantesFinales)) . "'");
         mysql_query("update sort_apostantes_restantes set json_apostante = '" . str_replace ( '"' , '%' , json_encode($apostantesRestantesFinales)) . "',
         												json_apostantes_en_rosco = '". str_replace ( '"' , '%' , json_encode($apostantesRestantes)) ."'");



        //Lo último , el atributo que usará el ajax para refrescar los clientes, cuando sé que ya está todo listo
         mysql_query("update sort_sorteo set json_partido_actual = '" . 
            str_replace ( '"' , '%' , $partido_actual). "'");
      }

      //confirmaSorteo($apostantesorteado,$this->data["actual"]);

   }

  


 
}


function getApostantes(){
//Número de apostantes en la fase
      $resultApostEnFase=mysql_query("SELECT num_apostantes, id FROM fases WHERE activa=1 ORDER BY id DESC");
      $rowApostEnfase=mysql_fetch_array($resultApostEnFase);      
      $numApostEnFase = $rowApostEnfase["num_apostantes"];
      $idFase = $rowApostEnfase["id"];
      

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
            ORDER BY nombre asc";
               
      }     
        
      $result=mysql_query($query);
      $total=array();
      while($row=mysql_fetch_object($result)){
         array_push($total,$row);   
      }
      mysql_free_result($result);
   
      return json_encode($total);

}


function getProximoPartidoSorteo(){
   /*
    * Se obtienen el próximo partido sin apostante
   */
   
   $queryPartidoSorteo="SELECT p.id,p.fecha, p.hora, e.nombre as local, f.nombre as visitante,  e.escudo as escLocal, f.escudo as escVisit 
               FROM partidos p
               LEFT JOIN partido_apostante pa ON p.id =pa.idpartido
               LEFT JOIN equipos e ON p.local=e.id
               LEFT JOIN equipos f ON p.visitante=f.id
               WHERE pa.idpartido is null
               ORDER BY p.fecha , p.hora ,p.id asc
               LIMIT 1;";
   //echo $queryPartidoSorteo;
   $result = mysql_query($queryPartidoSorteo);
                     
   if(!$result){
      die('Error: ' . mysql_error());
   }else{
      $total=array();
      while ($row = mysql_fetch_object($result)) {
      	$row->local=utf8_encode($row->local);
				$row->visitante=utf8_encode($row->visitante);
         array_push($total,$row);
      }
      mysql_free_result($result);
      
      return mb_convert_encoding(json_encode($total),'ISO-8859-1');
      }
}

/*
 * Devuelve estado=1 si ha habido error. estado=0 sino
 */
function setApostantePartido($idApostante,$idPartido){

   if($idApostante==null || $idApostante==''){
      $respuesta=array("estado"=>1,"error"=>"No se ha indicado el id del apostante");
      echo json_encode($respuesta);
      return;
   }
   if($idPartido==null || $idPartido==''){
      $respuesta=array("estado"=>1,"error"=>"No se ha indicado el id del partido");
      echo json_encode($respuesta);
      return;
   }
   
   $respuesta=array();
   if(!mysql_query("INSERT INTO partido_apostante (idpartido, idapostante)  VALUES ($idPartido,$idApostante)")){
      //die('Error: ' . mysql_error());
      $respuesta=array("estado"=>1,"error"=>mysql_error());
   }else{
      $respuesta=array("estado"=>0,"error"=>"");
   }
   return json_encode($respuesta);
}
?>
<?php 

   require('conexion_bd.php');   

/*******
   require('SingletonDataSorteoLive.php');
   $instancia = SingletonDataSorteoLive::getInstance();
   
      $instancia->start();

********/

   $result=mysql_query('SELECT json_apostante, json_apostantes_en_rosco from sort_apostantes_restantes ');
   $resultSorteo=mysql_query('SELECT json_partido_actual, json_partido_prox, json_apostante_actual from sort_sorteo ');
   $resultSorteados=mysql_query('SELECT json_partido, json_apostante from sort_sorteados ');
   while ($row=mysql_fetch_array($result)){
      $apostantesRestantes=str_replace ( '%', '"' , stripslashes($row['json_apostante']));
      $apostantesEnRosco=str_replace ( '%', '"' , stripslashes($row['json_apostantes_en_rosco']));
   }
   $sorteos=mysql_num_rows($resultSorteo);
   while ($row=mysql_fetch_array($resultSorteo)){
      $partido_actual=str_replace ( '%', '"' , stripslashes($row['json_partido_actual']));
      $apostante_actual=str_replace ( '%', '"' , stripslashes($row['json_apostante_actual']));
      $partido_prox=str_replace ( '%', '"' , stripslashes($row['json_partido_prox']));
   }
   $partidos_sorteados=array();
   $apostantes_sorteados=array();
   while ($row=mysql_fetch_array($resultSorteados)){
      array_push($partidos_sorteados,json_decode(str_replace ( '%', '"' , stripslashes($row['json_partido']))));
      array_push($apostantes_sorteados,json_decode(str_replace ( '%', '"' , stripslashes($row['json_apostante']))));
   }

   if($sorteos==0){
      $data = array("live"=>false);   
   }else{
      $data = array("live"=>true,"apostantes"=>json_decode($apostantesRestantes),"apostantes_en_rosco"=>json_decode($apostantesEnRosco), "partidos_sorteados"=>$partidos_sorteados, 
         "apostantes_sorteados"=>$apostantes_sorteados, "partido_actual"=>json_decode($partido_actual), "apostante_actual"=>json_decode($apostante_actual) , "proximo"=>json_decode($partido_prox));
   }

   echo stripslashes(json_encode($data));
?>
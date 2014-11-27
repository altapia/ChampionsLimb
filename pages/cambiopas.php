<?php
	$userid = $_POST['user'];
	$password_sha1 = sha1($_POST['password']);
	
	include "../pages/conexion_bd.php"; 
	$query="UPDATE user SET password_sha1='".$password_sha1."' WHERE userid=".$userid.";";
	//echo $query;
	if(!mysql_query($query)){
  			die('Error: ' . mysql_error());
	}

	if(isset($_POST["url"])){
 		header("Location: ".$_POST["url"]);	
 	}else{		
		header("Location: ./misapuestas.php");
	}
	
?>
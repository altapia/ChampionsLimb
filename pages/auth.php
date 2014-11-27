<?php
 include_once("conexion_bd_2.php");
echo mysql_error();
  $username = $_POST['username'];
  $password_sha1 = sha1($_POST['password']);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		/* create a prepared statement */
		if ($stmt = $mysqli->prepare("SELECT username, userid, admin FROM user WHERE username=? AND password_sha1=? ")) {

			/* bind parameters for markers */
			$stmt->bind_param("ss",$username,$password_sha1);
            
			
			/* execute query */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($user,$userid, $admin);

			/* fetch value */
			$row = $stmt->fetch();

			//printf("%s is in district %s\n", $username, $password_sha1);

			/* close statement */
			$stmt->close();
		}

		/* close connection */
		$mysqli->close();


  // clear out any existing session that may exist
  session_start();
  session_destroy();
  session_start();
  
  if ($row) {
    $_SESSION['signed_in'] = true;
    $_SESSION['username'] = $username;
	$_SESSION['userid'] = $userid;
	if($admin>0){
		$_SESSION['is_admin'] = true;
	}else{
		$_SESSION['is_admin'] = false;
	}
    
    header("Location: ". $_POST['url']);

	//echo 'ok';
  } else {
    $_SESSION['flash_error'] = "Usuario o contrase&ntilde;a incorrectos.";
    $_SESSION['signed_in'] = false;
    $_SESSION['is_admin'] = false;
    $_SESSION['username'] = null;
	$_SESSION['userid'] = -1;
    //echo 'naso ok';
    //echo $_SESSION['flash_error'];
	
	header("Location: ./login.php");
	
  }
  //echo $password_sha1;
?>
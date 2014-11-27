<?php 
	session_start();
	
	if (!$_SESSION['signed_in']) {
		$_SESSION['flash_error'] = "Please sign in";
		header("Location: ../login.php");
		exit; // IMPORTANT: Be sure to exit here!
	}

	//include 'validate.php';
	if(!$_SESSION['is_admin']){
		header("Location: ../../index.php");
		exit; // IMPORTANT: Be sure to exit here!
	}
?>
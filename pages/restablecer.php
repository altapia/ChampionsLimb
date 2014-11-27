<?php 	session_start();
error_reporting(E_ALL); ini_set('display_errors', 'On'); 
include_once("conexion_bd_2.php");
echo mysql_error();


if(!isset($_POST['username'])){
	session_destroy();	
  	session_start();
	$_SESSION['flash_error'] = "El usuario no existe";
	header("Location: ./login.php");
}
$username = $_POST['username'];

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

/*Obtenemos el email*/
if ($stmt = $mysqli->prepare("SELECT email FROM user WHERE username=? ")) {
	$stmt->bind_param("s",$username);
	$stmt->execute();
	$stmt->bind_result($email);
	$row = $stmt->fetch();
	$stmt->close();
}
$mysqli->close();

if( $email==null ||  $email==''){
	//echo 'El usuario no existe';
	//session_start();
  	session_destroy();	
  	session_start();
	$_SESSION['flash_error'] = "El usuario ".$username." no existe";
	header("Location: ./login.php");
	exit();
	return;
}


/*Restablecemos la contraseña*/
$password =  randomPassword();
$password_sha1 = sha1($password);

include("conexion_bd.php");
if(!mysql_query("UPDATE user SET password_sha1='$password_sha1' WHERE username='$username'")){
  	//die('Error: ' . mysql_error());
	//session_start();
  	session_destroy();
  	session_start();
	$_SESSION['flash_error'] = "Se produjo un error al restablecer la contrase&ntilde;a";
	header("Location: ./login.php");
	exit();
	return;
}


/*Se envía la contraseña por email*/

$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$cabeceras .= 'From: EUROLimbo <staff@hotelpene.com>' . "\r\n";
		
$titulo 	= 'ChampionsLimb - Contraseña restablecida';

$mensaje='<html><head><title>Restablecer contraseña</title><style>body{font-family: sans-serif,arial, verdana,helvetica;}</style></head>
		<body><h2>
		<img align="absmiddle" style="max-height: 30px;" src="http://hotelpene.com/championsLimb/images/logo.png">
		ChampionsLimb</h2>
		<p>'.strtoupper($username).', tu contrase&ntilde;a ha sido restablecida.<br> La nueva es: '.$password.'</p>';
$mensaje .='<p><strong><a href="http://hotelpene.com/championsLimb/">ChampionsLimb</a></strong></p></body></html>';		


mail($email,$titulo,$mensaje,$cabeceras);

//session_start();
session_destroy();
session_start();
$_SESSION['flash_error'] = "Se ha enviado la nueva contrase&ntilde;a a la direcci&oacute;n de e-mail ".$email;
$_SESSION['is_info'] = "1";
header("Location: ./login.php");

  
  function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

?>
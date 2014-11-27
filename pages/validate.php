<?php session_start();
if (!$_SESSION['signed_in']) {
$_SESSION['flash_error'] = "Usuario no autenticado";
header("Location: ./login.php");
exit; // IMPORTANT: Be sure to exit here!
}
?>
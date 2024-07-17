<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 

	include("../resources/PHP/Class.DB.php");
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		echo 'Could not initialize a session. Possible reasons: session data might be full or not possible to create a session. For security reasons the administration panel cannot be loaded. Exiting.';
		exit;
	}
	

	if($_REQUEST['csrf'] !== $_SESSION['uuid']) {
		echo 'Token is incorrect.';
		exit;
	}
	
	$_SESSION['uuid'] = NULL;
	$_SESSION['admin-uuid'] = NULL;
	$_SESSION['token'] = NULL;
	$_SESSION['loggedin'] = NULL;
	session_regenerate_id();
	
	header("Location: ../../index.php");
	exit;
?>
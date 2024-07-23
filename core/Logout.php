<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 

	include("../resources/PHP/Class.DB.php");
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		header("location: ../error/3/");
		exit;
	}

	if($_REQUEST['csrf'] !== $_SESSION['uuid']) {
		header("location: ../error/2/");
		exit;
	}
	
	$_SESSION = array();
	session_destroy();
	header("Location: ../../index.php");
	exit;
?>
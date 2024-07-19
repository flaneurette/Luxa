<?php

	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
	
	session_start(); 
	session_regenerate_id();

	include("resources/PHP/Class.DB.php");
	
	$db = new sql();
		
	if(isset($_REQUEST['id'])) { 
		$pageid =  $db->intcast($_REQUEST['id']);
		$table    = 'components';
		$column   = 'pid';
		$value    =  $pageid;
		$operator = '*';
		$result = $db->select($table,$operator,$column,$value);
		} else {
		$result = $db->query("SELECT * from `components` ORDER BY id DESC");
	}
	
echo json_encode($result);
?>
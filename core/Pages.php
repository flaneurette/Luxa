<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	session_regenerate_id();
	// login check
	if(isset($_SESSION['loggedin']) != '1') {
		header("Location: ../login/");
		exit;
	} 

	include("../resources/PHP/Class.DB.php");
	include("Cryptography.php");
	
	$cryptography 		= new Cryptography;
	$db = new sql();
	
	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
	} else {
			$token = $cryptography->getToken();
			$_SESSION['token'] = $token;
	}
	
	$_SESSION['admin-uuid'] = $cryptography->uniqueID();
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		header("location: ../error/3/");
		exit;
	}
	
	// create a new admin token
	if(!isset($_SESSION['uuid'])) {
		$token  = $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$token .= $cryptography->uniqueID();
		$_SESSION['uuid'] = $token;		
	} else {
		$token = $_SESSION['uuid'];
	}
	
	if(isset($_REQUEST['pid'])) { 
		$pageid = $db->intcast($_REQUEST['pid']);
	}
	
	if(isset($_REQUEST['delete'])) {
		if($_SESSION['uuid'] !== $_REQUEST['csrf']) {
			echo 'CSRF token is incorrect.';
			exit;
			} else {			
			$result = $db->delete('pages',$_REQUEST['delete']);
		}
	}
	
	$result = $db->query("SELECT * from pages ORDER BY id DESC"); 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo SITE;?>/assets/css/tokens.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cantarell:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<script src="<?php echo SITE;?>/assets/js/ui.js"></script>
	<title>PLAIN UI - Headless CMS</title>
</head>
<body>

<div class="container">
	<header class="header">
	<h1><a href="<?php echo SITE;?>index.php">PLAIN UI</a></h1>
	<ul class="navigate">
	<li><a href="<?php echo SITE;?>pages/">View pages</a></li>
	<li><a href="<?php echo SITE;?>pages/add/">Add page</a></li>
	<li><a href="<?php echo SITE;?>components/add/">Add component</a></li>
	<li><a href="<?php echo SITE;?>resources/">Resources</a></li>
	<li><a href="<?php echo SITE;?>settings/">Settings</a></li>
	<li><a href="<?php echo SITE;?>logout/<?php echo $token;?>/">Exit</a></li>
	</ul>
	</header>
	<nav class="nav">
	/ pages
	</nav>
	<article class="main">
	<table rowspan="" width="100%">
	<?php 
	for($i=0;$i<count($result);$i++){
	?>
		<tr>
		<td><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['page_name'];?></a></td>
		<td><a target="_blank" href="<?php echo SITE;?>API.php?id=<?php echo $db->intcast($result[$i]['id']);?>">API</a></td>
		<td width="500"></td>
		<td width="80"><a href="<?php echo SITE . 'pages/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/">delete</a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	</article>
</div>
</body>
</html>

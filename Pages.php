<?php
	ini_set('max_file_uploads', 30);
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
	
	//error_reporting(E_ALL);
	
	set_time_limit(0); 
	session_start(); 
	session_regenerate_id();

	include("resources/PHP/Class.ImageSanitize.php");
	include("resources/PHP/Class.DB.php");
	include("core/Cryptography.php");
	include("core/JSON.Converter.php");
	include("core/StoreData.php");
	
	$cryptography 		= new Cryptography;
	$sanitizer 	  		= new Sanitizer;
	$converter 	  		= new Converter;
	$storagecontainer 	= new StoreData;

	if(isset($_SESSION['token'])) {
		$token = $_SESSION['token'];
	} else {
			$token = $cryptography->getToken();
			$_SESSION['token'] = $token;
	}
	
	$_SESSION['admin-uuid'] = $cryptography->uniqueID();
	
	if(!isset($_SESSION['admin-uuid']) || empty($_SESSION['admin-uuid'])) {
		echo 'Could not initialize a session. Possible reasons: session data might be full or not possible to create a session. For security reasons the administration panel cannot be loaded. Exiting.';
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
		$pageid = (int)$_REQUEST['pid'];
	}

	
	$db = new sql();
	$result = $db->query("SELECT * from pages ORDER BY id DESC"); 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../assets/css/tokens.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cantarell:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<script src="../assets/js/ui.js"></script>
	<title>PLAIN UI - Headless CMS</title>
</head>
<body>

<div class="container">
	<header class="header">
	<h1>PLAIN UI.</h1>
	<ul class="navigate">
	<li><a href="../pages/">View pages</a></li>
	<li><a href="../pages/add/">Add page</a></li>
	<li><a href="../snippets/add/">Add snippet</a></li>
	</ul>
	</header>
	<nav class="nav">
	/ pages
	</nav>
	<article class="main">
	<form name="post" action="" method="POST" onsubmit="return do('textprocessing');" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="edit" value="1" />
	<input type="hidden" name="pageid" value="<?php echo $pageid;?>" />
	<ul>
	<?php 
	for($i=0;$i<count($result);$i++){
	?>
		<li><a href="../snippets/edit/<?php echo $result[$i]['id'];?>/"><?php echo $result[$i]['page_name'];?></a> - <a target="_blank" href="API.php?id=<?php echo $result[$i]['id'];?>">API</a></li>
	<?php
	}
	?>
	</ul>
	</form>
	</article>
</div>
</body>
</html>

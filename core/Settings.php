<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");

	session_start(); 
	session_regenerate_id();
	// login check
	if(isset($_SESSION['loggedin']) != '1') {
		header("Location: ../../../login/");
		exit;
	} 

	include("../resources/PHP/Class.DB.php");
	include("Cryptography.php");
	
	$cryptography = new Cryptography;
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
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_REQUEST['old_password']) && !empty($_REQUEST['new_password'])) {
				
				$newpassword1 = $_REQUEST['new_password'];
				$newpassword2 = password_hash($newpassword1, PASSWORD_DEFAULT);
				$id = 1;
				$table    = 'users';
				$columns  = ['password'];
				$values   = [$newpassword2];
				$db->update($table,$columns,$values,$id);
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo SITE;?>assets/css/tokens.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Cantarell:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
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
	<form name="post" action="" method="POST" id="form" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<nav class="nav">
	/ index / settings <input type="submit" onclick="plainui.post();" class="btn" value="save" />
	</nav>
	<article class="main">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<label>Old password: </label><input type="password" name="old_password" value="" placeholder="Old password" required>
	<label>New password: </label> <input type="password" name="new_password" value="" placeholder="New password" required>
	</article>
	</form>
</div>
</body>
</html>

<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");

	session_start(); 
	session_regenerate_id();	
	// login check
	if(isset($_SESSION['loggedin']) != '1') {
		header("Location: ../../login/");
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
		$pageid = $db->intcast($_REQUEST['pid']);
	}
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_POST['component_title']) && !empty($_POST['component_title'])) {
				// insert snippet.
				$id = $db->intcast($_POST['pageid']);
				$component_title_vars = $_POST['component_title'];
				$component_text_vars  = $_POST['component_text'];
				$table    = 'components';
				$columns  = ['pid','component_title','component_text'];
				$values   = [$id,$component_title_vars,$component_text_vars];
				$db->insert($table,$columns,$values);
			}
		}
	}
	
	$result = $db->query("SELECT * FROM pages");
	
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
	<script src="<?php echo SITE;?>assets/js/ui.js"></script>
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
	<li><a href="<?php echo SITE;?>logout/<?php echo $token;?>/">Exit</a></li>
	</ul>
	</header>
	<form name="post" action="" method="POST" id="form" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<nav class="nav">
	/ index / add / component on 		<select name="pageid">
		<?php 
		for($i=0;$i<count($result);$i++) { 
		?> 
		<option value="<?php echo $result[$i]['id'];?>"><?php echo $result[$i]['page_name'];?></option>
		<?php 
		} 
		?>
		</select> <input type="submit" onclick="document.getElementById('form').submit();" class="btn" value="update" />
	</nav>
	<article class="main">
	
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="edit" value="1" />
		<h1><div name="" contentEditable="true" id="titleditor" oninput="plainui.proc('titleditor','component_title');">Title</div></h1>
		<input type="hidden" name="component_title" id="component_title" value=""  />
		<textarea id="component_text" name="component_text" class="textarea"></textarea>
		<div name="component_text" contentEditable="true" name="post-message" class="texteditor" id="texteditor" oninput="plainui.proc('texteditor','component_text');" placeholder="Write...">Text...</div>
	</form>
	</article>
</div>
</body>
</html>

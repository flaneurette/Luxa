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

	if(!isset($pageid)) {
		echo 'PageID is required to edit this page.';
		exit;
	}
	
	if(isset($_POST)) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			// update components.
			if(isset($_POST['count'])) { 
				$len = $_POST['count'];
				} else {
				$len = 0;
			}
			
			for($i = 0; $i < $len; $i++) {
				
				$id = $db->intcast($_POST['id'.$i]);
				$component_title_vars = $_POST['component_title_' . $i];
				$component_text_vars  = $_POST['component_text_' . $i];
				$table    = 'components';
				$columns  = ['component_title','component_text'];
				$values   = [$component_title_vars,$component_text_vars];
				$db->update($table,$columns,$values,$id);
			}
		}
	}
	
	$table    = 'components';
	$column   = 'pid';
	$value    =  $pageid;
	$operator = '*';
	$result = $db->select($table,$operator,$column,$value); 

	$table    = 'pages';
	$column   = 'id';
	$value    =  $pageid;
	$operator = '*';
	$result_header = $db->select($table,$operator,$column,$value);	
	
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
	<nav class="nav">
	/ index / edit / components on <?php echo $result_header[0]['page_name'];?> <input type="submit" onclick="plainui.post();" class="btn" value="update" />
	</nav>
	<article class="main">
	<form name="post" action="" method="POST" id="form" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="hidden" name="edit" value="1" />
	<input type="hidden" name="pageid" value="<?php echo $pageid;?>" />
	<input type="hidden" name="count" id="count" value="<?php echo count($result);?>" />
	<?php 
	for($i=0;$i<count($result);$i++){
	?>
		<h1><div name="" contentEditable="true" id="titleditor-<?php echo $i;?>" oninput="plainui.proc('titleditor-<?php echo $i;?>','component_title_<?php echo $i;?>');"><?php echo $result[$i]['component_title'];?></div></h1>
		<input type="hidden" name="component_title_<?php echo $i;?>" id="component_title_<?php echo $i;?>" value="<?php echo $result[$i]['component_title'];?>"  />
		<textarea id="component_text_<?php echo $i;?>" name="component_text_<?php echo $i;?>" class="textarea"></textarea>
		<input type="hidden" name="id<?php echo $i;?>" value="<?php echo $db->intcast($result[$i]['id']);?>"  />
		<div name="component_text" contentEditable="true" name="post-message" class="texteditor" id="texteditor-<?php echo $i;?>" oninput="plainui.proc('texteditor-<?php echo $i;?>','component_text_<?php echo $i;?>');" placeholder="Write..."><?php echo $result[$i]['component_text'];?></div>
	<?php
	}
	?>
	</form>
	</article>
</div>
</body>
</html>
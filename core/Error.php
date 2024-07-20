<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	session_regenerate_id();
	
	include("../resources/PHP/Class.DB.php");
	
	if(isset($_REQUEST['reason'])) {
		
		switch($_REQUEST['reason']) {
			case 1:
			$error_message = "You have reached the maximum login attempts, please contact your database administrator to lift restriction.";
			break;
			case 2:
			$error_message = "Anti CSRF token is incorrect.";
			break;
			case 3:
			$error_message = "Could not initialize a session. Possible reasons: session data might be full or not possible to create a session. For security reasons the administration panel cannot be loaded. Exiting.";
			break;
			default:
			$error_message = "We've encountered an unknown error.";
			break;
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
	</ul>
	</header>
	<nav class="nav">
	/ error
	</nav>
	<article class="main">
	<h1>Error</h1>
		<div id="dialog-alert"><?php echo $error_message;?></div>
	</form>
	</article>
</div>
</body>
</html>

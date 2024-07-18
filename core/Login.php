<?php
	
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	session_regenerate_id();

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
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_REQUEST['username']) && !empty($_REQUEST['password'])) {
				
				$username = $db->clean($_POST["username"],'encode');
				$password = $db->clean($_POST["password"],'encode');
		
				$userprofile = [];
				$result = [];
				
				$table    = 'users';
				$column   = 'username';
				$value    =  $username;
				$operator = '*';
				$result = $db->select($table,$operator,$column,$value); 
				$result_attempt = $db->query('select * from users');
				
				if(count($result) >= 1 && !password_verify($password, $result[0]['password'])) {
					if($result_attempt[0]['attempts'] >= MAX_LOGIN_ATTEMPTS) {
						echo 'You have reached the maximum login attempts, please contact your database administrator to lift restriction.';
						$may_login = false;
						exit;
					}	
				} else if(count($result) >= 1 && password_verify($password, $result[0]['password'])) {
					
					$id = 1;
					$table    = 'users';
					$columns  = ['attempts'];
					$values   = [0];
					$db->update($table,$columns,$values,$id);
					
					$_SESSION['uid'] = $db->intcast($result[0]['id']);
					$_SESSION['profile'] = $result[0];
					$_SESSION['loggedin'] = '1';
					 header("Location: ../index.php");
					 exit;
					} else {
					$id = 1;
					$table    = 'users';
					$columns  = ['attempts'];
					$values   = [$result_attempt[0]['attempts'] + 1];
					$db->update($table,$columns,$values,$id);
				}
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
	</ul>
	</header>
	<nav class="nav">
	/ login
	</nav>
	<article class="main">
	<h1>Login</h1>
		<form name="login" method="POST" action="" class="loginform">
		<input type="hidden" name="csrf" value="<?php echo $token;?>" />
		<fieldset>
		<label>Username</label>
		<input type="text" name="username" value="" required>
		</fieldset>
		<fieldset>
		<label>Password</label>
		<input type="password" name="password" value="" required>
		</fieldset>
		<input type="submit" name="" value="Login" />
	</form>
	</article>
</div>
</body>
</html>

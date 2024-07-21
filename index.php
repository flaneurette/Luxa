<?php
	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
 
	session_start(); 
	session_regenerate_id();
	// login check
	if(isset($_SESSION['loggedin']) != '1') {
		header("Location: login/");
		exit;
	} 
	
	include("resources/PHP/Class.DB.php");
	include("core/Cryptography.php");
	
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
	$result = $db->query("SELECT * FROM components");
?>
<!DOCTYPE html>
<html>
<head>
<?php include("core/Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header">
	<?php include("core/Navigation.php");?>
	</header>
	<nav class="nav">
	/ index
	</nav>
	<article class="main">
	
	<textarea class="textresult"><?php echo json_encode($result,JSON_PRETTY_PRINT); ?></textarea>
	
	</article>
</div>
</body>
</html>

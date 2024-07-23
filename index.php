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
	
	require("configuration.php");
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
	
	<table rowspan="" width="100%">
	<?php 
	for($i=0;$i<count($result);$i++){
		if($result[$i]["component_image"] !='') {
			$image = str_replace('../','',$result[$i]["component_image"]);
				if(!file_exists(str_replace(SITE,'',$image))) {
					$image = "resources/content/thumb.png";
				}
			} else {
			$image = "resources/content/thumb.png";
		}
	?>
		<tr>
		<td width="150"><img src="<?php echo $image;?>" width="100"/></td>
		<td valign="top"><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['pid']);?>/"><?php echo $result[$i]['component_title'];?></a></td>
		<td valign="top"><a href="<?php echo SITE;?>API.php?filetype=unique&id=<?php echo $db->intcast($result[$i]['pid']);?>" target="_blank">API</a></td>
		<td valign="top"><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['pid']);?>/">EDIT</a></td>
		<td width="50"  valign="top"></td>
		</tr>
	<?php
	}
	?>
	</table>	
	</article>
</div>
</body>
</html>

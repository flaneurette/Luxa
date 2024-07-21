<?php
	
	include("Header.php");
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_FILES['resource']) && !empty($_FILES['resource'])) {
				foreach ($_FILES["resource"]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {
						$tmp_name = $_FILES["resource"]["tmp_name"][$key];
						$name = basename($_FILES["resource"]["name"][$key]);
						move_uploaded_file($tmp_name, UPLOAD_DIR. "/$name");
					}
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<?php include("Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header">
	<?php include("Navigation.php");?>
	</header>
	<form name="post" action="" method="POST" id="form" autocomplete="off"  enctype="multipart/form-data">
	<nav class="nav">
	/ index / add resources <input type="submit" onclick="plainui.post();" class="btn" value="add" />
	</nav>
	<article class="main">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<input type="file" name="resource[]"/>
	<input type="file" name="resource[]"/>
	<input type="file" name="resource[]"/>
	<input type="file" name="resource[]"/>
	<input type="file" name="resource[]"/>
	<input type="file" name="resource[]"/>
	</article>
	</form>
	<article class="main">
	<table width="100%">
	<?php 
	
	$dir   = UPLOAD_DIR;
	$files = scandir($dir, SCANDIR_SORT_DESCENDING);
	
	for($i=0;$i<count($files);$i++) {
		echo "<tr><td><a href=\"".UPLOAD_DIR.$files[$i]."\" target=\"_blank\">".$files[$i]."</a><td></tr>";
	}
	
	?>
	</table>
	</article>
</div>
</body>
</html>

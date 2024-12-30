<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['csrf'])) {
		if($_REQUEST['csrf'] === $_SESSION['uuid']) {
			if(isset($_REQUEST['file']) && !empty($_REQUEST['file'])) {
				if(!stristr($_REQUEST['file'],'../../')) {
					if(stripos($_REQUEST['file'],'.png',-4) || stripos($_REQUEST['file'],'.jpg',-4) || stripos($_REQUEST['file'],'.gif',-4)) {
						unlink($_REQUEST['file']);
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
	<nav class="nav">
	/ index / lightbox 
	</nav>
	<article class="main">
	<?php 
	
	$dir   = UPLOAD_DIR;
    $files = [];
    foreach (scandir($dir) as $file) {
        if (!in_array($file, array('.', '..'))) {
			if(stripos($file,'.png',-4) || stripos($file,'.jpg',-4) || stripos($file,'.gif',-4)) { 
				$files[$file] = filemtime($dir . '/' . $file);
			}
		}
    }
    arsort($files);
    $files = array_keys($files);
	
	for($i=0;$i<count($files);$i++) {
		echo "<div class=\"lightbox-item\">";
		echo "<a href=\"".UPLOAD_DIR.$db->clean($files[$i],'encode')."\" target=\"_blank\">";
		echo "<img class=\"component-image\" src=\"".UPLOAD_DIR.$db->clean($files[$i],'encode')."\" />";
		echo "</a>";
		echo "<span><a href=\"?csrf=".$token."&file=".UPLOAD_DIR.$db->clean($files[$i],'encode')."\" onclick=\"return confirm('Are you sure you want to remove this item?');\">remove</a></span>";
		echo "</div>";
	}
	
	?>
	</article>
</div>
</body>
</html>

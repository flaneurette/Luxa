<?php
	require("../configuration.php");
	include("Header.php");
	$result = $db->query("SELECT * from pages ORDER BY id DESC"); 
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
	/ API
	</nav>
	<article class="main">
	<label>API</label>
	<table width="100%">
	<?php 
	for($i=0;$i<count($result);$i++){
	?>
		<tr>
		<td width="100"><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['page_name'];?></a></td>
		<td width="200"><input type="text" value="<?php echo SITE . "API.php?filetype=json&id=" . $db->intcast($result[$i]['id']) ; ?>" /></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	</article>
</div>
</body>
</html>

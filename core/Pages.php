<?php
	require("../configuration.php");
	include("Header.php");

	if(isset($_REQUEST['delete'])) {
		if($_SESSION['uuid'] === $_REQUEST['csrf']) {			
			$result = $db->delete('pages',$_REQUEST['delete']);
		}
	}
	
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
	/ pages
	</nav>
	<article class="main">
	<table rowspan="" width="100%">
	<?php 
	for($i=0;$i<count($result);$i++){
	?>
		<tr>
		<td><a href="<?php echo SITE;?>components/edit/<?php echo $db->intcast($result[$i]['id']);?>/"><?php echo $result[$i]['page_name'];?></a></td>
		<td><a target="_blank" href="<?php echo SITE;?>API.php?id=<?php echo $db->intcast($result[$i]['id']);?>">API</a></td>
		<td width="500"></td>
		<td width="80"><a href="<?php echo SITE . 'pages/'.$token;?>/delete/<?php echo $db->intcast($result[$i]['id']);?>/">delete</a></td>
		</tr>
	
	<?php
	}
	?>
	</table>
	</article>
</div>
</body>
</html>

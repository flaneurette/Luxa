<?php
	require("../configuration.php");
	include("Header.php");
	
	if(isset($_REQUEST['pid'])) { 
		$pageid = $db->intcast($_REQUEST['pid']);
	}

	if(!isset($pageid)) {
		header("location: ../error/4/");
		exit;
	}
	
	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			if(isset($_POST['count'])) { 
				$len = $db->intcast($_POST['count']);
				} else {
				$len = 1;
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
<?php include("Meta.php");?>
</head>
<body>

<div class="container">
	<header class="header" >
	<?php include("Navigation.php");?>
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
		if($result[$i]["component_image"] !='') {
			$image = str_replace('../','',$result[$i]["component_image"]);
				if(!file_exists(str_replace(SITE,'',$image))) {
					$image = "../../../resources/content/thumb.png";
				}
			} else {
			$image = "../../../resources/content/thumb.png";
		}
	?>
		
		<h1><div name="" contentEditable="true" id="titleditor-<?php echo $i;?>" oninput="plainui.proc('titleditor-<?php echo $i;?>','component_title_<?php echo $i;?>');"><?php echo $db->clean($result[$i]['component_title'],'encode');?></div></h1>
		<img src="<?php echo $image;?>" width="262" class="component-image"/><input type="hidden" name="component_title_<?php echo $i;?>" id="component_title_<?php echo $i;?>" value="<?php echo $result[$i]['component_title'];?>"  />
		<textarea id="component_text_<?php echo $i;?>" name="component_text_<?php echo $i;?>" class="textarea"></textarea>
		<input type="hidden" name="id<?php echo $i;?>" value="<?php echo $db->intcast($result[$i]['id']);?>"  />
		<div name="component_text" style="overflow-y:scroll;" contentEditable="true" name="post-message" class="texteditor" id="texteditor-<?php echo $i;?>" oninput="plainui.proc('texteditor-<?php echo $i;?>','component_text_<?php echo $i;?>');" placeholder="Write..."><?php echo $result[$i]['component_text'];?></div>
	<?php
	}
	?>
	</form>
	</article>
</div>
</body>
</html>
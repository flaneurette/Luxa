<?php
	require("../configuration.php");
	include("Header.php");

	if(isset($_POST['csrf'])) {
		if($_POST['csrf'] === $_SESSION['uuid']) {
			
			if(isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
				// insert component
				$component_title_vars = $db->clean($_REQUEST['page'],'encode');
				$table    = 'pages';
				$columns  = ['page_name','sub','meta_title','meta_description','meta_tags'];
				$values   = [$component_title_vars,0,$db->clean($_POST['meta_title'],'encode'),$db->clean($_POST['meta_description'],'encode'),$db->clean($_POST['meta_tags'],'encode')];
				$db->insert($table,$columns,$values);
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
	<form name="post" action="" method="POST" id="form" autocomplete="off" data-lpignore="true" enctype="multipart/form-data">
	<nav class="nav">
	/ index / add page <input type="submit" onclick="plainui.post();" class="btn" value="add" />
	</nav>
	<article class="main">
	<input type="hidden" name="csrf" value="<?php echo $token;?>" />
	<label>Slug</label>
	<input type="text" name="page" value="" placeholder="Type here: /design/ or /index/" />
	<label>Meta title</label>
	<input type="text" name="meta_title" value="" placeholder="" />
	<label>Meta description</label>
	<input type="text" name="meta_description" value="" placeholder="" />
	<label>Meta tags</label>
	<input type="text" name="meta_tags" value="" placeholder="A comma separated list" />
	</article>
	</form>
</div>
</body>
</html>

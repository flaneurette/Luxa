<?php

	header("X-Frame-Options: DENY"); 
	header("X-XSS-Protection: 1; mode=block"); 
	header("Strict-Transport-Security: max-age=30");
	header("Referrer-Policy: same-origin");
	
	session_start(); 
	session_regenerate_id();

	include("resources/PHP/Class.DB.php");
	
	$db = new sql();
		
	if(isset($_REQUEST['id'])) { 
		$pageid 	=  $db->intcast($_REQUEST['id']);
		$table    	= 'components';
		$column   	= 'pid';
		$value    	=  $pageid;
		$operator 	= '*';
		$result 	= $db->select($table,$operator,$column,$value);
		} else {
		$result = $db->query("SELECT * from `components` ORDER BY id DESC");
	}

	if(isset($_REQUEST['filetype'])) {
		if($_REQUEST['filetype'] == 'array') {
			var_dump($result);
		} elseif($_REQUEST['filetype'] == 'unique') {
			for($i = 0; $i < count($result); $i++) {
				$result[$i]["component_title_".$i] = $result[$i]["component_title"];
				$result[$i]["component_text_".$i] = $result[$i]["component_text"];
				unset($result[$i]["component_title"]);
				unset($result[$i]["component_text"]);
			}
			echo json_encode($result);
		} elseif($_REQUEST['filetype'] == 'csv') {
			$file = 'resources/content/temp.csv';
			$fp = fopen($file, 'w');
			foreach ($result as $fields) {
				fputcsv($fp, $fields);
			}
			fclose($fp);
			if(file_exists($file)) {
				readfile($file);
			}
		} else {
			echo json_encode($result);
		}
		
	} else {
		echo json_encode($result);
	}
?>
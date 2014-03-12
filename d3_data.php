<?php
	$num = $_GET['type'];
	require_once 'db_helper.php';
		$data = d3_originalinfo_get ($num);
		$json_string = json_encode ( $data );
		echo $json_string;
	?>
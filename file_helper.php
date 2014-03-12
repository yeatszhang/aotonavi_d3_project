<?php
function upload_file($file_tool_name, $file_save_path) {
	if ($_FILES [$file_tool_name] ["error"] > 0) {
		echo "Error: " . $_FILES [$file_tool_name] ["error"] . "<br />";
	} else {
		echo "Upload: " . $_FILES [$file_tool_name] ["name"] . "<br />";
		echo "Type: " . $_FILES [$file_tool_name] ["type"] . "<br />";
		echo "Size: " . ($_FILES [$file_tool_name] ["size"] / 1024) . " Kb<br />";
		echo "Stored in: " . $_FILES [$file_tool_name] ["tmp_name"];
		
		if (file_exists ( $file_save_path )) {
			echo $_FILES [$file_tool_name] ["name"] . " already exists. ";
		} else {
			move_uploaded_file ( $_FILES [$file_tool_name] ["tmp_name"], $file_save_path );
			echo "Stored in: " . $file_save_path;
		}
	}
}
?>
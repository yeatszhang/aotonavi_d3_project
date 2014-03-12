<?php
require_once 'db_helper.php';
require_once 'file_helper.php';
$uploadFile = "uploads/path/" . $_FILES["userfile"]["name"];
upload_file('userfile',$uploadFile);
echo '<h1>浏览已上传路径文件文件</h1>';
	$cureent_dir = 'uploads/path';
	$dir = opendir ( $cureent_dir );
	echo "<p>Upload directory is $cureent_dir</p>";
	echo '<p>Directory Listening:</p><ul>';
	while ( false !== ($file = readdir ( $dir )) ) {
		if ($file != "." && $file != "..") {
			echo "<li><a href=\"show_signal_path.php?file=$file\" >$file</a></li>";
		}
	}
	echo '</ul>';
	closedir ( $dir );
?>
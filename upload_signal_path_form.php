<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<title>显示路测轨迹</title>
</head>
<body>
	<form action="upload_signal_path.php" method="post" enctype="multipart/form-data">
	<p>导入路径数据表</p>
	<label for="userfile">Filename:</label>
	<input type="file" name="userfile" id="userfile" />
	<input type="submit" value="Send File" />
	</form>
	<div id="l-map"></div>
</body>
</html>

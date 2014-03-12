
<html>
<head>
  <title>Uploading...</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<h1>Uploading file...</h1>
<?php
require_once 'db_helper.php';
require_once 'file_helper.php';
set_time_limit(0);
$uploadFile = "uploads/" . $_FILES["userfile"]["name"];
upload_file('userfile',$uploadFile);

  //连接数据库文件
  db_connect();
  
  originalinfo_upload($uploadFile,$_POST["people"]);
  
  // show what was uploaded

  file_get_contents("http://localhost/signalinfo/CalcAverVari.php");
?>
</body>
</html>
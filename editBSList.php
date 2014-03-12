<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/bootstrap.css" />
<title>信道环境测量演示系统</title>


<style type="text/css">

</style>
</head>

<body>
	<script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.fancybox.pack.js"></script>
	<!----------------------------------------------------------------------------------------->
	 <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">RSSS</a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
             <li><a href="original_data_import_form.php">上传数据</a></li>
			<li><a href="upload_signal_path_form.php">查看单表</a></li>
			<li><a href="editBSList.php">管理基站</a></li>
			<!--  <li><a href="">帮助</a></li>-->
			<!-- <li><a href="">联系我们</a></li> -->
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
      
      
	  <!-- Begin page content -->
       <div class="container" style="margin-top:40px;">
        <div class="page-header">
          <h1>The Presentation System Of Channel Environment</h1>
        </div>
      <div class="row">
      <form  action="editList.php" method="get" >
        <table class="table table-striped">
		<tr><td>Longitude</td><td>Lautitude</td><td>Radius</td><td>Name</td><td>PCI1</td><td>Degree1</td><td>Degree2</td><td>PCI2</td><td>Degree3</td><td>Degree4</td><td>PCI3</td><td>Degree5</td><td>Degree6</td><td>操作</td></tr>
		<?php
		require_once("db_helper.php");
		 $result = getBasestation();
		 for($i = 0; $i < count($result); $i++) {
		        echo "<tr><td>" . $result[$i]['Longitude'] . "</td>";
                echo "<td>" . $result[$i]['Latitude'] . "</td>";
				echo "<td>" . $result[$i]['Radius'] . "</td>";
				echo "<td>" . $result[$i]['Name'] . "</td>";
				echo "<td>" . $result[$i]['PCI1'] . "</td>";
				echo "<td>" . $result[$i]['Degree1'] . "</td>";
				echo "<td>" . $result[$i]['Degree2'] . "</td>";
				echo "<td>" . $result[$i]['PCI2'] . "</td>";
				echo "<td>" . $result[$i]['Degree3'] . "</td>";
				echo "<td>" . $result[$i]['Degree4'] . "</td>";
				echo "<td>" . $result[$i]['PCI3'] . "</td>";
				echo "<td>" . $result[$i]['Degree5'] . "</td>";
				echo "<td>" . $result[$i]['Degree6'] . "</td>";
				$ID = $result[$i]['Id'];
				echo "<td> <a href=\"editBS_form.php?operation=EDIT&id=$ID\">edit</a>
        			 <a href=\"editBS_form.php?operation=DELETE&id=$ID\">delete</a><td></tr>";
		 }
         ?>
		</table>
            <a href="editBS_form.php?operation=ADD">add a new station</a>
        </form>   
      </div>
      </div>

    <div id="footer">
      <div class="container">
        <p class="text-muted">HuaWei--the presentation system of channel
						environment</p>
      </div>
    </div>

</body>
</html>


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
				<button type="button" class="navbar-toggle" data-toggle="collapse"
					data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
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
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>


	<!-- Begin page content -->
	<div class="container" style="margin-top: 40px;">
		<div class="page-header">
			<h1>The Presentation System Of Channel Environment</h1>
		</div>
		<div id="form-mes" style="margin-left: 90px;">
		<div class="row col-xs-4" style="margin-right: 10px;">
			<form action="original_data_import.php" method="post"
				enctype="multipart/form-data">
				<h3>导入原始数据</h3>
				<div class="form-group" style="margin-top: 20px;">
					<label for="userfile">选择导入文件:</label> <input type="file"
						name="userfile" id="userfile" /> <label for="people">请选择用户数:</label>
					<select id="people" name="people" class="form-control">
						<option value=1 selected="selected">1</option>
						<option value=2>2</option>
						<option value=3>3</option>
						<option value=4>4</option>
						<option value=5>5</option>
						<option value=0>更多</option>
					</select>
					<p class="help-block">请选择想要上传的文件,选择上传文件的用户数，并点击上传按钮</p>
					<input type="submit" class="btn btn-primary" value="上传" />
				</div>
			</form>
		</div>
		<div class="row col-xs-4" style="margin-right: 10px;">
			<form action="resetPrecision.php" method="post"
				enctype="multipart/form-data">
				<h3>修改数据精度</h3>
				<div class="form-group">
					<label for="people">栅格精度:</label> <select id="precision"
						name="precision" class="form-control">
						<option value=0 selected="selected"></option>
						<option value=5>5</option>
						<option value=10>10</option>
						<option value=20>20</option>
						<option value=30>30</option>
						<option value=0>其他</option>
					</select>
					<p class="help-block">请选择合适的精度</p>
					<input type="submit" class="btn btn-primary" value="修改" />
				</div>
			</form>
		</div>
		<div class="row">
		<h3>清空数据库</h3>
		<div class="form-group">
		<label>点击按钮清除所有数据库中的数据</label>
		<br />
			<button type="submit" class="btn btn-primary" onclick="window.location.href('data_clear.php')">清除数据</button>
		</div>
		</div>
		</div>
	</div>


	<div id="footer">
		<div class="container">
			<p class="text-muted">HuaWei--the presentation system of channel
				environment</p>
		</div>
	</div>


	<script type="text/javascript">
		$(function(){
			$("#precision option:first").text("现在精度为:"+<?php include_once 'params.php'; echo $_config['params']['ACCURACY_DEFAULT']; ?>);
	$("select").change(function(){
		if($(this).attr("id")=="people"){
			if($("#people").val()==0){
				$("#divofpeople").html("<label for='people'>用户数:</label> <input name='people' type='text' />");
			}
		}
		if($(this).attr("id")=="precision"){
			if($("#precision").val()==0){
				$("#divofprecision").html("<label for='people'>栅格精度:</label> <input name='precision' type='text' />");
			}
		}
	});
});
</script>
</body>
</html>

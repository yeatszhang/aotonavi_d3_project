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
      <div class="col-md-4  col-md-offset-2">
      <form action="editBS.php" method="post" role="form">
    	<?php
			require_once 'db_helper.php';
			$operation = $_GET['operation'];
			$id = $_GET['id'];

			if($operation == 'DELETE') {
				delete_basestation($id);
				echo "基站数据已成功删除。";
			}
			if($operation == 'ADD') {
				?>
				<div class="form-group">
   				 <label for="lng">Longitude:</label>
  				  <input type="text"  class="form-control" name="lng" />
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">Latitude:</label>
  				  <input type="text" class="form-control" name="lat" />
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">Radius:</label>
  				  <input type="text" class="form-control" name="r" />
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">Name:</label>
  				  <input type="text" class="form-control" name="n" />
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">PCI1:</label>
  				  <input type="text" class="form-control" name="pci1" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree1:</label>
  				  <input type="text" class="form-control" name="d1" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree2:</label>
  				  <input type="text" class="form-control" name="d2" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">PCI2:</label>
  				  <input type="text" class="form-control" name="pci2" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree3:</label>
  				  <input type="text" class="form-control" name="d3" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree4:</label>
  				  <input type="text" class="form-control" name="d4" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">PCI3:</label>
  				  <input type="text" class="form-control" name="pci3" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree5:</label>
  				  <input type="text" class="form-control" name="d5" />
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree6:</label>
  				  <input type="text" class="form-control" name="d6" />
 				 </div>
				<input type="hidden" class="btn btn-primary" name="operation" value = "ADD" />
				<input type = "submit" class="btn btn-primary" value="添加"> 
				<?php 
			}
			else if($operation == 'EDIT') {
				$result = get_wish_by_wish_id($id);
				?>
				<div class="form-group">
   				 <label for="lng">Longitude:</label>
  				  <input type="text"  class="form-control" name="lng" placeholder="<?php echo $result['Longitude']; ?>" />
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">Latitude:</label>
  				  <input type="text" class="form-control" name="lat" placeholder="<?php echo $result['Latitude']; ?>" />
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">Radius:</label>
  				  <input type="text" class="form-control" name="r" placeholder="<?php echo $result['Radius']; ?>"/>
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">Name:</label>
  				  <input type="text" class="form-control" name="n" placeholder="<?php echo $result['Name']; ?>"/>
 				 </div>
 				 <div class="form-group">
   				 <label for="lng">PCI1:</label>
  				  <input type="text" class="form-control" name="pci1" placeholder="<?php echo $result['PCI1']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree1:</label>
  				  <input type="text" class="form-control" name="d1" placeholder="<?php echo $result['Degree1']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree2:</label>
  				  <input type="text" class="form-control" name="d2" placeholder="<?php echo $result['Degree2']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">PCI2:</label>
  				  <input type="text" class="form-control" name="pci2" placeholder="<?php echo $result['PCI2']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree3:</label>
  				  <input type="text" class="form-control" name="d3" placeholder="<?php echo $result['Degree3']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree4:</label>
  				  <input type="text" class="form-control" name="d4" placeholder="<?php echo $result['Degree4']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">PCI3:</label>
  				  <input type="text" class="form-control" name="pci3" placeholder="<?php echo $result['PCI3']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree5:</label>
  				  <input type="text" class="form-control" name="d5" placeholder="<?php echo $result['Degree5']; ?>"/>
 				 </div>
 				  <div class="form-group">
   				 <label for="lng">Degree6:</label>
  				  <input type="text" class="form-control" name="d6" placeholder="<?php echo $result['Degree6']; ?>"/>
 				 </div>
				<input type="hidden" class="btn btn-primary" name="operation" value = "ADD" />
				<input type = "submit" class="btn btn-primary" value="修改"> 
				<input type="hidden" name="operation" value = "EDIT" />
				<input type="hidden" name="id" value = "<?php echo $id ?>" /> 
				<?php 
			}					 
		?>
	</form>
      </div>
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
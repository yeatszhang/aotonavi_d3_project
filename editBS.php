 <html>
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   </head>
   <body>
   	<?php
		require_once 'db_helper.php';
		$id = $_POST['id'];
		$operation = $_POST['operation'];
		$lng = $_POST['lng'];
		$lat = $_POST['lat'];
		$r = $_POST['r'];
		$n = $_POST['n'];
		$d1 = $_POST['d1'];
		$d2 = $_POST['d2'];
		$d3 = $_POST['d3'];
		$d4 = $_POST['d4'];
		$d5 = $_POST['d5'];
		$d6 = $_POST['d6'];
		$pci1 = $_POST['pci1'];
		$pci2 = $_POST['pci2'];
		$pci3 = $_POST['pci3'];
		
		
		if($operation == "ADD") {
			insertBasestation($lng, $lat, $r, $n,$pci1, $d1, $d2,$pci2, $d3, $d4,$pci3, $d5, $d6);
			echo "insert complete";
		}
		else if($operation == "EDIT") {
			updateBasestation($id, $lng, $lat, $r, $n,$pci1, $d1, $d2,$pci2, $d3, $d4,$pci3, $d5, $d6);
			echo "update complete";
		}	
	?>
	</br >
	 <a href="editBSList.php">return to index</a>"
   </body>
</html>

	
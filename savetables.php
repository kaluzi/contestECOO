<?php
	include('common.php');
	myconnect();
	
	$val = mysql_query('select 1 from `yearlist`');

		if($val === FALSE)
		{

		//---------------------create a table
		$sql_qy ="CREATE TABLE `yearlist`(id INT NOT NULL AUTO_INCREMENT, year INT, PRIMARY KEY ( `id` ))"; 
		$rslt = mysql_query( $sql_qy );
		//----------------------------------
		}
		for ($i = 1986; $i <= 2008; $i++){
		$sql_qy = "INSERT INTO `yearlist` ";
		$sql_qy .= "(`year`) ";
		$sql_qy .= "VALUES (";
		$sql_qy .= " '$i' )";
		$rslt = mysql_query( $sql_qy ) or die ("insert was failed.");
		echo "<p>The insert was successful.\n</p>";
		}
	
?>
<?php
include "translate.php"; 

//*** add loop here to go through every question

	$source= $temp;
	$source=preg_replace("/&nbsp;/"," ",$source);
	$source=preg_replace("/<br*>/","\n",$source);
	//$source=file_get_contents("turingsol2.txt");	//reads turing solution from text file into string $source. ***change to read from database
	//lines of turing solution are separated by \n not <br/>
	
	//$file = fopen("test.txt","w"); //write translated program to this file 

	$progname="Test";//name of program ***change to read from database
	$progname=explode(" ",$progname);
	$progname=implode($progname);//get rid of spaces
	
	$output=translate($source, $progname);//translates the solution
	
	//***change below to output to database
	echo "<pre>";
	$output=preg_replace("/\r\n/","<br/>",$output);
	$output=str_replace ( "/<br/><br/>/" , "<br/>" , $output);
	//$output=nl2br($output);
	//echo $output;
	$sql_qy = "UPDATE `$year` SET ";
	$sql_qy .= "translated = '$output' ";
	$sql_qy .= "WHERE title = '$datatitle' ";
	$result = mysql_query( $sql_qy ) or die("Update failed!!!");
	//echo "<p>The update was successful.\n</p>";
	//fwrite($file,$output);
	//fclose($file);

//***end loop
?>
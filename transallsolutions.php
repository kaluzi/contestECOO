<?php
include "translate.php"; 

//*** add loop here to go through every question

	$source=file_get_contents("turingsol2.txt");	//reads turing solution from text file into string $source. ***change to read from database
	//lines of turing solution are separated by \n not <br/>
	
	$file = fopen("test.txt","w"); //write translated program to this file 

	$progname="Test";//name of program ***change to read from database
	$progname=explode(" ",$progname);
	$progname=implode($progname);//get rid of spaces
	
	$output=translate($source, $progname);//translates the solution
	
	//***change below to output to database
	fwrite($file,$output);
	fclose($file);

//***end loop
?>
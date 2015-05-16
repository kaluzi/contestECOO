<?php
	include('common.php');
		myconnect();
	include('readfile.php');
	//$file = "filen/reg1991p4.html";
	//foreach(glob('files/boardwide/*.html') as $file){
	foreach(glob('filen/*.html') as $file){
		rdfile($file);
	}
	
?>
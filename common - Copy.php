<?php
// common.php for accessing pirates database
	function myconnect()
	{
	   $id_link = mysql_connect('localhost', 'ayu', 'chunmeng');
	   if (! $id_link)
		  die( "The connection to the local MySQL server has failed.");
	   $dbexists = mysql_select_db( "ayu" );
	   if(! $dbexists)
		  die("test1 database not found!");
	} 
?>
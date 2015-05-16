<?php
// common.php for accessing pirates database
function myconnect()
{
   $id_link = mysql_connect('localhost', 'root', '27131025651441');
   if (! $id_link)
      die( "The connection to the local MySQL server has failed.");
   $dbexists = mysql_select_db( "test1" );
   if(! $dbexists)
      die("pirates database not found!");
} // end myconnect
?> 

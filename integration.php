<?php
	session_start();
	if(!isset($_SESSION['username']) || $_SESSION['username']=="")
	{
		echo "Please login to see this page.";
	}
	else
	{
		echo "content :))";
	}
?>
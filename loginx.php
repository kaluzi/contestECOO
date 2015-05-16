<?
	include "connect.php";
	$username=mysql_real_escape_string($_POST['username']);
	$password=mysql_real_escape_string($_POST['password']);
	$sql="SELECT * FROM `login_system` WHERE username='$username' and password='$password'";
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);
	if($count==1)
	{
		session_start();
		$_SESSION['username']=$username;
		$_SESSION['password']=$password;

                include("headmenu.php");
                echo "You have successfully logged in!";
                include("comments.php");
                echo "</table>";


	}
	else
	{
                include("headmenu.php");
		echo "Please try again.";
                include("comments.php");
                echo "</table>";
	}
	
?>
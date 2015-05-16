<?
	include "connect.php";
	$username=mysql_real_escape_string($_POST['username']);
	$password=mysql_real_escape_string($_POST['password']);
	$name=mysql_real_escape_string($_POST['name']);
	$email=mysql_real_escape_string($_POST['email']);
        if (($username!="")&&($password!="")&&($name!="")&&($email!="")) {
	$insert=mysql_query("INSERT INTO login_system (username,password,name,email) VALUES ('$username','$password','$name','$email')") or die("Not Connected");
	include("headmenu.php");
        echo "You have successfully registered!";
        include("comments.php");
        echo "</table>";
        }

        else
        {
        include("headmenu.php");
        echo "Please try again!";
        include("comments.php");
        echo "</table>";
        }


?>

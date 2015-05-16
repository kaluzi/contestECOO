<?

$dbhost = "localhost";
$dbname = "ayu";
$dbuser = "ayu";
$dbpass = "chunmeng";
mysql_connect ( $dbhost, $dbuser, $dbpass)or die("Could not connect to MySQL: ".mysql_error());
mysql_select_db($dbname) or die(mysql_error());
?>

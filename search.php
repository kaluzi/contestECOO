<?php
include "searchbar.php";
include('common.php'); //change to our database
myconnect();
echo "<br>";
$firstyear=1986;
$lastyear=2008;
$found=false;

if (isset($_REQUEST['key']))
{
  $key=$_REQUEST['key'];
  for($year=$firstyear; $year<=$lastyear; $year++)
  {
    $sql_query = "SELECT * FROM `$year`"; //change this to different table
    $result = mysql_query($sql_query ) or die ();

    for($i = 1; $row = mysql_fetch_array($result); $i++) 
    {
      $s=$row['title']." ".$row['prob'];
      if (preg_match("/\b".strtolower($key)."\b/",strtolower($s)) )
      {
	$temp=$row['title'];
	echo "<h3><a style = \"color:#000000\" href=\"viewdata.php?year=".$year."&y=".$row['ctype']."&x=".$temp."\">". $year.", ".$row['ctype']."- ".$temp."</a>"."</h3><br/>".$row['prob'];
	echo "<br/>-----------------------------------------------------<br/>";
	$found=true;
      }
    }

  }
if(!$found)
echo "No results were found.";
}
?>

<?php
include "searchbar.php";
include('common.php'); //change to our database
myconnect();
echo "<hr/>";
//$firstyear=1990;
//$lastyear=date('Y');
$firstyear=2002;
$lastyear=2002;

$found=false;

if (isset($_REQUEST['key']))
{
  $key=$_REQUEST['key'];
  for($year=$firstyear; $year<=$lastyear; $year++)
  {
    $sql_query = "SELECT * FROM $year t"; 
    $result = mysql_query($sql_query ) or die ();
    for($i = 1; $row = mysql_fetch_array($result); $i++) 
    {
      $s=$row['prob']." ".$row['sol'];//change
      echo $s;
      if (stripos($s, $key) !== false)
      {
	echo $s;
	$found=true;
      }
    }

  }
if(!$found)
echo "No results were found.";
}
?>

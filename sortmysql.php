<?php include("headmenu.php"); ?>

<h1 align=center> Sorted Contests</h1>
<font face="verdana">
<?php
//$sortbythis = $_REQUEST['sorbythis'];
//$order = $_REQUEST['order'];

// Make a MySQL Connection
include('common.php');
myconnect();
$type = array();
$t = 0;
$tsort = $_REQUEST['tsort'];
// Get all the data from the table
$count = 0;
if ($tsort != "comm"){
	$sql_query = "SELECT year FROM `yearlist` ";
	$result = mysql_query($sql_query ) or die ("cannot find yearlist");
	for($i = 1; $row = mysql_fetch_array($result); $i++) {
		$year[$count++] = $row['year'];
	} 
	for ($i = 0; $i < $count; $i++){
		$sql_query = "SELECT $tsort FROM `$year[$i]` ";
		$result = mysql_query($sql_query ) or die ("cannot find $year[$i]");
		for($j = 1; $row = mysql_fetch_array($result); $j++) {
			$temp = $row[''.$tsort.''];
			if (!in_array($temp,$type)){
			$type[$t++] = $temp;
		   }
		}
	}
	echo "<a name=top></a>";
	for ($j = 0; $j < $t; $j++){
		
		if ($tsort == "tout"){
			echo"<a href=#top>Top</a> <a href=#1>Graphics</a> <a href=#2>Numerical Calculations</a> <a href=#3>Optimizing</a> <a href=#4>Decoding</a> <a href=#5>Combinations</a> <a href=#6>Other</a>";
			switch ($type[$j]){
			case 1:
			echo "<a name=1><h1>Graphics</h1></a>";
				break;
			case 2:
			echo "<a name=2><h1>Numerical Calculations</h1></a>";
				break;
			case 3:
			echo "<a name=".$type[$j]."><h1>Optimizing</h1></a>";
				break;
			case 4:
			echo "<a name=".$type[$j]."><h1>Decoding</h1></a>";
				break;
			case 5:
			echo "<a name=".$type[$j]."><h1>Combinations</h1></a>";
				break;
			case 6:
			echo "<a name=".$type[$j]."><h1>Other</h1></a>";
				break;
			}
		}else if ($tsort == "lang"){
			if (strlen(trim($type[$j])) == 0)
				continue;
			echo "<a href=#top>Top</a> <a href=#Turing>Turing</a> <a href=#JavaScript>JavaScript</a> <a href=#Java>Java</a>";
			echo"<a name=".$type[$j]."><h1>$type[$j]</h1></a>";
		}else
			{
			if ($tsort == "diff"){	
				echo "Difficulty Level:";
			echo"<a href=#top>Top</a> <a href=#1>1</a> <a href=#2>2</a> <a href=#3>3</a> <a href=#4>4</a> <a href=#5>5</a>";
				echo"<h2>Difficulty Level:</h2>";}
			else{
			echo"<a href=#top>Top</a> <a href=#Boardwide>Boardwide</a> <a href=#Regionals>Regionals</a> <a href=#Finals>Finals</a>";
			}
					echo	"<a name=".$type[$j]."><h1>$type[$j] </h1></a>";
			if($tsort=="ctype"){
				}
		
		}
		for ($i = 0; $i < $count; $i++){
		$sql_query = "SELECT prob, title, pn, ctype FROM `$year[$i]` WHERE $tsort='$type[$j]'";
				$result = mysql_query($sql_query ) or die ("cannot find $year[$i]");
				for($k = 1; $row = mysql_fetch_array($result); $k++) {
				$temp=$row['title'];
				echo "<h2><a style = \"color:#000000\" href=\"viewdata.php?year=".$year[$i]."&y=".$row['ctype']."&x=".$temp."\">" . $temp . "</a></h2>";
				echo "Year: " . $year[$i] . "<br><br>";
				echo "<b>Problem</b><br> " . $row['prob'] . "<br><br>";
				echo "<hr>";
				}
		}
	}
}

?>
<?php include("comments.php"); ?>   
</table>
</font>
</body>
</html>














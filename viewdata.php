<?php include("headmenu.php"); ?>
<?php
	if(isset($_REQUEST['x']))
	{
		$x = $_REQUEST['x'];
	}
	if(isset($_REQUEST['y']))
	{
		$y = $_REQUEST['y'];
	}
	$year = $_REQUEST['year'];
	//$year=str_replace("'","\'",$year);
	$webname=$_REQUEST['webname'];
	$t = 0;
	$temp1 = array();
	include('common.php');
	myconnect();
	echo "<font face = \"verdana\">";

       echo '<h1 align=center>Year - '.$year.'</h1>';
	   echo '<table width=915px align=center>';
	   
	if (isset($year)){
		echo '<tr>';
		$sql_query = "SELECT * FROM `$year` ";
		$result = mysql_query($sql_query ) or die ("cannot find $year");
		for($i = 1; $row = mysql_fetch_array($result); $i++) {
			$temp = $row['ctype'];
			if (!in_array($temp,$temp1)){
			$temp1[$t++] = $temp;
			//echo "<td align=\"center\" bgcolor=\"#D7E6FC\"><h3><a style=\"color:#000000\" href=\"viewdata.php?y=$temp&year=$year&webname=$webname\">" . $temp . "</a></h3></td>";
		   }		 
		}
		if(in_array("Boardwide", $temp1 ))
			echo "<td align=\"center\" bgcolor=\"#D7E6FC\"><h3><a style=\"color:#000000\" href=\"viewdata.php?y=Boardwide&year=$year&webname=$webname\">Boardwide</a></h3></td>";
		if(in_array("Regionals", $temp1 ))
			echo "<td align=\"center\" bgcolor=\"#D7E6FC\"><h3><a style=\"color:#000000\" href=\"viewdata.php?y=Regionals&year=$year&webname=$webname\">Regionals</a></h3></td>";
		if(in_array("Finals", $temp1 ))
			echo "<td align=\"center\" bgcolor=\"#D7E6FC\"><h3><a style=\"color:#000000\" href=\"viewdata.php?y=Finals&year=$year&webname=$webname\">Finals</a></h3></td>";

		echo '</tr>';
		echo '</table>';
		if (isset($y)){
		echo '<table width=915px align=center>';
			echo '<tr>';
			$sql_query = "SELECT * FROM `$year` WHERE ctype='$y'";
			$result = mysql_query($sql_query ) or die ("cannot find $year");
			for($i = 1; $row = mysql_fetch_array($result); $i++) {
				$temp = $row['title'];
				$origtitle=$temp;
				$temp = str_replace(chr(150),"-",$temp);
				$temp = str_replace(chr(96),"'",$temp);
				$temp = str_replace(chr(145),"'",$temp);
				$temp = str_replace(chr(146),"'",$temp);
				$temp = str_replace(chr(147),"\"",$temp);
				$temp = str_replace(chr(148),"\"",$temp);
				$temp = str_replace(chr(133),"...",$temp);
				$temp = str_replace(chr(176)," degrees",$temp);
				$temp = str_replace(chr(186)," degrees",$temp);
				$temp = str_replace(chr(60),"<",$temp);
				$temp = str_replace(chr(62),">",$temp);
				mysql_query("UPDATE `$year` SET title='$temp' WHERE title='$origtitle'");
				echo "<td align=\"center\" bgcolor=\"#2C3363\"><a style=\"color:#FFFFFF\" href=\"viewdata.php?y=$y&x=$temp&year=$year&webname=$webname\">" . $temp . "</a></td>";
			}
			echo '</tr>';
			echo'</table>';
		
			if (isset($x)){
				$x = str_replace(chr(150),"-",$x);
			echo '<table align = "center" border = "5" cellpadding = "10" width=915px>';
				echo '<tr>';
				$x = str_replace("'","&#39;",$x);
				$sql_query = "SELECT * FROM `$year` WHERE title='$x'";
				$result = mysql_query($sql_query ) or die ("cannot find $year");
				$row = mysql_fetch_array($result);
				echo "<td bgcolor=\"#061B36\">";
				$datatitle = $row['title'];
				echo "<font color = \"#FFFFFF\"><h1 align = \"center\">" . $datatitle . "</h1></font>";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td bgcolor=\"#D9E1FA\">";
				echo "<h2>Contest Type:</b> " . $row['ctype'] . "</h2>";
				echo "<b>Problem Number:</b> " . $row['pn'];
				echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td>";
				//$temp = $row['prob'];
				$temp = str_replace(chr(96),"'",$row['prob']);
				$temp = str_replace(chr(145),"'",$temp);
				$temp = str_replace(chr(146),"'",$temp);
				$temp = str_replace(chr(147),"\"",$temp);
				$temp = str_replace(chr(148),"\"",$temp);
				$temp = str_replace(chr(133),"...",$temp);
				$temp = str_replace(chr(176)," degrees",$temp);
				$temp = str_replace(chr(186)," degrees",$temp);
				$temp = str_replace(chr(60),"<",$temp);
				$temp = str_replace(chr(62),">",$temp);

				
				
				echo "<b>Description</b><br><br> " . $temp;
				echo "<hr>";
				
				$temp = $row['solution'];
				if (strlen(trim($temp)) != 0){
					$temp3 = $row['lang'];
					
					echo "<b>Sample Solution in " . $temp3 . "</b><br><br>";
					echo "<font face = \"Courier New\" size =\"2\">".$temp. "</font><br><br>";
					
					if ($temp3 == "Turing"){

						echo"<h4>Translated to Java</h4>";
						include "transsolutions.php";
						echo "<pre>".$row['translated']."</pre>";

					}
				
				}else{
					echo "<b>No solution given</b><br><br>";
				}
				echo "<hr>";
				echo "</font>";
				$temp = $row['input'];
				if (strlen(trim($temp)) != 0){
				echo "<font face = \"verdana\">";
				echo "<b>Sample Input</b><br><font face = \"Courier New\" size =\"2\">" . $temp . "</font>";
				}else{
					echo "<b>No input given</b><br><br>";
				}
				echo"<hr>";
echo '<a name=commentstitle><h3> Comments </h3></a>';
	$sql_query = "SELECT * FROM `$year` WHERE title='$x'";
	$result = mysql_query($sql_query ) or die ("cannot find $year");
	$row = mysql_fetch_array($result);
	$temp = $row['comm'];
	echo $temp;
	if (isset($_POST['sub'])){
		$comment = $_REQUEST['comment']."<br><br><br>";
		echo $comment;
		$comment = $comment;
		$sql_qy = "UPDATE `$year` SET ";
		$sql_qy .= "comm = '$comment' ";
		$sql_qy .= "WHERE title = '$x' ";
		$result = mysql_query( $sql_qy ) or die("Update failed!!!");
	}	
	//echo '<form action = "'.$webname.'?year='.$year.'&x='.$x.'&y='.$y.'&webname='.$webname.'" method = "POST">';
	echo '<form action = "viewdata.php?year='.$year.'&x='.$x.'&y='.$y.'&webname='.$webname.'#commentstitle" method = "POST">';
	echo '<textarea name = "comment" rows="4" cols="50">Add in a comment</textarea>';
	echo '<input type= "submit" name = "sub" value="Submit"><input type= "reset" value="Reset">';
	echo '</form>';
echo '</td></tr>';
	
echo '</table>';
			}
		}
	}
?>


</td>
  </tr>
  <tr BGCOLOR=#FFFFFF ALIGN=CENTER>
  <td colspan=2>
  <BR><BR>
  &copy; Copyright Amy YU, Angel SHAO, Karen LU and Lucy WANG 2013
	<br>Site created on May 15th, 2013 //Last modified on June 3rd, 2013 
  </td>
  </TR>  
</font>
</table>

</body>
</html>

<html>
	<head>
		<title>Page Template</title>
		<?php
	echo'<meta http-equiv="Content-Style-Type" content="text/css">';
	echo'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>';
	echo'<script>';
	echo'$(document).ready(function() {';
	for ($i = 1; $i <= 7; $i++){
	  echo'$("div.choice_'.$i.'").hover(';
		//on mouseover
		echo'function() {';
		  echo'$(this).animate({';
			echo'height: \'+=250\''; //adds 250px
			echo'}, \'fast\''; //sets animation speed to fast
		  echo');';
		  
		echo'},';
		//on mouseout
		echo'function() {';
		  echo'$(this).animate({';
			echo'height: \'-=250px\''; //substracts 250px
			echo'}, \'fast\'';
		  echo');';
		echo'}';
	  echo');';
	}
	echo'});';
	
	echo'</script> ';
	echo'<style type="text/css">';
		
		for ($i = 1; $i <= 7; $i++){
			echo'div.tsc_mtable div.choice_'.$i.':hover {';
				echo'text-color:#000000;';
				echo'box-shadow: 5px 0px 30px rgba(0, 0, 0, 0.5);';
				echo'-webkit-box-shadow: 5px 0px 30px rgba(0, 0, 0, 0.5);';
				echo'-moz-box-shadow: 5px 0px 30px rgba(0, 0, 0, 0.5);';
				echo'margin-bottom: -30px;'; /// Note the added removal of the bottom margin that all "non" active elements have
				
				echo'}';
		}
		/*for ($i = 0; $i < 7;$i++){
		echo'div.pos_'.($i+1);
		echo'{';
		echo'position:relative;';
		echo'right:'.($i *-100).'px;';
		echo'}';
		}*/
		 
		
		echo'#box{';
			echo'width:150px;';
			echo'height:20px;';
			echo'overflow:hidden;';
			echo'padding-top:4px;';
			echo'border-width:2px;'; 
			echo'border-style:solid;';
			echo'border-color:#222 #222 #222 #222;';
			echo'margin:30px;';
			
			echo'box-shadow: 0px 0px 10px #fff;';
			echo'background-color:#000000';
		echo'}';
		
		echo'#box div{';
			echo'line-height:21px;';
			echo'margin:auto;';
			echo'font-family:verdana,arial,helvetica,sans-serif;';
			echo'color:#DDD;';
			//echo'text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;';
			echo'text-align:center;';
		echo'}';
		

		echo'#box div:nth-child(1){width:180px;}';
	echo'</style>';
echo'</head>';
echo'<body>';

	echo'<table width = "100%">';
		echo'<tr style ="background-color:black">';
		
			echo'<td colspan = "2"><h1 style="font-size:75;font-family:Segoe UI;color:white">&nbsp;&nbsp;1111</h1></td>';
		echo'</tr>';
		echo'<tr style ="background-color:#FFFFFF">';
			echo'<td colspan = 2 align = "right">';
			echo'</td>';
		echo'</tr>';
		echo'<tr>';
		echo'<div class="tsc_mtable tsc_pt3_style1">';
		
		for ($i = 0; $i < 7; $i++){
		echo'<td style ="background-color:black" width = 100 valign="top">';
			//if ($menu[$i][0] != $pg){
				echo'<div class = "pos_'.($i+1).'">';
				echo'<div id="box" class="choice_'.($i+1).'"onmouseover="this.style.backgroundColor=\'1111\'" onmouseout="this.style.backgroundColor=\'#000000\'">';
				echo'<div><font style="color:#FFFFFF;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black">2222</font></a></div>';
				echo'<div><br><hr noshade color="black"><font style="color:black">1111</font></a></div>';
				//echo'<div><br><hr noshade color="black"><font style="color:black">'.$menu[$i][2].'</font></div>';
				
				echo'</div>';
				echo'</div>';
				echo'</font>';
				//echo'<th bgcolor = "'.$top[count($top)-1].'">'.$line.'</th>';
			//}
			//else
			//$subtop = $menu[$i];
			echo'</td>';
		}
		
		echo'</div>';
		
			//echo'<th colspan="6" align = "center">'.$pg.'</th>';
		echo'</tr>';
	echo'</table>';
	?>
	
	</body>
</html>
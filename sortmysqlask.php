<?php include("headmenu.php"); ?>

<h1 align=center> Sort Through the Contests</h1>
<table>
<tr><td>

<?php include("search.php"); ?>
</td>
</tr>

</table>
<form method="GET" action="sortmysql.php">



<br>

<table>




  <!--<tr>
	<td>Sort data by:</td>
	<td><input type="text" name="sortbythis" value="" size="5"> </td>
  </tr>

  <tr>
	<td>Order data by:</td>
	<td><input type="text" name="order" value="" size="5"> </td>
  </tr>*-->
  
  <tr>
	<td>How would you like to sort the contests?</td>
	<td><select name = "tsort" id = "tsort">
		  <option value="ctype" selected>Type of Contests</option>
		  <option value="tout">Type of Output</option>
		  <option value="diff">Difficulty</option>
		  <option value="lang">Language</option>
		</select> 
	</td>
<td></td>
	<td><input type="submit" value="Enter!"></td>
  </tr>


</table>



<?php include("comments.php"); ?>   
</table>

</body>
</html>

    
    
</form>
</body>
</html>

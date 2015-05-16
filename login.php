<?php include("headmenu.php"); ?>


<h1 align=center>Login</h1>
<form name="login" method="post" action="loginx.php">
<table width="225" align="center">
                    <td width="71"><span style="font-size:10pt;">Username:</span></td>
                    <td width="139"><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td width="71"><span style="font-size:10pt;">Password:</span></td>
                    <td width="139"><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td width="71">&nbsp;</td>
                        <td width="139">
                            <p align="right"><input type="submit" name="submit" value="Submit"></p>
                        </td>
                </tr>
    <tr>
        <td colspan=2>Not Registered?<a href="register.php" target="_self"> Register Now!</font></i></td>
    </tr>
</table>
</form>

</td>
<?php include("comments.php"); ?>   

</table>

</body>
</html>

<?php include("headmenu.php"); ?>


<h1 align=center>Register</h1>
<form action="registerx.php" method="post">
<table border="0" width="225" align="center">
    <tr>
                        <td width="116"><span style="font-size:10pt;">Name:</span></td>
                        <td width="156"><input type="text" name="name" maxlength="100"></td>
                    </tr>
                    <tr>
                        <td width="116"><span style="font-size:10pt;">Email:</span></td>
                        <td width="156"><input type="text" name="email" maxlength="100"></td>
                    </tr>
                <tr>
                    <td width="116"><span style="font-size:10pt;">Username:</span></td>
                    <td width="156"><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td width="116"><span style="font-size:10pt;">Password:</span></td>
                    <td width="156"><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td width="116">&nbsp;</td>
                        <td width="156">
                            <p align="right"><input type="submit" name="submit" value="Submit"></p>
                        </td>
                </tr>
</table>
</form>

</td>
<?php include("comments.php"); ?>   

</table>

</body>
</html>

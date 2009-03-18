<?php
require_once("util/dba.php");
require_once("util/user.php");

session_start();
$dba    = new dba();
$user   = new user( $dba );

if( !$user_name ) $user_name = $_POST["user_name"];
if( !$user_password ) $user_password = $_POST["user_password"];

if( trim( $user_name ) && trim( $user_password ) )
{
        $user->log( trim( $user_name ), trim( $user_password ) );
        if( $user->isLogged() ) die("<script>top.document.location.href='index.php';</script>");
        else $wrong_str = "Wrong user name or password";

}
else
{
        $user->logoff();
        session_destroy();
}
?>
<html>
	<head>
		<title>Log in</title>
        <link rel="stylesheet" href="style/style.css" type="text/css">
	</head>
	<body class="content_body">
        <form name="my_form" action="<?=$PHP_SELF; ?>" method="post">
        <table cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td><img src="graphics/transp.gif"></td>
                <td><img src="graphics/horisontal_button/left_selected.gif"></td>
                <td class="faneblad_selected">Log in</td>
                <td><img src="graphics/horisontal_button/right_selected.gif"></td>
            </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content">
                    <table class="color1" width="100%" cellpadding="0" cellspacing="0" border="0">
												<tr>
                           <td colspan="2"> <img src="graphics/transp.gif" border="0" width="300" height="15"> </td>
                        </tr>
                        <tr>
                            <td width="100" class="tdText">Name</td>
                            <td> <input type="text" name="user_name" class="input"></td>
                        </tr>
                        <tr>
                            <td width="100" class="tdText">Password</td>
                            <td> <input type="password" name="user_password" class="input"> </td>
                        </tr>
                        <tr>
                            <td colspan="2"> <img src="graphics/transp.gif" border="0" width="300" height="15"> </td>
                        </tr>
                    </table>
					<br>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
									<td width="200"><img src="graphics/transp.gif" border="0" width="200" height="15"></td>
									<td width="210" align="right"><input type="submit" value="Log in" class="knap"></td>
							</tr>
						</table>
				</td>
            </tr>
        </table>
		</form>
	</body>
</html>



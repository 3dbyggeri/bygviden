<?php 
    /*********************************************************************/
    /*   frameset2.php                                                   */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   Inner frameset for application                                  */
    /*                                                                   */
    /*********************************************************************/

    require_once("util/dba.php");
    require_once("util/user.php");
    session_start();
    $user = new user( new dba() );
    
    if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Wizi InnerFrameset</title>
</head>
<!-- Wizi InnerFrameset -->
<frameset rows="23,*,23" framespacing="0" frameborder="0">
	<frame name="topfrm" src="nav.php" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
	<frame name="contentfrm" src="home/index.php" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0" noresize>
	<frame name="bottomfrm" src="bottom.php?copyright=1" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
</frameset>
<!-- NoFames HTML -->
<noframes>
<body>
	<H1>Vizion Factory NewMedia ApS - Wizi</H1>
	<p>Wizi kræver en browser som understøtter framesets.</p>
	<p>Denne version kræver at benytter enten Internet Explorer 5.5 eller højere, Mozilla 1.0 eller Netscape 7.0</p>
</body>
</noframes>
</html>

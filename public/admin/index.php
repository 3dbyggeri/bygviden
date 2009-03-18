<?php 
    /*********************************************************************/
    /*   index.php                                                       */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   main frameset for the aplication                                */
    /*                                                                   */
    /*********************************************************************/

require_once("util/dba.php");
require_once("util/user.php");
session_start();
$user = new user( new dba() );

if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");
if( !$treeView ) $treeView = $_GET["treeView"];
if (!$treeView) $treeView = 'normal';

switch( $treeView )
{
	case "minimize" : $frameSize = array( 0,17,'*' ); break;
	case "normal" : $frameSize = array( 200,17,'*' ); break;
	case "maximize" : $frameSize = array( '*',17,0 ); break;
	default:$frameSize = array( 200,17,'*' ); break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Bygviden :: Administration</title>
	<script language="JavaScript" src="scripts/treemenu_frame.js"></script>
	<script language="JavaScript" src="scripts/global_funcs.js"></script>
	<script language="JavaScript">
		var currentState = '<?=$treeView?>';
	</script>
</head>
<!-- Wizi frames -->
<frameset cols="200,*" framespacing="0" frameborder="1">
	<frame src="about:blank" name="treefrm" id="treefrm" frameborder="10" scrolling="Auto" marginwidth="0" marginheight="0">
	<frame name="mainfrm" src="frameset2.php" marginwidth="0" style="border-left:4px solid #666666" marginheight="0" scrolling="auto" frameborder="10">
</frameset>
<!-- Wizi Noframes -->
<noframes>
<body>
	<H1>Vizion Factory NewMedia ApS - Wizi</H1>
	<p>Wizi kræver en browser som understøtter framesets.</p>
	<p>Denne version kræver at du benytter enten Internet Explorer 5.5 eller højere, Mozilla 1.0 eller Netscape 7.0</p>
</body>
</noframes>
</html>

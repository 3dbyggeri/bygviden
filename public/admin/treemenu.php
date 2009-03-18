<?php 
    /*********************************************************************/
    /*   treemenu.php                                                    */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   MenuPane for open / close sitetree                              */
    /*                                                                   */
    /*********************************************************************/

    require_once("util/dba.php");
    require_once("util/user.php");
    session_start();
    $user = new user( new dba() );
    
    if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");
	
	switch( $treeView )
	{
		case "minimize" : $treeView = 'minimize'; break;
		case "normal"   : $treeView = 'normal'; break;
		case "maximize" : $treeView = 'maximize'; break;
		default : $treeView = 'minimize'; break;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Wizi Treemenus</title>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#9D999C">
<table width="17" height="100%" cellspacing="0" cellpadding="0" align="left" bgcolor="#9D999C">
	<tr>
		<td valign="top" align="left">
			<table width="17" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="graphics/transp.gif" height="25" width="2" border="0"/></td>
					<td><img src="graphics/transp.gif" height="25" width="13" border="0"/></td>
					<td><img src="graphics/transp.gif" height="25" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
					<td><img src="graphics/treemenu_images/prikker.gif" height="1" width="13"/></td>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="17" width="2" border="0"/></td>
					<td><a href="javascript:parent.resize('minimize')" onFocus="if(this.blur)this.blur();"><img src="graphics/treemenu_images/minimize_<?if($treeView != 'minimize') {echo 'off';}else{echo 'on';}?>.gif" height="13" width="13" alt="Minimize" name="minimize" border="0"/></a></td>
					<td><img src="graphics/transp.gif" height="17" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="17" width="2" border="0"/></td>
					<td><a href="javascript:parent.resize('normal')" onFocus="if(this.blur)this.blur();"><img src="graphics/treemenu_images/normal_<?if($treeView != 'normal') {echo 'off';}else{echo 'on';}?>.gif" height="13" width="13" alt="Normal" name="normal" border="0"/></a></td>
					<td><img src="graphics/transp.gif" height="17" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="17" width="2" border="0"/></td>
					<td><a href="javascript:parent.resize('maximize')" onFocus="if(this.blur)this.blur();"><img src="graphics/treemenu_images/maximize_<?if($treeView != 'maximize') {echo 'off';}else{echo 'on';}?>.gif" height="13" width="13" alt="Maximize" name="maximize" border="0"/></a></td>
					<td><img src="graphics/transp.gif" height="17" width="2" border="0"/></td>
				</tr>
        <!--
				<tr>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
					<td><img src="graphics/treemenu_images/prikker.gif" height="1" width="13"/></td>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="2" width="2" border="0"/></td>
					<td><a href="javascript:parent.changeTree('documents')" onFocus="if(this.blur)this.blur();"><img src="graphics/treemenu_images/documents_off.gif" name="documents" width="13" border="0"/></a></td>
					<td><img src="graphics/transp.gif" height="2" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
					<td><img src="graphics/treemenu_images/prikker.gif" height="1" width="13"/></td>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="2" width="2" border="0"/></td>
					<td><a href="javascript:parent.changeTree('media')" onFocus="if(this.blur)this.blur();"><img src="graphics/treemenu_images/media_off.gif" name="media" width="13" border="0"/></a></td>
					<td><img src="graphics/transp.gif" height="2" width="2" border="0"/></td>
				</tr>
				<tr>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
					<td><img src="graphics/treemenu_images/prikker.gif" height="1" width="13"/></td>
					<td><img src="graphics/transp.gif" height="20" width="2" border="0"/></td>
				</tr>
        -->
			</table>
		</td>
	</tr>
</table>
</body>
</html>			

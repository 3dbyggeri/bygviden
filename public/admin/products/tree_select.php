<?php 
require("../util/dba.php");
include_once( "../util/tree.php" );
include_once( "../util/varegruppeTree.php" );

session_start();
$dba  = new dba();

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$toggle ) $toggle = $_POST["toggle"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];


$root = 133;

$tree = new varegruppeTree( $dba, session_id(),'varegrupper');
$tree->toggle( $toggle );
?>
<html>
    <head>
        <title>Varegrupper</title>
        <link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
        <script language="javascript" src="../scripts/global_funcs.js"></script>
        <script>
          function selecting(id,name)
          {
            if(opener) opener.selectedVaregruppe(id,name);
            window.close();
          }
        </script>
    </head>
    <body bgcolor="#FFFFFF" style="margin:0px;padding:0px"> 
    <table width="100%" cellpadding="4" cellspacing="0" border="0">
      <tr>
        <td bgcolor="#CC6600" align="right" valign="middle"><img src="graphics/space.gif" width="10" height="23"></td>
        <td bgcolor="#CC6600" align="right" valign="middle">
          <a href="javascript:window.close()" 
            style="font-family:verdana,sans-serif;text-decoration:none;font-size:12px;font-weight:900;color:#ffffff">[x]</a>

        </td>
     </tr>
    </table>

		<form type="submit" name="tree" action="<?=$PHP_SELF?>" method="POST">
			<input type="hidden" name="toggle">
			<?php
				$nodes =  $tree->getNodeArray();
				$n = count( $nodes );
			?>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td
				</tr>
			</table>
			<?for( $i = 0; $i < $n; $i++ ):?>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<?//==================NODE TABLE CELL============================?>
					<td align="left">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<?//==================SPACER CELL============================?>
								<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
								<?//==================DISCLOSURE TRIANGLE CELL============================?>
								<td valign="top">
									<a href="javascript:toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
								</td>
								<?//==================NODE ICON CELL============================?>
								<td valign="top">
                  <span class="nodeName"><img src="graphics/doc.gif" border="0"/></span>
								</td>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">

                <!--only leaf should be selectable-->
                <?if( $nodes[$i]["node"] ):?>
                  <span style="font-weight:900;font-family:verdana,sans-serif;font-size:12px"><?=$nodes[$i]['name'] ?></span>
                <?else:?>
                  <a href="javascript:selecting(<?=$nodes[$i]['id']?>,'<?=$nodes[$i]['name']?>');" 
                    class="nodeName" 
                    onfocus="if(this.blur)this.blur();"
                    title="Klik for at v¾lge"><?=$nodes[$i]['name'] ?></a>
                <?endif?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?endfor?>
		</form>
    </body>
</html>


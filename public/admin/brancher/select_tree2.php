<?php
session_start();
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/buildingTree.php" );
include_once( "../util/user.php" );

$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$tree = new buildingTree( $dba, session_id(), 'buildingelements' );

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$toggle ) $toggle = $_REQUEST["toggle"];
if( !$add ) $add = $_GET["add"];
if( !$type ) $type = $_GET["type"];
if( !$type ) $type = $_POST["type"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if($_REQUEST['toggle'])
{
    echo '<!--posting...'.$_POST['toggle'].'-->';
}
$tree->toggle( $toggle );
?>
<html>
    <head>
        <title>Tree</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
        <script language="javascript" src="../scripts/global_funcs.js"></script>
    </head>
    <body bgcolor="#FFFFFF">
<script language="javascript">
  function selectNode( id, name )
  {
    top.mainfrm.contentfrm.nodeSelected(id);
  }

</script>
<style>
    .nodeName { color:#000; }
</style>

<form name="tree" action="select_tree2.php" method="post">
<input type="hidden" name="toggle_login" value="0">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="branche" value="<?=$branche?>">
<input type="hidden" name="elementid" value="<?=$props['element_id']?>">
<input type="hidden" name="elementname" value="<?=$props['element_name']?>">
<input type="hidden" name="saving">
			<input type="hidden" name="toggle">
			<?php
				$nodes =  $tree->getNodeArray();
				$n = count( $nodes );
			?>
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
									<?
                                          $iconPostFix='';
                                          $alt='';
                        
                                        if( $nodes[$i]['id'] == $root ) $icon = 'doc';
                                        else $icon = 'doc';
                                      ?>
									<?if( $nodes[$i]['id'] != $root  ):?>
                                        <a href="javascript:selectNode(<?=$nodes[$i]["id"]?>,'<?=$nodes[$i]['name']?>')" 
                                            title="Valgt bygningsdel" class="nodeName" 
                                            onfocus="if(this.blur)this.blur();"><img src="graphics/<?=$icon?>.gif" alt="Edit" border="0"/></a>
									<?endif?>
								</td>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">
                                    <?if( $nodes[$i]['id'] == $root ):?>
                                        <span class="nodeName" style="color:#000;padding-bottom:3px"><?=$nodes[$i]["name"] ?></span>
                                    <?else:?>
                                        <a href="javascript:selectNode(<?=$nodes[$i]["id"]?>,'<?=$nodes[$i]['name']?>')" 
                                           title="Valgt bygningsdel"
                                           class="nodeName" 
                                           onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
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

<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/buildingTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
//if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$tree = new buildingTree( $dba, session_id(), 'buildingelements' );
$tree->fullOpen = true;

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$newNodeName ) $newNodeName = $_POST["newNodeName"];
if( !$movingNode ) $movingNode = $_POST["movingNode"];
if( !$toggle ) $toggle = $_POST["toggle"];
if( !$add ) $add = $_GET["add"];
if( !$remove ) $remove = $_GET["remove"];
if( !$remove ) $remove = $_POST["remove"];
if( !$duplicate ) $duplicate = $_GET["duplicate"];
if( !$move ) $move = $_GET["move"];
if( !$where ) $where = $_GET["where"];
if( !$rename ) $rename = $_POST["rename"];
if( !$newname ) $newname = $_POST["newname"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];

$tree->toggle( $toggle );
$tree->add( $add );
$tree->remove( $remove );
$tree->duplicate( $duplicate );
$move   = $tree->move( $move, $where );
$rename = $tree->rename( $rename, $newname );
?>
<html>
	<head>
		<title>Site tree</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
		<script language="javascript" src="../scripts/global_funcs.js"></script>
    <script language="javascript" src="../scripts/scroll_master.js"></script>
	</head>
  <body bgcolor="#FFFFFF" onload="restoreScrollPosition('<?=urlencode($_SERVER['PHP_SELF'] )?>')"  onunload="saveScrollPosition( '<?=urlencode($_SERVER['PHP_SELF'])?>')">
		<form type="submit" name="tree" action="<?=$PHP_SELF?>" method="POST">
			<input type="hidden" name="toggle">
			<input type="hidden" name="remove">
			<input type="hidden" name="rename" value="<?=$rename?>">
			<input type="hidden" name="move" value="<?=$move?>">
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
					<td align="left" onmouseover="shownode( <?=$nodes[$i]["id"]?> )"  onmouseout="hidenode( <?=$nodes[$i]["id"]?> )">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<?//==================SPACER CELL============================?>
								<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
								<?//==================DISCLOSURE TRIANGLE CELL============================?>
								<td valign="top">
									<a href="javascript:toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\></a>
								</td>
								<?//==================NODE ICON CELL============================?>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">
									<?if( $nodes[$i]["id"] == $rename ):?>
										<input type="text" size="14" class="textfield" name="newname" value="<?=$nodes[$i]["name"] ?>"/>
										<script language="javascript">
											document.tree.newname.focus();
										</script>
									<?elseif( $nodes[$i]["moving"] ):?>
										<a href="<?=$PHP_SELF?>" class="nodeName_gray" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?elseif( $move ):?>
										<a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?else:?>
											<a href="javascript:renaming(<?=$nodes[$i]["id"]?>);" class="<?=( !$iconPostFix || !stristr('_gray',$iconPostFix) )?"nodeName":"nodeName_gray"?>" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?endif?>
								</td>
							</tr>
						</table>
					</td>
					<?//==================ACTIONS TABLE CELL============================?>
					<td onmouseover="shownode( <?=$nodes[$i]["id"]?> )"  onmouseout="hidenode( <?=$nodes[$i]["id"]?> )">
						<table border="0" cellpadding="0" cellspacing="0" class="hide" id="<?=$nodes[$i]["id"]?>">
							<tr>
								<td>&nbsp;&nbsp;</td>
								<?if( !$move ):?>
								<td <?=( $nodes[$i]["id"] == $root )?"colspan=\"3\"":""?>>
										<a href="<?=$PHP_SELF?>?add=<?=$nodes[$i]["id"]?>" onmouseover="lightup('add', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('add', <?=$nodes[$i]["id"]?>)" title="Add" onfocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" name="add<?=$nodes[$i]["id"]?>" border="0" alt="Add"></a>
								</td>
								<?if( $nodes[$i]["id"] != $root ):?>
								<td>
										<a href="<?=$PHP_SELF?>?duplicate=<?=$nodes[$i]["id"]?>" onmouseover="lightup('duplicate', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('duplicate', <?=$nodes[$i]["id"]?>)" title="Duplicate" onfocus="if(this.blur)this.blur();"><img src="graphics/duplicate_off.gif" name="duplicate<?=$nodes[$i]["id"]?>" border="0" alt="Duplicate"></a>
								</td>
								<td>
										<a href="javascript:removing( <?=$nodes[$i]["id"]?> )" onmouseover="lightup('delete', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('delete', <?=$nodes[$i]["id"]?>)" onfocus="if(this.blur)this.blur();"><img src="graphics/delete_off.gif" name="delete<?=$nodes[$i]["id"]?>" border="0" alt="Delete"></a>
								</td>
								<td>
										<a href="<?=$PHP_SELF?>?move=<?=$nodes[$i]["id"]?>" onmouseover="lightup('move', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('move', <?=$nodes[$i]["id"]?>)" title="Move" onfocus="if(this.blur)this.blur();"><img src="graphics/move_off.gif" name="move<?=$nodes[$i]["id"]?>" border="0" alt="Move"></a>
								</td>
								<?endif?>
								<?endif?>
								<td>&nbsp;&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<?endfor?>
		</form>
	</body>
</html>

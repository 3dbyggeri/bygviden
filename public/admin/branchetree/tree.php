<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/brancheTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$branche = $_GET['branche'];
if( !$branche ) $branche = $_POST['branche'];
if( !$branche ) $branche = 'general';

$tree = new brancheTree( $dba, session_id(), $branche );

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
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];

$tree->toggle( $toggle );
$tree->add( $add );
$tree->remove( $remove );
$tree->duplicate( $duplicate );
$move   = $tree->move( $move, $where );

$brancher = $tree->getBrancher();
?>
<html>
	<head>
		<title>Site tree</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
		<script language="javascript" src="../scripts/global_funcs.js"></script>
    <script language="javascript" src="../scripts/scroll_master.js"></script>
        <style>
            .nodeName { color:#000; }
        </style>
	</head>
  <body bgcolor="#FFFFFF" onload="restoreScrollPosition('<?=urlencode($_SERVER['PHP_SELF'] )?>')"  onunload="saveScrollPosition( '<?=urlencode($_SERVER['PHP_SELF'])?>')">
		<form type="submit" name="tree" action="<?=$PHP_SELF?>" method="POST">
			<input type="hidden" name="toggle">
			<input type="hidden" name="remove">
			<input type="hidden" name="rename" value="<?=$rename?>">
			<input type="hidden" name="move" value="<?=$move?>">
      <input type="hidden" name="branche" value="<?=$branche?>">
			<?php
				$nodes =  $tree->getNodeArray();
				$n = count( $nodes );
			?>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>
                        <select name="branche" onchange="document.tree.submit()" style="width:180px">
                          <?for( $i = 0; $i < count( $brancher ); $i++ ):?>
                            <option value="<?=$brancher[$i]['name']?>" <?=( $brancher[$i]['name'] == $branche )?'selected':''?>><?=$brancher[$i]['label']?></option> 
                          <?endfor?>
                        </select>
                    </td>
					<td><a href="print.php?branche=<?=$branche?>" target="_blank"><img border="0" src="print.gif" style="margin:2px"></a></td>
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
								<td valign="top">
									<?
	                  $iconPostFix='';
	                  $alt='';
	
	                  if( $filter[ $nodes[$i]["id"] ]["state"] )
	                  {
	                    if( $selected_overview != 'docs' ) $iconPostFix.="_gray";
	                  }
	                  else
	                  {
	                    if( $selected_overview == 'docs' ) $iconPostFix.="_gray";
	                  }
	                  
	                  if( $filter[ $nodes[$i]["id"] ]["unpublish"] )
	                  {
	                    $iconPostFix.="_unpub";
	                    $alt = " Unpublish scheduled to: ".$filter[ $nodes[$i]["id"]]["unpublish"];
	                  }
	
	                  if( $filter[ $nodes[$i]["id"] ]["publish"] )
	                  {
	                    $iconPostFix.="_pub";
	                    $alt.= " Publish scheduled to:". $filter[ $nodes[$i]["id"]]["publish"];
	                  }
                  ?>
									
									<?if( $nodes[$i]["moving"] ):?>
										<a href="<?=$PHP_SELF?>" class="nodeName_gray" onfocus="if(this.blur)this.blur();"><img src="graphics/doc_gray.gif" alt="Edit" border="0"/></a>
									<?elseif( $move ):?>
										<a href="<?=$PHP_SELF?>?branche=<?=$branche?>&move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onfocus="if(this.blur)this.blur();"><img src="graphics/doc.gif" alt="Edit" border="0"/></a>
									<?elseif( $nodes[$i]['id'] != 1 ):?>
										<a href="../brancher/index.php?id=<?=$nodes[$i]["id"]?>&branche=<?=$branche?>" title="Edit" class="nodeName" onclick="parent.resize('normal');" target="contentfrm" onfocus="if(this.blur)this.blur();"><img src="graphics/doc<?=$iconPostFix?>.gif" alt="Edit" border="0"/></a>
                  <?else:?>
                    <img src="graphics/doc<?=$iconPostFix?>.gif" alt="Edit" border="0"/>
									<?endif?>
								</td>
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
										<a href="<?=$PHP_SELF?>?branche=<?=$branche?>&move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?else:?>
											<span class="nodeName"><?=$nodes[$i]["name"] ?></span>
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
										<a href="<?=$PHP_SELF?>?add=<?=$nodes[$i]["id"]?>&branche=<?=$branche?>" onmouseover="lightup('add', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('add', <?=$nodes[$i]["id"]?>)" title="Add" onfocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" name="add<?=$nodes[$i]["id"]?>" border="0" alt="Add"></a>
								</td>
								<?if( $nodes[$i]["id"] != $root ):?>
								<td>
										<a href="<?=$PHP_SELF?>?branche=<?=$branche?>&duplicate=<?=$nodes[$i]["id"]?>" onmouseover="lightup('duplicate', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('duplicate', <?=$nodes[$i]["id"]?>)" title="Duplicate" onfocus="if(this.blur)this.blur();"><img src="graphics/duplicate_off.gif" name="duplicate<?=$nodes[$i]["id"]?>" border="0" alt="Duplicate"></a>
								</td>
								<td>
										<a href="javascript:removing( <?=$nodes[$i]["id"]?> )" onmouseover="lightup('delete', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('delete', <?=$nodes[$i]["id"]?>)" onfocus="if(this.blur)this.blur();"><img src="graphics/delete_off.gif" name="delete<?=$nodes[$i]["id"]?>" border="0" alt="Delete"></a>
								</td>
								<td>
										<a href="<?=$PHP_SELF?>?branche=<?=$branche?>&move=<?=$nodes[$i]["id"]?>" onmouseover="lightup('move', <?=$nodes[$i]["id"]?>)" onmouseout="grayout('move', <?=$nodes[$i]["id"]?>)" title="Move" onfocus="if(this.blur)this.blur();"><img src="graphics/move_off.gif" name="move<?=$nodes[$i]["id"]?>" border="0" alt="Move"></a>
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

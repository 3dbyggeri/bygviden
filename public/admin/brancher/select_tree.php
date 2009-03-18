<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/buildingTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$tree = new buildingTree( $dba, session_id(), 'buildingelements' );

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$toggle ) $toggle = $_POST["toggle"];
if( !$add ) $add = $_GET["add"];
if( !$type ) $type = $_GET["type"];
if( !$type ) $type = $_POST["type"];
if( !$PHP_SELF ) $PHP_SELF = $_SERVER["PHP_SELF"];

$tree->toggle( $toggle );
?>
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
                    if( $nodes[$i]['id'] == $root ) $icon = 'doc';
                    else $icon = 'doc';
                  ?>
									<?if( $nodes[$i]["moving"]  ):?>
                      <a href="<?=$PHP_SELF?>" class="nodeName_gray" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=$icon?>_gray.gif" alt="Edit" border="0"/></a>
									<?elseif( $move ):?>
                      <a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=$icon?>.gif" alt="Edit" border="0"/></a>
									<?elseif( $nodes[$i]['id'] != $root  ):?>
                        <a href="javascript:selectNode(<?=$nodes[$i]["id"]?>,'<?=$nodes[$i]['name']?>')" title="Edit" class="nodeName" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=$icon?><?=$iconPostFix?>.gif" alt="Edit" border="0"/></a>
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
										<a href="<?=$PHP_SELF?>?move=<?=$move?>&where=<?=$nodes[$i]["id"]?>" class="nodeName" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									<?else:?>
                    <?if( $nodes[$i]['id'] == $root ):?>
											<span class="nodeName" style="color:#000000"><?=$nodes[$i]["name"] ?></span>
										<?else:?>
											<a href="javascript:selectNode(<?=$nodes[$i]["id"]?>,'<?=$nodes[$i]['name']?>')" class="<?=( !$iconPostFix || !stristr('_gray',$iconPostFix) )?"nodeName":"nodeName_gray"?>" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
										<?endif?>
									<?endif?>
								</td>
							</tr>
						</table>
					</td>
					<?//==================ACTIONS TABLE CELL============================?>
				</tr>
			</table>
			<?endfor?>

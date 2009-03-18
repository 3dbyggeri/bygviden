<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/agentTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$tree = new agentTree( $dba, session_id(), 'agentstyring' );

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
<html>
	<head>
		<title>Kildestyring</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
		<script language="javascript" src="../scripts/global_funcs.js"></script>
    <script language="javascript">
      function selectNode( id )
      {
        opener.agentSelected( id );
        window.close();
      }
    </script>
	</head>
	<body bgcolor="#FFFFFF">
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
                        <a href="javascript:selectNode(<?=$nodes[$i]["id"]?>)" title="Edit" class="nodeName" onfocus="if(this.blur)this.blur();"><img src="graphics/<?=$icon?><?=$iconPostFix?>.gif" alt="Edit" border="0"/></a>
									<?endif?>
								</td>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">
											<a href="javascript:selectNode(<?=$nodes[$i]["id"]?>)" class="<?=( !$iconPostFix || !stristr('_gray',$iconPostFix) )?"nodeName":"nodeName_gray"?>" onfocus="if(this.blur)this.blur();"><?=$nodes[$i]["name"] ?></a>
									
								</td>
							</tr>
						</table>
					</td>
					<?//==================ACTIONS TABLE CELL============================?>
				</tr>
			</table>
			<?endfor?>
		</form>
	</body>
</html>

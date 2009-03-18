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
			<?php
				$nodes =  $tree->getNodeArray();
				$n = count( $nodes );
			?>
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
									<img src="graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\>
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
                                      ?>
									
                                    <img src="graphics/doc<?=$iconPostFix?>.gif" border="0"/>
								</td>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">
                                    <span class="nodeName"><?=$nodes[$i]["name"] ?></span>
								</td>
							</tr>
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<?endfor?>
	</body>
</html>

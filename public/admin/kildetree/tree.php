<?php
include_once( "../util/dba.php" );
include_once( "../util/tree.php" );
include_once( "../util/kildeTree.php" );
include_once( "../util/user.php" );

session_start();
$dba = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='log.php';</script>");

$root = 1;
$tree = new kildetree( $dba, session_id(), 'kildestyring' );

if( $_GET['sort'] ) $_SESSION['sort_order'] = $_GET['sort'];

//get parameters
if( !$id ) $id = $_POST["id"];
if( !$action ) $action = $_POST["action"];
if( !$newNodeName ) $newNodeName = $_POST["newNodeName"];
if( !$movingNode ) $movingNode = $_POST["movingNode"];
if( !$add ) $add = $_GET["add"];
if( !$type ) $type = $_GET["type"];
if( !$type ) $type = $_POST["type"];
if( !$remove ) $remove = $_GET["remove"];
if( !$remove ) $remove = $_POST["remove"];
if( !$duplicate ) $duplicate = $_GET["duplicate"];
if( !$move ) $move = $_GET["move"];
if( !$where ) $where = $_GET["where"];
if( !$rename ) $rename = $_POST["rename"];
if( !$newname ) $newname = $_POST["newname"];
if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];

$tree->toggle( $_POST['toggle'] );
$tree->add( $add, $type );
if( $add ) unset( $type );
$tree->addFolder( $_GET['addfolder'] );
if( $_GET['addfolder'] ) unset( $type );
$tree->remove( $remove );
$tree->duplicate( $duplicate );
$move   = $tree->move( $move, $where );
$rename = $tree->rename( $rename, $newname );
?>
<html>
	<head>
		<title>Kildestyring</title>
		<link href="../style/style.css" rel="stylesheet" rev="stylesheet" type="text/css"/>
		<script language="javascript" src="../scripts/global_funcs.js"></script>
    <script language="javascript" src="../scripts/scroll_master.js"></script>
	</head>
    <body style="margin-top:0px" bgcolor="#FFFFFF" onunload="saveScrollPosition( '<?=urlencode($_SERVER['PHP_SELF'])?>')">
		<form type="submit" name="tree" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
			<input type="hidden" name="toggle">
			<input type="hidden" name="remove">
			<input type="hidden" name="rename" value="<?=$rename?>">
			<input type="hidden" name="move" value="<?=$move?>">
      <input type="hidden" name="type" value="<?=$type?>">
      <select name="sorting" style="width:200px" 
        onchange="document.location.href='<?=$_SERVER['PHP_SELF']?>?sort='+ this.options[this.selectedIndex].value">
        <option value="normal">Normal</option>
        <option value="alfabetical" <?=( $_SESSION['sort_order'] == 'alfabetical')?'selected':''?>>Alfabetisk</option>
      </select>
			<?php
				$nodes =  $tree->getNodeArray();
				$n = count( $nodes );
			?>

      <?
        function approvedMove( $from, $to, $to_state )
        {
          //from is the element to be moved
          //to is the desired destination
          if( $from == 'folder' )
          {
            //folder kan only be move at the top level or inside other folder
            //the state of the folder doesn't mind
            return ( $to == 'root' || $to == 'folder' )? true:false;
          }
          if( $from == 'leverandor' )
          {
            //leverandor can be moved to root,
            //moved under or after a folder
            return ( $to == 'root' || $to == 'folder' )? true:false;
          }
          if( $from == 'kategori' )
          {
            //categories can only be moved under an open leverandor
            //and a closed categorie
            if( $to == 'leverandor' && $to_state ) return true;
            if( $to == 'kategori' && !$to_state ) return true;
            return false;
          }
          if( $from == 'publikation' )
          {
            //publications kan only be moved under an open kategori
            //or besides a closed publikation
            if( $to == 'kategori' && $to_state ) return true;
            if( $to == 'publikation' && !$to_state ) return true;
            return false;
          }
          return true;
        }
      ?>
			<?for( $i = 0; $i < $n; $i++ ):?>
        <?
          $node = $nodes[$i];
          $level = $node['level'] * 10;
          $toggleState = ( $node['open'] )? 'down':'up';
          $toggleState.= ( $node['node'] )? 'node':'leaf';
          $icon = ( $node['id'] == $root )? 'doc':$node['type'];
          $isGrayIcon = ( ( $type && $tree->types[ $node['type'] ] != $type ) || $node["moving"]  )?1:0;
          if($node['type'] == 'kategori' && $node['indholdsfortegnelse'] =='y') $icon = 'samletpub';
        ?>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<?//==================NODE TABLE CELL============================?>
					<td align="left" onmouseover="shownode( <?=$node["id"]?> )"  onmouseout="hidenode( <?=$node["id"]?> )">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<?//==================SPACER CELL============================?>
								<td width="<?=$level?>"><img src="graphics/space.gif" width="<?=$level?>" height="10" alt="space"\></td>
								<?//==================DISCLOSURE TRIANGLE CELL============================?>
								<td valign="top">
                  <?//========Show triangle on everything but publications======?>
                  <?if( $node['type']!='publikation'):?>
									  <a href="javascript:toggling( <?=$node['id']?> )" 
                      title="<?=(stristr($toggleState,'down'))?'Lukke':'&Aring;bne';?>" 
                      onfocus="if(this.blur)this.blur();"><img 
                      src="graphics/<?=$toggleState?>.gif" alt="Toggle" border="0"\></a>
                  <?else:?>
                     <img src="graphics/<?=$toggleState?>.gif" alt="" border="0"\>
                  <?endif?>
								</td>
								<?//==================NODE ICON CELL============================?>
								<td valign="top">
									<?if( $isGrayIcon ):?>
                                          <a href="<?=$_SERVER['PHP_SELF']?>" 
                                            class="nodeName_gray" 
                                            onfocus="if(this.blur)this.blur();"><img 
                                            src="graphics/<?=$icon?>_gray.gif" alt="Rediger" border="0"/></a>
                                                        <?elseif( $node['id'] != $root ):?>
                                          <?if( $node['type'] != 'folder' ):?>
                                            <a href="../kildestyring/index.php?id=<?=$node['id']?>" 
                                              title="Redigere" class="nodeName" onclick="parent.resize('normal');" 
                                              target="contentfrm" 
                                              onfocus="if(this.blur)this.blur();"><img src="graphics/<?=$icon?><?=$iconPostFix?>.gif" alt="Rediger" border="0"/></a>
                                          <?else:?>
                                             <img src="graphics/<?=$icon?><?=$iconPostFix?>.gif" alt="Rediger" border="0"/>
                                          <?endif?>
									<?endif?>
								</td>
								<?//==================SPACER CELL============================?>
								<td><img src="graphics/space.gif" width="5" height="10" alt="space"\></td>
								<?//==================ITEM NAME CELL============================?>
								<td valign="top">
									<?if( $node['id'] == $rename ):?>
										<input type="text" size="14" class="textfield" name="newname" value="<?=$node['name']?>"/>
                    <a name="#rename">
									<?elseif( $nodes[$i]["moving"] ):?>
										<a href="<?=$_SERVER['PHP_SELF']?>" 
                      class="nodeName_gray" onfocus="if(this.blur)this.blur();"><?=$node['name']?></a>
									<?elseif( $move ):?>
                    <?if( approvedMove( $type, $node['type'], $node['open'] ) ):?>
                      <a href="<?=$_SERVER['PHP_SELF']?>?move=<?=$move?>&where=<?=$node['id']?>" 
                        class="nodeName" onfocus="if(this.blur)this.blur();"><?=$node['name']?></a>
                    <?else:?>
											<span class="nodeName" style="color:#000000"><?=$node['name']?></span>
                    <?endif?>
									<?else:?>
                    <?if( $node['id'] == $root ):?>
											<span class="nodeName" style="color:#000000"><?=$node['name']?></span>
										<?else:?>
											<a href="javascript:renaming(<?=$node['id']?>);" 
                                                title="Redigere navn"
                        class="<?=( !$iconPostFix || !stristr('_gray',$iconPostFix) )?"nodeName":"nodeName_gray"?>" 
                        onfocus="if(this.blur)this.blur();"><?=$node['name']?></a>
										<?endif?>
									<?endif?>
								</td>
							</tr>
						</table>
					</td>
					<?//==================ACTIONS TABLE CELL============================?>
					<td onmouseover="shownode( <?=$node['id']?> )" onmouseout="hidenode( <?=$node['id']?> )">
						<table border="0" cellpadding="0" cellspacing="0" class="hide" id="<?=$node['id']?>">
							<tr>
								<td>&nbsp;&nbsp;</td>
								<?if( !$move ):?>
                <?if( $node['id'] == $root ):?>
                  <?//======== ADD VIDENS LEVERANDØR =======?>
								  <td colspan="2">
                    <a href="<?=$_SERVER['PHP_SELF']?>?add=<?=$node['id']?>&type=<?=$node['type']?>" 
                      onmouseover="lightup('add', <?=$nodes[$i]["id"]?>)" 
                      onmouseout="grayout('add', <?=$nodes[$i]["id"]?>)" 
                      title="Tilføj videns leverandør" 
                      onfocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" 
                      name="add<?=$node['id']?>" border="0" alt="Tilføj videns leverandør"></a>
                  </td>
                <?elseif( $node['type'] != 'publikation' ):?>
                  <td>
                    <?//======== ADD KATEGORIER =======?>
                    <a href="<?=$_SERVER['PHP_SELF']?>?add=<?=$node['id']?>&type=<?=$node['type']?>" 
                      onmouseover="lightup('add',<?=$node['id']?>)" 
                      onmouseout="grayout('add',<?=$node['id']?>)" 
                      title="Tilf&oslash;j" onfocus="if(this.blur)this.blur();"><img src="graphics/add_off.gif" 
                      name="add<?=$node['id']?>" border="0" alt="Tilf&oslash;j"></a>
                  </td>
                <?endif?>

                <?if( $node['type'] == 'root' || $node['type'] == 'folder' ):?>
                <?//============ ADD FOLDER =================?>
                  <td>
                    <a href="<?=$_SERVER['PHP_SELF']?>?addfolder=<?=$node['id']?>&type=<?=$node['type']?>" 
                      onmouseover="lightup('add',<?=$node['id']?>)" 
                      onmouseout="grayout('add',<?=$node['id']?>)" 
                      title="Tilf&oslash;j Mappe" onfocus="if(this.blur)this.blur();"><img src="graphics/add_folder.gif" 
                      name="add<?=$node['id']?>" border="0" alt="Tilf&oslash;j Mappe"></a>
                  </td>
                <?endif?>

								<?if( $node['id'] != $root ):?>
                  <td>
                    <?//============ DUPLICATE ===================//?>
                    <a href="<?=$_SERVER['PHP_SELF']?>?duplicate=<?=$node['id']?>" 
                      onmouseover="lightup('duplicate',<?=$node['id']?>)" 
                      onmouseout="grayout('duplicate',<?=$node['id']?>)" 
                      title="Dubler" onfocus="if(this.blur)this.blur();"><img src="graphics/duplicate_off.gif" 
                      name="duplicate<?=$node['id']?>" border="0" alt="Dubler"></a>
                  </td>
                  <td>
                    <?//============ DELETE ===================//?>
                    <a href="javascript:removing(<?=$node['id']?>)" 
                      onmouseover="lightup('delete',<?=$node['id']?>)" 
                      onmouseout="grayout('delete',<?=$node['id']?>)" 
                      onfocus="if(this.blur)this.blur();"><img src="graphics/delete_off.gif" 
                      name="delete<?=$node['id']?>" border="0" alt="Slet"></a>
                  </td>
                  <td>
                    <?//============ MOVE ===================//?>
                    <a href="<?=$_SERVER['PHP_SELF']?>?move=<?=$node['id']?>&type=<?=$node['type']?>" 
                      onmouseover="lightup('move',<?=$node['id']?>)" 
                      onmouseout="grayout('move',<?=$node['id']?>)" 
                      title="Flyt" onfocus="if(this.blur)this.blur();"><img src="graphics/move_off.gif"
                      name="move<?=$node['id']?>" border="0" alt="Flyt"></a>
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
    <script>
      restoreScrollPosition('<?=urlencode($_SERVER['PHP_SELF'] )?>');
    </script>
	</body>
</html>

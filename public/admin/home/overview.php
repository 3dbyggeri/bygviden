<?php
    require_once("../util/tree.php");
    require_once("../util/mediaTree.php");
    require_once("../util/overview.php");

    if( !$docId ) $docId = $_POST["docId"];
    if( !$toggle ) $toggle = $_POST["toggle"];
    if( !$selected_overview ) $selected_overview = $_POST["selected_overview"];
    if( !$selected_overview ) $selected_overview = "publish";
    if( !$interval ) $interval = $_POST["interval"];
    
    if( $selected_overview == 'media' )
    {
      $table = "mediaTree";
      $tree = new mediaTree( $dba, session_id(), $table );
    }
    else
    {
      $table = "tree";
      $tree = new tree( $dba, session_id(), $table );
    }
    
    
    $overview = new overview( $dba );
    if( $selected_overview == 'wizitors' && $interval ) $overview->interval = $interval;

    $filter = $overview->getFilter( $selected_overview );
    $tree->fullOpen = TRUE;
    $tree->toggle( $toggle );
    $nodes =  $tree->getNodeArray();
    $n = count( $nodes );



    $states = array("publish"=>"Documents publishing state",
                    "nav"=>"Documents displayed on navigation",
                    "title"=>"Documents without a title",
                    "docs"=>"Documents linked or included by other documents",
                    "media"=>"Media linked or included by documents",
                    "wizitors"=>"How many users visit the site right now"
                    );

    $wizitorsDescription ="Show how many unique users are visiting the site<br>";
    $wizitorsDescription.="And wich document they are reading.<br><br>";
    if( $overview->totalCurrentUsers )
    {
      $wizitorsDescription.= "There ";
      $wizitorsDescription.= ( $overview->totalCurrentUsers > 1 )?"are":"is";
      $wizitorsDescription.= " currently ";
      $wizitorsDescription.= '<strong>'. $overview->totalCurrentUsers .' ';
      $wizitorsDescription.= ( $overview->totalCurrentUsers > 1 )?"users":"user";
      $wizitorsDescription.= '</strong>';
    }
    else
    {
      $wizitorsDescription.= "There are currently ";
      $wizitorsDescription.= "no users";
    }
    $wizitorsDescription.= " visiting the site within the specified interval ";

		$descriptions = array("publish"=>"
		<img src=../tree/graphics/doc.gif /> A colored icon symbolise a published document.<br />
		<img src=../tree/graphics/doc_gray.gif /> A grey icon symbolise an unpublished document.<br />
		<img src=../tree/graphics/doc_unpub.gif /> Colored icon with red symbol symbolise a document scheduled to be unpublished.<br />
		<img src=../tree/graphics/doc_pub.gif /> Colored icon with green symbol is a document scheduled to become published.<br /><br />
		Click on the icon to edit the document properties",
		
		"nav"=>"<img src=../tree/graphics/doc.gif /> All ducuments with a colored icon is displayed on the website navigation.<br /><br />Click on the icon to edit the document properties",
    
		"title"=>"<img src=../tree/graphics/doc_gray.gif /> All documents with a grey icon has no title.<br /><br/>Click on the icon to edit the document properties",
    
		"docs"=>"<img src=../tree/graphics/doc.gif /> All documents with a colored icon is include or linked to from other documents.<br /><br/>Click on the document icon to view it's dependencies",
    
		"media"=>"A colored icon show which media files documents depend on.<br /><br />Click on the media icon to view it's dependencies",
    
		"wizitors"=>$wizitorsDescription
                    );
?>
<script language="javascript">
	function toggling( id )
	{
		document.tree.toggle.value = id;
		document.tree.submit();
	}
	function selectNode( id )
	{
		document.tree.docId.value = id;
		document.tree.submit();
	}
</script>
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td><img src="../graphics/transp.gif" width="15" height="20"></td>
	</tr>	
	<tr>	
		<td class="header"><?=$states[ $selected_overview ]?></td>			
	</tr>
</table>
<br/>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
	<tr>
 		<td width="1" valign="top"><img src="../graphics/transp.gif" border="0" width="1" height="250"></td>
 		<td valign="top">
			<table cellpadding="0" cellspacing="0"  border="0">
				<tr>
					<td colspan="2"><img src="../graphics/transp.gif" border="0" width="10" height="10"></td>
				</tr>
				<tr>
					<td valign="top" class="tdpadtext" style="padding-left:25px;">
						<select name="selected_overview" class="select_list" onchange="document.tree.submit()">
              <option value="publish" <?=( $selected_overview =='publish')?"selected":""?>>Publish - unpublish</option>
              <option value="nav" <?=( $selected_overview =='nav')?"selected":""?>>Visible on navigation</option>
              <option value="title" <?=( $selected_overview =='title')?"selected":""?>>Without title</option>
              <option value="docs" <?=( $selected_overview =='docs')?"selected":""?>>Document dependencies</option>
              <option value="media" <?=( $selected_overview =='media')?"selected":""?>>Media dependencies</option>
              <option value="wizitors" <?=( $selected_overview =='wizitors')?"selected":""?>>Online visitors</option>
						</select>
						<br />
						<input type="hidden" name="toggle">
						<input type="hidden" name="docId">
            <br />
						<!--Start Tree-->
						<table width="100%" cellpadding="0" cellspacing="0" border="0">															
							<?for( $i = 0; $i < $n; $i++ ):?>
							<tr>
								<?//==================NODE TABLE CELL============================?>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<?//==================SPACER CELL============================?>
											<td width="<?=( $nodes[$i]["level"] ) * 10 ?>"><img src="../tree/graphics/space.gif" width="<?=( $nodes[$i]["level"] ) * 10 ?>" height="10" alt="space"\></td>
											<?//==================DISCLOSURE TRIANGLE CELL============================?>
											<td>
                          <!--<a href="#" onclick="toggling( <?=$nodes[$i]["id"]?> )" title="Toggle" onfocus="if(this.blur)this.blur();">-->
                          <img src="../tree/graphics/<?=( $nodes[$i]["open"] )? "down": "up" ?><?=( $nodes[$i]["node"] )? "node":"leaf"?>.gif" alt="Toggle" border="0"\>
                          <!--</a>-->
                          </td>
											<?//==================NODE ICON CELL============================?>
											<td nowrap>
												<span class="nodeName">
                            <?if( $table == 'tree' ):?>
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
                                <?
                                  switch( $selected_overview )
                                  {
                                    case('docs'):
                                      $documentPane ='dependencies';
                                      break;
                                    case('wizitors'):
                                      $documentPane ='statistics';
                                      break;
                                    case('title'):
                                      $documentPane ='edit';
                                      break;
                                    default:
                                      $documentPane ='settings';
                                  }
                                ?>
                                <a href="../documents/index.php?id=<?=$nodes[$i]["id"]?>&pane=<?=$documentPane?>&referer=<?=urlencode( $PHP_SELF ."?pane=".$pane."&selected_overview=". $selected_overview) ?>"><img src="../tree/graphics/doc<?=$iconPostFix?>.gif" alt="<?=$alt?>" border="0"/></a>
                            <?else:?>
                              <?
                                  $iconPostFix='';
                                  if( !$filter[ $nodes[$i]["id"] ]["state"] )
                                  {
                                    $iconPostFix.="_gray";
                                  }
                                  $format = $nodes[$i]["format"];
										              if( !in_array($format, $tree->formats ) ) $format = 'general';
                              ?>
                              <a href="../media/index.php?id=<?=$nodes[$i]["id"]?>&pane=dependencies&referer=<?=urlencode( $PHP_SELF ."?pane=". $pane ."&selected_overview=". $selected_overview )?>"><img src="../mediatree/graphics/file_icons/<?=$format?><?=$iconPostFix?>.gif" alt="<?=$alt?>" border="0"/></a>
                            <?endif?>
                        </span>
											</td>
											<?//==================SPACER CELL============================?>
											<td><img src="../tree/graphics/space.gif" width="5" height="10" alt="space"\></td>
											<?//==================ITEM NAME CELL============================?>
											<td nowrap>
												<span class="nodeName" style="color:#<?=( !$iconPostFix || !stristr('_gray',$iconPostFix) )?"000000":"cdcdcd"?>;"><?=$nodes[$i]["name"]?><?=( $filter[ $nodes[$i]["id"] ]["visitors"] )?' &nbsp;<b>['. $filter[ $nodes[$i]["id"] ]["visitors"] .']</b>':''?></span>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<?endfor?>
						</table>
						<!--End Tree-->
            <br><br>
					</td>
					<td valign="top" class="plainText" style="padding-left:15px;padding-top:4px;padding-right:10px;">
            <?if( $selected_overview == 'wizitors' ):?>
              <select name="interval"  class="select_list" style="width:100px" onchange="document.tree.submit()">
                <option value="100" <?=($overview->interval==100)?'selected':''?>>100</option>
                <option value="200" <?=($overview->interval==200)?'selected':''?>>200</option>
                <option value="300" <?=($overview->interval==300)?'selected':''?>>300</option>
                <option value="400" <?=($overview->interval==400)?'selected':''?>>400</option>
                <option value="500" <?=($overview->interval==500)?'selected':''?>>500</option>
              </select>
              <strong>time interval in second</strong>
              <br><br>
            <?endif?>
            <?=$descriptions[ $selected_overview ]?>
            <?if( $selected_overview == 'wizitors' ):?>
              <br><br>
              <input type="button" value="Refresh" class="knap" onclick="document.location.href='<?=$PHP_SELF?>?interval=<?=$overview->interval?>&pane=overview&selected_overview=wizitors'">
            <?endif?>
          </td>
				</tr>
			</table>
		</td>
	</tr>										
</table>

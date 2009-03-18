<?php
  require_once("../util/building.php");
  include_once( "../util/tree.php" );
  include_once( "../util/brancheTree.php" );

  $building = new Building( $dba, $id );
  if( !$category ) $category = $_POST['category'];
  if( !$category ) $category = $_GET['category'];

  if( $_POST['kilde'] ) $building->addKilde( $_POST['kilde'], $category );
  if( $_POST['produkt'] ) $building->addProdukt( $_POST['produkt'], $category );
  if( $_POST['varegruppe'] ) $building->addVaregruppe( $_POST['varegruppe'] );

  if( $_POST['saving'] ) $building->setGodeRaad( $_POST['rte1'] );
  
  if( $_GET['removeprodukt'] ) $building->removeProdukt( $_GET['removeprodukt'], $category );
  if( $_GET['removeagent'] )   $building->removeAgent( $_GET['removeagent'], $category );
  if( $_GET['removekilde'] )   $building->removeKilde( $_GET['removekilde'], $category );
  if( $_GET['removevaregruppe'] )  $building->removeVaregruppe( $_GET['removevaregruppe'], $category );


  $tree = new brancheTree( $dba, session_id(),'');
  $brancher = $tree->getBrancher();
  $props = $building->loadProperties();

  $selected_items = Array();
  $result = $dba->exec( "SELECT * FROM ". $dba->getPrefix() ."bygcatpubbranch WHERE element_id=". $id );
  $n = $dba->getN( $result );
  for( $i = 0; $i < $n; $i++ )
  {
      $row = $dba->fetchArray( $result );
      $selected_items[] = $row['category_id'] .'_'. $row['publication_id'].'_'.$row['branche'];

  }
  //in_array('item',$selected_items);

  function RTESafe($strText) {
    //returns safe code for preloading in the RTE
    $tmpString = trim($strText);
    
    //convert all types of single quotes
    $tmpString = str_replace(chr(145), chr(39), $tmpString);
    $tmpString = str_replace(chr(146), chr(39), $tmpString);
    $tmpString = str_replace("'", "&#39;", $tmpString);
    
    //convert all types of double quotes
    $tmpString = str_replace(chr(147), chr(34), $tmpString);
    $tmpString = str_replace(chr(148), chr(34), $tmpString);
  //	$tmpString = str_replace("\"", "\"", $tmpString);
    
    //replace carriage returns & line feeds
    $tmpString = str_replace(chr(10), " ", $tmpString);
    $tmpString = str_replace(chr(13), " ", $tmpString);
    
    return $tmpString;
  }
?>
<script src="../MochiKit.js"></script>
<script>
  function submiting()
  {
	  updateRTE('rte1');
    document.my_form.saving.value = 1; 
    document.my_form.submit();
  }
  function kildeSelected( id )
  {
    document.my_form.kilde.value = id; 
    submiting();
  }
  function addpublikations( category )
  {
    document.my_form.category.value = category; 
    p  = 'width=400,height=600,toolbar=no,scrollbars=1,directories=no,';
    p += 'status=no,location=no,menubar=no,resizable=yes';
    url = '../kildetree/select_tree.php';
    pop = window.open( url,'pop',p);
    pop.focus();
  }
  function agentSelected( id )
  {
    document.my_form.agent.value = id; 
    //document.my_form.submit();
    submiting();
  }
  function addagenter(category)
  {
    document.my_form.category.value = category; 
    p = 'width=400,height=600,toolbar=no,scrollbars=1,directories=no,';
    p+= 'status=no,location=no,menubar=no,resizable=yes';
    url = '../agenttree/select_tree.php';
    Pop = window.open( url,'pop',p);
    Pop.focus();
  }
  function productSelected(id)
  {
    document.my_form.produkt.value = id; 
    submiting();
  }
  function addprodukt( category )
  {
    document.my_form.category.value = category; 
    p = 'width=400,height=600,toolbar=no,directories=no,scrollbars=1';
    p+= ',status=no,location=no,menubar=no,resizable=yes';

    url = 'select_produkt.php';
    Pop = window.open( url,'pop',p);
    Pop.focus();
  }
  function addvaregroup()
  {
    //document.my_form.category.value = category; 
    p  = 'width=400,height=600,toolbar=no,scrollbars=1,directories=no,';
    p += 'status=no,location=no,menubar=no,resizable=yes';
    url = '../products/tree_select.php';
    pop = window.open( url,'pop',p);
    pop.focus();
  }
  function selectedVaregruppe(id,name)
  {
    document.my_form.varegruppe.value = id; 
    submiting();
  }
  function togglePublication(category,pubid,branche)
  {
  	var el =  document.getElementById('img_'+ category +'_'+ pubid +'_'+ branche);
	var is_selected = !(el.src.indexOf('transp') > 0);
	el.src= (is_selected)? '../graphics/transp.gif':'../graphics/selected.gif';
	var url = 'bygcatpubbranch.php?turn=';
	url+= (is_selected)? 'off':'on';
	url+= '&byg=<?=$id?>&cat='+ category +'&pub='+ pubid +'&branch='+ branche;
	d = doSimpleXMLHttpRequest(url);
  }
</script>
<style>
  .removing { text-decoration:none;color:#666666;padding-right:10px;font-size:11px;}
  .removing:hover { text-decoration:underline;color:#000000; }
  #cat_action {
    padding-right:6px;
  }
  #cat_action a {
    padding-left:10px;
    text-decoration:none;
    color:#666666;
    font-size:9px;
  }
  #cat_action a:hover {
    text-decoration:underline;
    color:#000000;
  }
  #listing TD {
    padding:5px;
    color:#666666;
    font-size:11px;
  }
  #listing TD a {color:#000; }
</style>
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="kilde">
<input type="hidden" name="agent">
<input type="hidden" name="produkt">
<input type="hidden" name="category">
<input type="hidden" name="varegruppe">
<input type="hidden" name="saving">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header"><?=$props['name']?>  <span class="alert_message"><?=$message?></span>
</td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr class="color2">
                <td colspan="2" class="tdpadtext" >Gode r&aring;d</td>
           </tr>
           <tr>
                <td class="tdpadtext" colspan="2">
                    <?//=getCheckListEditor( $props['goderaad'] )?>

                    <form name="RTEDemo" action="demo.htm" method="post" onsubmit="return submitForm();">
                    <script language="JavaScript" type="text/javascript" src="richtext.js"></script>
                    <script language="JavaScript" type="text/javascript">
                    <!--
                      initRTE("images/", "", "");
                    //-->
                    </script>
                    <noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

                    <script language="JavaScript" type="text/javascript">
                    <!--
                      writeRichText('rte1', '<?=RTESafe($props['goderaad'])?>', 400, 200, true, false);
                    //-->
                    </script>
                    </form>

                </td>
           </tr>
           <!-- Varegrupper -->
           <tr class="color2">
                <td class="tdpadtext" >Varegrupper</td>
                <td valign="top" id="cat_action" class="tdpadtext" align="right" nowrap>

                  <a  href="javascript:addvaregroup()" 
                    title="Tilf&oslash;j varegruppe"><img src="../graphics/add.png" border="0" /></a>
                </td>
           </tr>

           <? /****************** VAREGRUPPER *****************/?>
           <? $antalVaregrupper = count( $props['varegrupper'] ); ?>
           <?if($antalVaregrupper):?>
             <tr>
              <td colspan="2">
                <table id="listing" cellpadding="0" cellspacing="1" border="0" width="100%">
                 <?for( $i = 0; $i < $antalVaregrupper; $i++ ):?>
                   <?
                      $name = $props['varegrupper'][$i]['name'];
                      $removeURL = $_SERVER['PHP_SELF'].'?id='.$id.'&category='.$cat;
                      $removeURL.= '&removevaregruppe='.$props['varegrupper'][$i]['id']; 
                   ?>
                   <tr>
                        <td class="tdpadtext"><?=$name?></td>
                        <td  align="right">
                        <a  href="<?=$removeURL?>" title="Fjern varegruppe"><img src="../graphics/delete.gif" border="0" /></a></td>
                   </tr>
                 <?endfor?>
                </table>
              </td>
            </tr>
           <?endif?>
           <? /****************** END VAREGRUPPER *****************/?>
           <tr><td colspan="2">&nbsp;</td></tr>

           <? /****************** START PUBLIKATIONER *****************/?>
	    <tr>
	    	<td colspan="2">
			<style>
				.branche { border-right:1px solid #cdcdcd;border-bottom:1px solid #cdcdcd}
				.first { border-left:1px solid #cdcdcd; }
			</style>
			<table id="listing" cellpadding="0" cellspacing="0" border="0" width="100%">
			    <tr>
				<td>&nbsp;</td>

				<?for( $bc = 0; $bc < count( $brancher ); $bc++ ):?>
				    <td class="branche <?=($bc==0)?'first':''?>" style="border-top:1px solid #cdcdcd"><img 
				    	src="../graphics/brancher/<?=$brancher[$bc]['name']?>.gif" /></td>
				<?endfor?>
				<td>&nbsp;</td>
			    </tr>


			   <?for( $j = 0; $j < count( $props['categories'] ); $j++ ):?>
			     <?
				$cat = $props['categories'][$j]['id'] ;
				$categoryName = $props['categories'][$j]['name'];
				$antalPublikationer = count( $props['publikationer'][$cat] );
				$antalAgenter = count($props['agenter'][$cat]);
				$antalProdukter = count( $props['products'][$cat] );

			     ?>
			     <? /****************** KATEGORY HEADER *****************/?>
			     <tr class="color2"> 
			       <td class="tdpadtext"><?=$categoryName?></td> 
				<?for( $bc = 0; $bc < count( $brancher ); $bc++ ):?>
				    <td> &nbsp; </td>
				<?endfor?>

			       <td valign="top">
				  <a href="javascript:addpublikations(<?=$cat?>)" 
				    title="Tilf&oslash;j publikation"><img src="../graphics/add.png" border="0" /></a>
			       </td> 
			     </tr>
			     <? /****************** END KATEGORY HEADER *****************/?>

			      <? /****************** PUBLIKATIONER *****************/?>
			      <?if( $antalPublikationer ):?>
				   <?for( $i = 0; $i < $antalPublikationer; $i++ ):?>
				     <?
					$c = $props['publikationer'][$cat][$i];
					$name = $c['kildename'];
					$removeURL = $_SERVER['PHP_SELF'].'?id='.$id.'&category='.$c['category'];
					$removeURL.='&removekilde='.$c['kildeid']; 
				     ?>

				     <tr>
					  <td >
					    <a href="../kildestyring/index.php?id=<?=$c['kid']?>"><?=$c['kilde_leverandor_name']?></a> &#187; 
					    <a href="../kildestyring/index.php?id=<?=$c['kat_id']?>"><?=$c['kat_name']?></a> &#187; 
					    <strong><a href="../kildestyring/index.php?id=<?=$c['kildeid']?>"><?=$c['kildename']?></a></strong>
					  </td>

					<?for( $bc = 0; $bc < count( $brancher ); $bc++ ):?>
						    <td class="branche <?=($bc==0)?'first':''?>">
						    	<?
							$item = $c['category'].'_'.$c['kildeid'].'_'.$brancher[$bc]['name'];
							$img = (in_array($item,$selected_items))? 'selected.gif':'transp.gif';
							?>
						    	<a href="javascript:togglePublication('<?=$c['category']?>','<?=$c['kildeid']?>','<?=$brancher[$bc]['name']?>')"><img 
								id="img_<?=$c['category']?>_<?=$c['kildeid']?>_<?=$brancher[$bc]['name']?>"
								width="16" height="16" 
								src="../graphics/<?=$img?>" border="0" /></a>
						    </td>
					<?endfor?>
					  <td >
						<a href="<?=$removeURL?>" 
							title="Fjern publikation"
							class="removing"><img src="../graphics/delete.gif" border="0" /></a>
					  </td>
				     </tr>

				   <?endfor?>
			     <?endif?>
			     <? /****************** END PUBLIKATIONER *****************/?>




            		<?endfor?>
                  </table>
                </td>
              </tr>
	    
           <tr>
            <td ><img src="../graphics/transp.gif" height="15"></td>
           </tr>
        </table>
    </td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td >
		    <table cellpadding="0" cellspacing="0" border="0" width="325">
          <tr>
            <td class="tdpadtext">
              <?if( $referer ):?>
                <a href="<?=$referer?>"><img align="middle" src="../graphics/back_arrow.gif" border="0"></a>
              <?endif?>
            </td>
            <td  align="right">
              <input type="submit" value="Fortryd" name="cancel" class="knap">
              <input type="button" onclick="submiting()" value="Gemt" name="save" class="knap"> 
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>

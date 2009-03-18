<?php
if($_GET['publish']) $producenter->publishProduct($_GET['publish']);

if($_GET['publish_placement']) $producenter->publishPlacement($_GET['product_id'],
                                                              $_GET['element_id'],
                                                              $_GET['node_id'],
                                                              $_GET['branche']);

$products_requests = $producenter->getProductsRequests();
$placement_requests = $producenter->getPlacementRequests();

?>
<form name="myform" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr><td><img src="../graphics/transp.gif" height="20"></td></tr> 
  <tr>
    <td class="header">
      Produkt publicering
      <span class="alert_message"><?=$message?></span>
    </td>
	</tr>
  <tr><td><img src="../graphics/transp.gif" height="15"></td></tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
          <tr >
            <td class="tdpadtext">Navn</td>
            <td class="tdpadtext">Producent</td>
            <td class="tdpadtext">Request dato</td>
            <td class="tdpadtext">&nbsp;</td>
          </tr>
          <!--producent liste-->
          <?if( !count($products_requests) ):?>
            <tr class="color2">
              <td class="tdpadtext" align="center" colspan="4">Ingen anm&oslash;dninger</td>
            </tr>
          <?endif?>
          <?for($i = 0;$i < count( $products_requests);$i++ ):?>
            <?$color = ( $i % 2 == 0 )?'color2':'color3';?>
            <tr class="<?=$color?>">
              <td class="tdpadtext">
                <a href="index.php?pcr_id=<?=$products_requests[$i]['producer_id']?>&pane=product&prod_id=<?=$products_requests[$i]['id']?>" 
                  ><?=$products_requests[$i]['product_name']?></a>
              </td>
              <td class="tdpadtext">
                <a href="index.php?pcr_id=<?=$products_requests[$i]['producer_id']?>&pane=producer" 
                  ><?=$products_requests[$i]['producer_name']?></a>
              </td>
              <td class="tdpadtext">
                <?=$products_requests[$i]['publish_request']?>
              </td>
              <td class="tdpadtext">
                [<a href="<?=$_SERVER['PHP_SELF']?>?pane=workflow&publish=<?=$products_requests[$i]['id']?>">Publish</a>]
              </td>
            </tr>
          <?endfor?>
        </table>
    </td>
   </tr>
   <tr><td>&nbsp;</td></tr>
   <tr><td><img src="../graphics/transp.gif" height="15"></td><tr>

    <tr>
      <td class="header">
        Produkt placering
        <span class="alert_message"><?=$message?></span>
      </td>
    </tr>
    <tr><td><img src="../graphics/transp.gif" height="15"></td></tr>
    <tr> 
      <td>
          <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
            <tr >
              <td class="tdpadtext">Navn</td>
              <td class="tdpadtext">Producent</td>
              <td class="tdpadtext">Bygningselement</td>
              <td class="tdpadtext">Dato</td>
              <td class="tdpadtext">&nbsp;</td>
            </tr>
            <!--producent liste-->
            <?if( !count($placement_requests) ):?>
              <tr class="color2">
                <td class="tdpadtext" align="center" colspan="6">Ingen anm&oslash;dninger</td>
              </tr>
            <?endif?>
            <?for($i = 0;$i < count( $placement_requests);$i++ ):?>
              <?
                $color = ( $i % 2 == 0 )?'color2':'color3';
                $product_id = $placement_requests[$i]['product_id'];
                $product_name = $placement_requests[$i]['product_name'];
                $producer_id = $placement_requests[$i]['producer_id'];
                $producer_name = $placement_requests[$i]['producer_name'];
                $element_id =  $placement_requests[$i]['element_id'];
                $element_name =  $placement_requests[$i]['element_name'];
                $node_id =  $placement_requests[$i]['node_id'];
                $branche_name =  $placement_requests[$i]['branche_name'];
              ?>
              <tr class="<?=$color?>">
                <td class="tdpadtext">
                <a href="index.php?pcr_id=<?=$producer_id?>&pane=product&prod_id=<?=$product_id?>" 
                  ><?=$product_name?></a>
                </td>
                <td class="tdpadtext">
                  <a href="index.php?pcr_id=<?=$producer_id?>&pane=producer" 
                    ><?=$producer_name?></a>
                </td>
                <td class="tdpadtext">
                  <a href="../../index.php?node=<?=$node_id?>&element=<?=$element_id?>&branche=<?=$branche_name?>" 
                    target="_blank"><?=$element_name?></a>
                </td>
                <td class="tdpadtext">
                  <?=$placement_requests[$i]['publish_request']?>
                </td>
                <td class="tdpadtext">
                  [<a href="<?=$_SERVER['PHP_SELF']?>?pane=workflow&publish_placement=1&product_id=<?=$product_id?>&element_id=<?=$element_id?>&node_id=<?=$node_id?>&branche=<?=$branche_name?>">Publish</a>]
                </td>
              </tr>
            <?endfor?>
          </table>
      </td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr><td><img src="../graphics/transp.gif" height="15"></td><tr>

  </table>
  </form>


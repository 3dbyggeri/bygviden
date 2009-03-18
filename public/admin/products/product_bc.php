<?php
  if( !$productId ) $productId = $_GET['productId'];
  if( !$productId ) $productId = $_POST['productId'];
  if( !$data_open ) $data_open = $_GET['data_open'];
  if( !$publish_open ) $publish_open = $_GET['publish_open'];
  if( !$product_publish_state ) $product_publish_state = $_GET['product_publish_state'];

  require_once("../util/product.php");
  require_once("../util/date_widget.php");
  $product = new product( $dba, $productId );

  if( $data_open ) $product->toggleData( $data_open );
  if( $publish_open ) $product->togglePublish( $publish_open );
  if( $product_publish_state ) $product->toggle_publish( $product_publish_state );

  if( $_POST['save_product'] )
  {
    if( $_POST['data_open'] == 'y' )
    {
      $product->setName(        $_POST['name'] );
      $product->setDescription( $_POST['description'] );
      $product->setObservation( $_POST['observation'] );
      $product->setKilde_url(   $_POST['kilde_url'] );
      $product->setLogo_url(    $_POST['logo_url'] );


    }
    if( $_POST['publish_open'] )
    {
        if( $_POST['publishSchedule']  )
        {
          $product->setPublishDate( $_POST['day_publish'], $_POST['month_publish'], $_POST['year_publish'] );
        }
        else $product->setPublishDate();

        if( $_POST['unpublishSchedule'] ) 
        {
          $product->setUnPublishDate( $_POST['day_unpublish'], $_POST['month_unpublish'], $_POST['year_unpublish'] );
        }
        else $product->setUnPublishDate();
    }

    $message = "Dinne ændringer er blevet gemt";
  }

  $props = $product->loadProperties();
  
  $publishDate = new date_widget("publish");
  $unpublishDate = new date_widget("unpublish");

  if( $props['publishD'] )
  {
    $publishDate->setDate( $props['publishD'], $props['publishM'], $props['publishY'] );
  }
  if( $props['unpublishD'] )
  {
    $unpublishDate->setDate( $props['unpublishD'], $props['unpublishM'], $props['unpublishY'] );
  }
?>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="productId" value="<?=$productId?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="data_open" value="<?=$props['data_open']?>">
<input type="hidden" name="publish_open" value="<?=$props['publish_open']?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td><img src="../graphics/transp.gif" height="20"></td>
    </tr> 
    <tr>
      <td class="header">Produkt '<?=$props['name']?>'<span class="alert_message"><?=$message?></span>
  </td>
    </tr>
    <tr> 
      <td><img src="../graphics/transp.gif" height="15"></td>
    </tr>

    <tr> 
      <td>
          <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
            <tr class="color2">
              <td class="plainText">
                <a href="<?=$_SERVER['PHP_SELF']?>?productId=<?=$productId?>&pane=<?=$pane?>&id=<?=$id?>&data_open=<?=($props['data_open'] == 'y' || !$props['data_open'] )?'n':'y'?>"><img src="../graphics/<?=($props['data_open'] == 'y' || !$props['data_open'] )?'downnode':'upnode'?>.gif" border="0"></a>
                Stamdata
              </td>
            </tr>
           <?if( $props['data_open'] == 'y' || !$props['data_open'] ):?>
           <tr>
                <td class="tdpadtext" >Navn</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="name" class="input" value="<?=$props['name']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Web adresse</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="kilde_url" class="input" value="<?=$props['kilde_url']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Web adresse til produkt logo</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="logo_url" class="input" value="<?=$props['logo_url']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Produkt beskrivelse</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <textarea name="description" rows="4" cols="53" class="input" wrap="virtual"><?=$props['description']?></textarea>
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Bemærkninger fra Dansk Byggeri</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <textarea name="observation" rows="4" cols="53" class="input" wrap="virtual"><?=$props['observation']?></textarea>
                </td>
           </tr>
           <?endif?>
          <tr> <td>&nbsp;</td> </tr>
            <tr class="color2">
              <td class="plainText">
                <a href="<?=$_SERVER['PHP_SELF']?>?productId=<?=$productId?>&pane=<?=$pane?>&id=<?=$id?>&publish_open=<?=($props['publish_open'] == 'y' || !$props['publish_open'] )?'n':'y'?>"><img src="../graphics/<?=($props['publish_open'] == 'y' || !$props['publish_open'] )?'downnode':'upnode'?>.gif" border="0"></a>
                Status
              </td>
            </tr>
           <?if( $props['publish_open'] == 'y' || !$props['publish_open'] ):?>
          <tr>
              <td class="tdpadtext" valign="top">
                <input type="button" class="stor_knap" style="width:250px" onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&productId=<?=$productId?>&product_publish_state=<?=($props['publish'])?'n':'y'?>&pane=<?=$pane?>'" value="<?=( $props['publish'] )?"Nedtag produkt":"Publicere produkt"?>" >
              </td>
          </tr>
           <tr>
            <td>
              <table  cellpadding="0" cellspacing="0" border="0">
                <?if( !$props['publish'] ):?>
                  <tr>
                      <td class="tdpadtext" valign="top">
                        <input type="checkbox" name="publishSchedule" class="plainText" <?=( $props['publishD'] )?"checked":""?>>
                      </td>
                      <td class="tdpadtext" valign="top">
                        Publicere kilden den [ d.m.å ]
                        <br />
                        <?=$publishDate->render()?>
                      </td>
                  </tr>
                <?endif?>
                <tr>
                    <td class="tdpadtext" valign="top">
                      <input type="checkbox" name="unpublishSchedule" class="plainText" <?=( $props['unpublishD'] )?"checked":""?>>
                    </td>
                    <td class="tdpadtext" valign="top">
                        Nedtage kilden den [ d.m.å ] 
                        <br><?=$unpublishDate->render()?>
                    </td>
                </tr>
              </table>
            </td>
          </tr>
           <?endif?>
          <tr> <td>&nbsp;</td> </tr>

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
            <td  align="right" nowrap>
              <input type="button" onClick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>'" value="Fortryd" name="cancel" class="knap" style="width:150px"> 
              <input type="submit" value="Gemt" name="save_product" class="knap" style="width:150px"> 
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>
  </form>

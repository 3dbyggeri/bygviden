<?php
  require("../util/date_widget.php");
  if( !$id ) $id = $_GET['id'];
  if( !$id ) $id = $_POST['id'];
  if( !$data_open ) $data_open = $_GET['data_open'];
  if( !$publish_open ) $publish_open = $_GET['publish_open'];
  if( !$product_open ) $product_open = $_GET['product_open'];
  if( !$publish_state ) $publish_state = $_GET['publish_state'];
  if( !$addprodukt ) $addprodukt = $_GET['addprodukt'];
  if( !$productId ) $productId = $_GET['productId'];
  if( !$removeprodukt ) $removeprodukt = $_GET['removeprodukt'];

  $producenter->setProducent( $id );
  
  if( $data_open )     $producenter->toggleData( $data_open );
  if( $publish_open )  $producenter->togglePublish( $publish_open );
  if( $product_open )  $producenter->toggleProduct( $product_open );
  if( $publish_state ) $producenter->toggle_publish( $publish_state );
  if( $removeprodukt ) $producenter->removeProduct( $removeprodukt );

  if( $addprodukt )  
  {
    $productId = $producenter->addProduct();
    require_once("product.php");
    die();
  }
  if( $productId  || $_POST['productId'] )
  {
    require_once("product.php");
    die();
  }

  if( $save || $_POST['save'] )
  {
    if( !$data_open ) $data_open = $_POST['data_open'];
    if( !$publish_open ) $publish_open = $_POST['publish_open'];
    if( $data_open == 'y' )
    {
      if( !$name ) $name = $_POST['name'];
      if( !$kilde_url ) $kilde_url = $_POST['kilde_url'];
      if( !$logo_url ) $logo_url = $_POST['logo_url'];
      if( !$adresse ) $adresse = $_POST['adresse'];
      if( !$CVR ) $CVR = $_POST['CVR'];
      if( !$telefon ) $telefon = $_POST['telefon'];
      if( !$fax ) $fax = $_POST['fax'];
      if( !$mail ) $mail = $_POST['mail'];
      if( !$description ) $description = $_POST['description'];

      $producenter->setName( $name );
      $producenter->setKilde_url( $kilde_url );
      $producenter->setLogo_url( $logo_url );
      $producenter->setAdresse( $adresse );
      $producenter->setCVR( $CVR );
      $producenter->setTelefon( $telefon );
      $producenter->setFax( $fax );
      $producenter->setMail( $mail );
      $producenter->setDescription( $description );
      $producenter->setObservation( $observation );
    }
    if( $publish_open == 'y' )
    {
          //publish date
          if( !$publishSchedule )    $publishSchedule = $_POST["publishSchedule"];
          if( !$day_publish )        $day_publish = $_POST["day_publish"];
          if( !$month_publish )      $month_publish = $_POST["month_publish"];
          if( !$year_publish )       $year_publish = $_POST["year_publish"];

          //unpublish date
          if( !$unpublishSchedule )  $unpublishSchedule = $_POST["unpublishSchedule"];
          if( !$day_unpublish )      $day_unpublish = $_POST["day_unpublish"];
          if( !$month_unpublish )    $month_unpublish = $_POST["month_unpublish"];
          if( !$year_unpublish )     $year_unpublish = $_POST["year_unpublish"];

          if( $publishSchedule   ) $producenter->setPublishDate( $day_publish, $month_publish, $year_publish );
          else $producenter->setPublishDate();

          if( $unpublishSchedule ) $producenter->setUnPublishDate( $day_unpublish, $month_unpublish, $year_unpublish );
          else $producenter->setUnPublishDate();
    }
    $message = 'Your changes has been saved';
  }

  $producent = $producenter->loadProperties( $id );

  $publishDate = new date_widget("publish");
  $unpublishDate = new date_widget("unpublish");

  if( $producent['publishD'] )
  {
    $publishDate->setDate( $producent['publishD'], $producent['publishM'], $producent['publishY'] );
  }
  if( $producent['unpublishD'] )
  {
    $unpublishDate->setDate( $producent['unpublishD'], $producent['unpublishM'], $producent['unpublishY'] );
  }

  if( $producent['product_open'] == 'y' ) $produkt = $producenter->getProducts();
?>
<script>
  function deleting( prodID, name )
  {
    if( confirm('Er du sikker på at du vil slette produkten '+ name +'?' ) )
    {
      document.location.href = '<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$pane?>&removeprodukt='+ prodID ;
    }
  }
</script>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="data_open" value="<?=$producent['data_open']?>">
<input type="hidden" name="publish_open" value="<?=$producent['publish_open']?>">
<input type="hidden" name="product_open" value="<?=$producent['product_open']?>">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td><img src="../graphics/transp.gif" height="20"></td>
    </tr> 
    <tr>
      <td class="header">Producent '<?=$producent['name']?>'<span class="alert_message"><?=$message?></span>
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
                <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&data_open=<?=($producent['data_open'] == 'y' || !$producent['data_open'] )?'n':'y'?>"><img src="../graphics/<?=($producent['data_open'] == 'y' || !$producent['data_open'] )?'downnode':'upnode'?>.gif" border="0"></a>
                Stamdata
              </td>
            </tr>
            <?if( $producent['data_open'] == 'y' || !$producent['data_open'] ):?>
            <tr >
              <td class="tdpadtext">Navn</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="name" class="input" value="<?=$producent['name']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Web adresse til hjemmeside</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="kilde_url" class="input" value="<?=$producent['kilde_url']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Web adresse til logo</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="logo_url" class="input" value="<?=$producent['logo_url']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Adresse</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="adresse" class="input" value="<?=$producent['adresse']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">CVR nr.</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="CVR" class="input" value="<?=$producent['CVR']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Telefon</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="telefon" class="input" value="<?=$producent['telefon']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Fax</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="fax" class="input" value="<?=$producent['fax']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Mail</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <input type="text" size="53" name="mail" class="input" value="<?=$producent['mail']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Beskrivelse</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <textarea name="description" rows="4" cols="53" class="input" wrap="virtual"><?=$producent['description']?></textarea>
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Bemærkninger fra Dansk Byggeri</td>
            </tr>
            <tr >
              <td class="tdpadtext">
                <textarea name="observation" rows="4" cols="53" class="input" wrap="virtual"><?=$producent['observation']?></textarea>
              </td>
            </tr>
          <?endif?>
         <tr>
              <td class="plainText" style="padding-left: 10px; padding-bottom: 10px;">&nbsp;</td>
         </tr>
          <tr class="color2">
            <td class="plainText">
              <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&publish_open=<?=($producent['publish_open'] == 'y' || !$producent['publish_open'] )?'n':'y'?>"><img src="../graphics/<?=($producent['publish_open'] == 'y' || !$producent['publish_open'] )?'downnode':'upnode'?>.gif" border="0"></a>
              Status
            </td>
          </tr>
          <?if( $producent['publish_open'] == 'y' || !$producent['publish_open'] ):?>
          <tr>
              <td class="tdpadtext" valign="top">
                Producenten er <?=( $producent['publish'] )?" ":" ikke "?> publiceret 
              </td>
          </tr>
          <tr>
              <td class="tdpadtext" valign="top">
                <input type="button" class="stor_knap" style="width:250px" onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&publish_state=<?=($producent['publish'])?'n':'y'?>&pane=<?=$pane?>'" value="<?=( $producent['publish'] )?"Nedtag producent":"Publicere producent"?>" >
              </td>
          </tr>
           <tr>
            <td>
              <table  cellpadding="0" cellspacing="0" border="0">
                <?if( !$producent['publish'] ):?>
                  <tr>
                      <td class="tdpadtext" valign="top">
                        <input type="checkbox" name="publishSchedule" class="plainText" <?=( $producent['publishD'] )?"checked":""?>>
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
                      <input type="checkbox" name="unpublishSchedule" class="plainText" <?=( $producent['unpublishD'] )?"checked":""?>>
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
         <tr>
              <td class="plainText" style="padding-left: 10px; padding-bottom: 10px;">&nbsp;</td>
         </tr>
          <tr class="color2">
            <td class="plainText">
              <a href="<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&product_open=<?=($producent['product_open'] == 'y' || !$producent['product_open'] )?'n':'y'?>"><img src="../graphics/<?=($producent['product_open'] == 'y' || !$producent['product_open'] )?'downnode':'upnode'?>.gif" border="0"></a>
              Produkter
            </td>
          </tr>
          <?if( $producent['product_open'] == 'y' || !$producent['product_open'] ):?>
           <tr>
              <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                   <?if( !count( $produkt ) ):?>
                     <tr>
                          <td colspan="2" align="center" class="tdpadtext" >Ingen produkter</td>
                     </tr>
                  <?endif?>
                  <?for(  $i = 0; $i < count( $produkt ); $i++ ):?>
                     <tr>
                          <td class="tdpadtext" ><a class="tabelText" href="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$pane?>&productId=<?=$produkt[$i]['id']?>"><?=$produkt[$i]['name']?></a></td>
                          <td  align="right" style="margin-right:25px"><a class="tabelText" 
                            href="javascript:deleting(<?=$produkt[$i]['id']?>,'<?=$produkt[$i]['name']?>')"
                            >Fjern</a></td>
                     </tr>
                     <tr class="color3"><td colspan="2"><img src="../graphics/transp.gif" height="1"></td></tr>
                  <?endfor?>
                    <tr> <td class="plainText">&nbsp;</td> </tr>
                   <tr>
                        <td colspan="2" align="right" class="tdpadtext" >
                          <input style="width:150px" type="button" onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$pane?>&addprodukt=1'" value="Tilføj produkt" class="knap">
                        </td>
                   </tr>
                </table>

          <?endif?>
           <tr>
                <td class="plainText" style="padding-left: 10px; padding-bottom: 10px;">&nbsp;</td>
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
            <td  align="right" nowrap>
              <input type="button" onClick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>'" value="Fortryd" name="cancel" class="knap" style="width:150px"> 
              <input type="submit" value="Gemt" name="save" class="knap" style="width:150px"> 
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

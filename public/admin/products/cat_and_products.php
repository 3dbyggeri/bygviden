<?php
$pcr_id = $_GET['pcr_id'];
if(!$pcr_id) $pcr_id= $_POST['pcr_id'];
$producenter->setProducent($pcr_id);
$producent = $producenter->loadProducer($pcr_id);

$categories_and_products = $producenter->loadCategoriesAndProducts($pcr_id);
?>
<script>
  function addProduct(category_id)
  {
    document.my_form.product_category.value = category_id;
    document.my_form.add_product.value = '1';
    document.my_form.submit();
  }
  function removeProduct(product_id)
  {
    if(!confirm('Slett produkt?')) return;
    document.my_form.remove_product.value = product_id;
    document.my_form.submit();
  }
  function moveProduct(product_id)
  {
    props = 'width=500,height=300';
    url = '../../moving_product.php?sfx=1&producent_id=<?=$pcr_id?>&product_id='+product_id;
    w= window.open(url,'moving',props);
    w.focus();
  }
  function removeCategori(category_id)
  {
    if(!confirm('Slett kategori og dets produkter?')) return;
    document.my_form.remove_category.value = category_id;
    document.my_form.submit();
  }
</script>
<style>
  .color4 {background-color:#EDDF98}
</style>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="add_product" value="0">
<input type="hidden" name="remove_product">
<input type="hidden" name="remove_category">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="pcr_id" value="<?=$pcr_id?>">
<input type="hidden" name="product_category" value="0">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td><img src="../graphics/transp.gif" height="20"></td>
    </tr> 
    <tr>
      <td class="header"><span class="alert_message"><?=$message?></span>
  </td>
    </tr>
    <tr> 
      <td><img src="../graphics/transp.gif" height="15"></td>
    </tr>

    <!--
      ********************************************************************

      -Implement indent for produkts under a category
      -Implement a kategory icon (use a folder icon)
      -Add  action labels to category (Oprette produkt, slett, redigere)
      -Add action labels to produkt (slett, redigere)

      -Slette produkt
      -Slette kategori
      
      --Implement produkt page (editing)
      --Implement category page (editing)

      Front-end let the producer log-in
      Load ekstra menu items for producers
      

      ********************************************************************
    -->

    <tr> 
      <td>
          <style>
           .color1 td { border-bottom:1px solid #fff; } 
          </style>
          <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
            <?if( !count( $categories_and_products ) ):?>
              <tr class="color2">
                <td class="tdpadtext" align="center">Ingen produkter eller produkt kategorier</td>
              </tr>
            <?else:?>
              <?$counter = 0?>
              <?foreach($categories_and_products as $key=>$value):?>
                <!--display all categories but the root category-->
                <?if($key!=0):?>
                  <tr>
                    <td class="tdpadtext">
                      <a class="tabelText" 
                        href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&pane=category&cat_id=<?=$key?>" 
                        class=""><img src="../graphics/folder.gif" border="0" align="absmiddle"></a>
                      <a class="tabelText" 
                        href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&pane=category&cat_id=<?=$key?>" 
                        class=""><?=$value['name']?></a>
                    </td>
                    <td class="tdpadtext" align="right">
                      <a href="javascript:addProduct('<?=$key?>')" class="tabelText" title="Opret produkt"><img src="../graphics/add.png" border="0" /></a>

                      <a href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&pane=category&cat_id=<?=$key?>" 
                            class="tabelText" title="Redigere kategori"><img src="../../tema/graphics/edit.png" border="0" /></a>

                      <a href="javascript:removeCategori('<?=$key?>')" class="tabelText" style="padding-right:10px" title="Slette kategori"><img src="../graphics/delete.png" border="0" /></a>
                    </td>
                  </tr>
                <?endif?>
                <?for($i=0;$i<count($value['products']);$i++):?>
                    <tr class="<?=$color?>">
                      <td class="tdpadtext">
                          <a class="tabelText" style="padding-left:40px" 
                            href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&pane=product&prod_id=<?=$value['products'][$i]['id']?>" 
                            class=""><?=$value['products'][$i]['name']?></a>
                      </td>
                      <td class="tdpadtext" align="right">

                        <a href="javascript:moveProduct('<?=$value['products'][$i]['id']?>')" 
                            class="tabelText" title="Flytte produkt"><img src="../graphics/pil_right.gif" border="0" /></a>

                        <a href="<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&pane=product&prod_id=<?=$value['products'][$i]['id']?>" 
                            class="tabelText" title="Redigere produkt"><img src="../../tema/graphics/edit.png" border="0" /></a>

                        <a href="javascript:removeProduct('<?=$value['products'][$i]['id']?>')" 
                          class="tabelText" title="Slett produkt" style="padding-right:10px"><img src="../graphics/delete.png" border="0" /></a>

                      </td>
                    </tr>
                
                <?endfor?>
                <?$counter++?>
              <?endforeach?>
            <?endif?>
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
            <td class="tdpadtext">&nbsp;</td>
            <td  align="right" nowrap>
              <input type="submit" value="Oprett kategori" name="new_category" class="knap" style="width:150px"> 
              <input type="button" value="Oprett produkt" onclick="addProduct(0)" name="new_product" class="knap" style="width:150px"> 
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

<?php
require_once('../admin/util/bruger.php');
include_once('../admin/util/products.php');
require_once('../admin/util/dba.php');
require_once('../config.php');
session_start();        


$producenter = new products(new dba());
$product_id = $_REQUEST['product_id'];

if($_POST['save_product'])
{
    $is_new = false;
    if($product_id =='-1')
    {
      $category_id = ($_REQUEST['category_id'])? $_REQUEST['category_id']:0;
      $product_id = $producenter->addProduct($_SESSION['bruger_id'], $_REQUEST['category_id']);
      $is_new = true;
    }
    $producenter->updateProduct($product_id,
                                $_POST['name'],
                                $_POST['home_page'],
                                $_POST['usage_description'],
                                $_POST['logo_url'],
                                $_POST['description'],
                                $_POST['observation']);

    $producenter->relateToVaregrupper($product_id,$_POST['varegrupper_id']);
    if($is_new)
    {
      die('<script>top.location.reload(false);top.GB_hide();</script>'); 
    }

    die('<script>top.updateProduct('.$product_id.',\''.$_POST['name'].'\');top.GB_hide();</script>'); 
}

$props = $producenter->loadProduct($product_id);
$varegrupper = $producenter->getVaregrupper($product_id);
$categories = $producenter->loadCategories($props['producer_id']);
?>
<html>
    <head>
        <title>Rediger Produkt</title>
        <style>
            body, td { font-size:12px;font-family:arial,sans-serif; }
        </style>
        <script>
            function chooseVareGroup()
            {
              style = 'scrollbars=yes,toolbar=no,status=no,menubar=no,location=no';
              style+= ',directories=no,resizable=yes,width=400,height=600';
              w = window.open('../admin/products/tree_select.php','select',style);
              w.focus();
            }
            function selectedVaregruppe(id,name)
            {
              addToList(document.bruger.varegrupper,name,id);
              return;
              document.bruger.varegruppe_id.value = id;
              document.bruger.varegruppe_name.value = name;
            }
            function errorImg(img)
            {
              img.src = 'graphics/transp.gif';
            }
            function addToList(listField, newText, newValue) 
            {
              if(!listField) listField = document.myform.varegrupper;
              if ( ( newValue == "" ) || ( newText == "" ) ) return;
              var len = listField.length++;  // Increase the size of list and return the size
              listField.options[len].value = newValue;
              listField.options[len].text = newText;
              listField.selectedIndex = len;  // Highlight the one just entered (shows the user that it was entered)
            }
           
            function removeFromList(listField) 
            {
              if(!listField) listField = document.bruger.varegrupper;
              if ( listField.length == -1)  return;
              var selected = listField.selectedIndex;
              if (selected == -1) return;
              var replaceTextArray = new Array(listField.length-1);
              var replaceValueArray = new Array(listField.length-1);
              for (var i = 0; i < listField.length; i++)
              {
                if ( i < selected) { replaceTextArray[i] = listField.options[i].text; }
                if ( i > selected ) { replaceTextArray[i-1] = listField.options[i].text; }
                if ( i < selected) { replaceValueArray[i] = listField.options[i].value; }
                if ( i > selected ) { replaceValueArray[i-1] = listField.options[i].value; }
              }
              listField.length = replaceTextArray.length;
              for (i = 0; i < replaceTextArray.length; i++)
              {  
                listField.options[i].value = replaceValueArray[i];
                listField.options[i].text = replaceTextArray[i];
              }
            }
            function saveVaregrupper()
            {
              var vg = "";
              listField = document.bruger.varegrupper;
              for (var i = 0; i < listField.length; i++)
              {
                if(vg!="")  vg+=",";
                vg+= listField[i].value;
              }
              document.bruger.varegrupper_id.value = vg;
            }
          </script>
    </head>
    <body bgcolor="#FFFFFF">
     <? $img = $props['logo_url']? $props['logo_url']:'admin/graphics/transp.gif'; ?>

     <form name="bruger" method="post">
        <input type="hidden" name="product_id" value="<?=$product_id?>">
        <input type="hidden" name="category_id" value="<?=$_REQUEST['category_id']?>">
            <table cellpadding="3" cellspacing="0" class="formtable" border="0">
              <tr>
                <td>Produkt navn:</td>
              </tr>
              <tr>
                <td>
                  <input class="textfield" type="text" style="width:85%" name="name" value="<?=$props['name']?>">
                </td>
              </tr>
              <tr>
                <td>Web adresse for produktet:</td>
              </tr><tr>
                <td>
                  <input class="textfield" type="text"  style="width:85%" 
                    name="home_page" value="<?=$props['home_page']?>">
                  <input type="button" value="Test" class="button" 
                    onclick="window.open(document.bruger.home_page.value)"> 
                </td>
              </tr>
              <tr>
                <td>URL til produktets anvisning:</td>
              </tr><tr>
                <td>
                  <input type="text" name="usage_description" class="textfield" 
                    style="width:85%" 
                    value="<?=$props['usage_description']?>">
                  <input type="button" value="Test" class="button" 
                    onclick="window.open(document.bruger.usage_description.value)"> 
                </td>
              </tr>
              <tr>
                <td>Beskrivelse:</td>
              <tr></tr>
                <td>
                  <textarea name="description" 
                    style="font-size:10px;font-family:verdana;width:85%;height:75px"
                    ><?=trim($props['description'])?></textarea>
                </td>
              </tr>

              <tr>
                  <td>Varegrupper:</td>
              <tr>
              </tr>
                  <td>
                  <input type="hidden" name="varegrupper_id" class="input">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td>
                        <select size="5" name="varegrupper" style="width:250px">
                          <?for($i=0;$i< count($varegrupper);$i++):?>
                              <option value="<?=$varegrupper[$i]['id']?>"><?=$varegrupper[$i]['name']?></option>
                          <?endfor?>
                        </select>
                      </td>
                      <td style="padding-left:3px">
                        <input class="knap" type="button" value="Fjern" onclick="javascript:removeFromList()">
                        <br>
                        <input style="margin-top:4px" class="knap" type="button"
                          value="Tilf&oslash;j" onclick="javascript:chooseVareGroup()">
                      </td>
                   </tr>
                  </table>
                  </td>
               </tr>
              <tr>
                  <td>Produkt Kategori:</td>
              <tr>
               <tr>
                <td>
                  <select name="category">
                    <option value="0">Produkt kategori</option>
                      <?for($i=0;$i< count($categories);$i++):?>
                          <option value="<?=$categories[$i]['id']?>" <?=($categories[$i]['id']==$props['category_id'])?'selected':''?>><?=$categories[$i]['name']?></option>
                      <?endfor?>
                  </select>
                </td>
              </tr>
              <tr>
              <td>
                <br>
                <input type="button" value="Fortryd" 
                    onclick="top.GB_hide()"
                    name="submited" class="knap">
                <input onclick="saveVaregrupper()" type="submit" name="save_product" class="button" value="Gem">

              </td>
            </tr>
            </table>
          </form>
    </body>
</html>

<?php
require_once('../admin/util/bruger.php');
include_once('../admin/util/products.php');
require_once('../admin/util/dba.php');
require_once('../config.php');
session_start();        

if(!$_SESSION['bruger_id']) die('<script>top.location.reload(true);</script>'); 
$producenter = new products(new dba());
$product_id = $_REQUEST['product_id'];

if($_POST['save_product'])
{
    $is_new = false;
    $logo_url = $_POST['logo_url'];
    if($product_id =='-1')
    {
      $category_id = ($_REQUEST['category_id'])? $_REQUEST['category_id']:0;
      $product_id = $producenter->addProduct($_SESSION['bruger_id'], $_REQUEST['category_id']);
      $is_new = true;
    }
    if($_FILES['logo']['name'])
    {
        $msg = '';
        $path_info = pathinfo($_FILES['logo']['name']);
        $logo = 'p_'.$product_id.'.'.$path_info['extension'];
        $target_path = realpath('../logo').'/'.$logo;

        list($width, $height, $type, $attr) = getimagesize($_FILES['logo']['tmp_name']); 

        if ((($_FILES["logo"]["type"] == "image/gif")
        || ($_FILES["logo"]["type"] == "image/jpeg")
        || ($_FILES["logo"]["type"] == "image/png")
        || ($_FILES["logo"]["type"] == "image/pjpeg"))
        && ($_FILES["logo"]["size"] < 30000)
        && ($width<200 || $height<250))
        {
          if ($_FILES["logo"]["error"] > 0)
          {
            $msg ="Det var ikke mulig at uploade filen " . $_FILES["logo"]["error"];
          }
          else
          {
            if(move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) 
            {
                //$msg ="Filen ".  basename( $_FILES['logo']['name'])." er blevet uploaded";
                $logo_url = $logo;
            } 
            else
            {
                $msg ="Det var ikke mulig at uploade filen";
            }
          }
        }
        else
        {
            $msg = "<div style='text-align:center;margin:30px;font-family:sans-serif;'>";
            $msg.= "<p>Billedet skal v&aelig;re under 200 x 250 pixel.</p>";
            $msg.= "<p>Fil st&oslash;rrelse m&aring; ikke v&aelig;re over 30KB.</p>";
            $msg.= "<p>Formatet skal v&aelig;re enten gif, png eller jpg.</p>";
            $msg.='<p><a href="editProduct.php?product_id='.$product_id.'">Pr&oslash;v igen</a></p>';
            $msg.='</div>';
        }
        if($msg) die($msg);
    }
    $producenter->updateProduct($product_id,
                                $_POST['name'],
                                $_POST['home_page'],
                                $_POST['usage_description'],
                                $logo_url,
                                $_POST['description'],
                                $_POST['observation']);

    $producenter->moveProduct($product_id,$_POST['category_id']);
    $producenter->relateToVaregrupper($product_id,$_POST['varegrupper_id']);

    $category_id = $_REQUEST['category_id'];
    die('<script>top.location.href="/index.php?action=minside&item=products&cat='.$category_id.'";top.GB_hide();</script>'); 

    //die('<script>top.updateProduct('.$product_id.',\''.$_POST['name'].'\');top.GB_hide();</script>'); 
}

$props = $producenter->loadProduct($product_id);
$varegrupper = $producenter->getVaregrupper($product_id);
$categories = $producenter->loadCategories($_SESSION['bruger_id']);
?>
<html>
    <head>
        <title>Rediger Produkt</title>
        <style>
            body, td { font-size:12px;font-family:arial,sans-serif; }
            .helptext
            {
              margin-top:30px;
              margin-bottom:20px;
              padding:10px;
              background-color:#F5EDA9;
              position:relative;
              width:200px;
            }
            .help_top
            {
              position:absolute;
              top:-15px; 
              left:175px;
              background-image:url(/graphics/help.png);
              background-repeat:no-repeat;
              width:32px;
              height:32px;
            }
            
        </style>
        <script>
            function chooseVareGroup()
            {
              style = 'scrollbars=yes,toolbar=no,status=no,menubar=no,location=no';
              style+= ',directories=no,resizable=yes,width=400,height=600';
              w = window.open('../admin/products/tree_select2.php','select',style);
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


     <form name="bruger" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?=$product_id?>">
        <input type="hidden" name="current_category_id" value="<?=$props['category_id']?>">
        <input type="hidden" name="category_id" value="<?=$_REQUEST['category_id']?>">
            <table cellpadding="0" cellspacing="0" border="0">
            <tr>
            <td valign="top">
                <table cellpadding="3" cellspacing="0" class="formtable" border="0">
                  <tr>
                    <td>Produktets navn:</td>
                  </tr>
                  <tr>
                    <td>
                      <input class="textfield" type="text" style="width:85%" name="name" value="<?=$props['name']?>">
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
                    <td>Web adresse til produktet p&aring; egen hjemmeside:</td>
                  </tr><tr>
                    <td>
                      <input class="textfield" type="text"  style="width:85%" 
                        name="home_page" value="<?=$props['home_page']?>">
                      <input type="button" value="Test" class="button" 
                        onclick="window.open(document.bruger.home_page.value)"> 
                    </td>
                  </tr>
                  <tr>
                    <td>Web adresse til produktets anvisning eller datablad:</td>
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
                      <td>V&aelig;lg en eller flere varegrupper p&aring; bygviden.dk:</td>
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
                      <td>V&aelig;lg en af egne kategorier:</td>
                  <tr>
                   <tr>
                    <td>
                      <select name="category_id">
                        <option value="0"></option>
                          <?for($i=0;$i< count($categories);$i++):?>
                              <option value="<?=$categories[$i]['id']?>" <?=($categories[$i]['id']==$props['category_id']||$categories[$i]['id']==$_REQUEST['category_id'])?'selected':''?>><?=$categories[$i]['name']?></option>
                          <?endfor?>
                      </select>
                    </td>
                  </tr>
                  <tr>
                  <td align="right">
                    <br>
                    <input type="button" value="Fortryd" 
                        onclick="top.GB_hide()"
                        name="submited" class="knap">
                    <input onclick="saveVaregrupper()" type="submit" name="save_product" class="button" value="Gem">

                  </td>
                </tr>
                </table>
              </td>
              <td valign="top" style="padding-left:15px">

              <div style="margin-top:3px;margin-bottom:3px">Inds&aelig;t billede af produktet:</div>
              <?
                if($props['logo_url'] && file_exists(realpath('../logo').'/'.$props['logo_url']) )
                {
                    echo('<img src="../logo/'.$props['logo_url'].'" style="margin:10px" /><br />');
                }
              ?>
              <input type="file" name="logo" />
              <div style="margin-top:5px;font-size:10px;color:#999">
                Billedet skal v&aelig;re under 200 x 250 pixel.<br>
                Fil st&oslash;rrelse m&aring; ikke v&aelig;re over 20KB.<br>
                Formatet skal v&aelig;re enten gif, png eller jpg.<br>
              </div>

     <div class="helptext">
          <div class="help_top"></div>
          <p>
            Skriv produktets navn. 
            <br/>
            Inds&aelig;t en beskrivelse og web adressen til produktet p&aring; din egen hjemmeside. 
            <br />
            Inds&aelig;t web adresse til produktets anvisning eller datablad.
            <br />
            V&aelig;lg en eller flere varegrupper p&aring; bygviden.dk.
            S&aring; vises produktet ogs&aring; under Byggevarer og Bygningsdele p&aring; bygviden.dk. 
            <br />
            V&aelig;lg en af dine egne kategorier. Inds&aelig;t evt. et billede. 
            <br />
            Klik p&aring; Gem.
          </p>

        </div>
                
              </td>
            </tr>
            </table>
          </form>
    </body>
</html>

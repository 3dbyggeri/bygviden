<?php
require_once('../admin/util/bruger.php');
include_once('../admin/util/products.php');
require_once('../admin/util/dba.php');
require_once('../config.php');
session_start();        


$producenter = new products(new dba());
$category_id = $_REQUEST['category_id'];

if($_REQUEST['save_category'])
{
    $new_cat = false;
    if($category_id =='-1') 
    {
      $category_id = $producenter->addProductCategory($_SESSION['bruger_id']);
      $new_cat =true;
    }
    $producenter->updateCategory($category_id,
                                 $_POST['name'],
                                 $_POST['home_page'],
                                 $_POST['description']); 

    if($new_cat)
    {
      die('<script>top.location.reload(false);top.GB_hide();</script>'); 
    }
    die('<script>top.updateCategory('.$category_id.',\''.$_POST['name'].'\');top.GB_hide();</script>'); 
}

$props = $producenter->loadCategory($category_id);

?>
<html>
    <head>
        <title>Rediger Produkt Kategori</title>
        <style>
            body, td { font-size:12px;font-family:arial,sans-serif; }
            .helptext
            {
              margin-top:30px;
              margin-bottom:20px;
              padding:10px;
              background-color:#F5EDA9;
              position:relative;
              width:450px;
            }
            .help_top
            {
              position:absolute;
              top:-15px; 
              left:415px;
              background-image:url(/graphics/help.png);
              background-repeat:no-repeat;
              width:32px;
              height:32px;
            }
            
        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <div class="helptext">
          <div class="help_top"></div>
          <p>
            Navngiv kategorien og inds&aelig;t evt. en web adresse til kategorien p&aring;
            din egen hjemmeside. 
            <br />Inds&aelig;t en kort beskrivelse. 
            <br />Klik p&aring; Gem.
          </p>

        </div>
      
        <form name="bruger" method="post">
              <input type="hidden" name="action" value="editProductCategori.php"> 
              <input type="hidden" name="category_id" value="<?=$category_id?>">
            <table width="100%" cellpadding="3" cellspacing="0" class="formtable" border="0">
              <tr>
                <td>Kategori navn:</td>
              </tr>
              <tr>
                <td>
                  <input class="textfield" type="text" style="width:85%" 
                    name="name" value="<?=$props['name']?>">
                </td>
              </tr>
              <tr>
              <tr>
                <td>Web adresse for kategori:</td>
              </tr><tr>
                <td>
                  <input class="textfield" type="text"  style="width:85%" 
                    name="home_page" value="<?=$props['home_page']?>">
                  <input type="button" value="Test" class="button" 
                    onclick="window.open(document.bruger.home_page.value)"> 
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
              <td>
                <br>
                <input type="button" value="Fortryd" 
                    onclick="top.GB_hide()"
                    name="submited" class="knap">
                <input type="submit" name="save_category" class="button" value="Gem">

              </td>
            </tr>
            </table>
        </form>
    </body>
</html>

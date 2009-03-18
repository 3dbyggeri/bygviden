<?php
session_start();        //start session

$sfx = ($_GET['sfx'])? $_GET['sfx']:$_POST['sfx'];

if(!is_numeric( $_SESSION['bruger_id'] ) ) if(!$sfx) die('User id missing');
$producent_id = ($_GET['producent_id'])? $_GET['producent_id']:$_POST['producent_id'];
$product_id = ($_GET['product_id'])? $_GET['product_id']:$_POST['product_id'];

if( $_SESSION['bruger_id'] != $producent_id) 
{
  if(!$sfx) die('You are not allowed to edit other producer profiles');
}

require_once("admin/util/dba.php");
require_once('admin/util/products.php');
$dba = new dba();
$producenter = new products( $dba );
$producenter->setProducent($producent_id);

if($_POST['save'])
{
  $producenter->moveProduct($product_id,$_POST['category_id']);
  die( '<script>opener.location.reload();window.close();</script>');
}

$categories_and_products = $producenter->loadCategoriesAndProducts($producent_id);
?>
<html>
  <head>
    <title>Flytte produkt</title>
    <link href="styles/bygviden.css" rel="stylesheet" type="text/css">
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link href="styles/links.css" rel="stylesheet" type="text/css" />
    <style>
      #produkter TD { border-bottom:1px dashed #999999;}
    </style>
  </head>
  <body bgcolor="#FFFFFF" style="margin:0px;padding:0px"> 
  <form name="myform" action="<?=$_SERVER['PHP_SELF']?>" method="post">
  <input type="hidden" name="producent_id" value="<?=$producent_id?>">
  <input type="hidden" name="product_id" value="<?=$product_id?>">
  <input type="hidden" name="save" value="1">
  <input type="hidden" name="sfx" value="<?=$sfx?>">

  <table width="100%" cellpadding="4" cellspacing="0" border="0">
    <tr>
      <td bgcolor="#CC6600" align="right" valign="middle" style="height:30px">&nbsp;</td>
      <td bgcolor="#CC6600" align="right" valign="middle">
        <a href="javascript:window.close()" 
          style="font-family:verdana,sans-serif;text-decoration:none;font-size:12px;font-weight:900;color:#ffffff">[x]</a>

      </td>
   </tr>
  </table>
  <style>
    #container
    {
      margin:20px;
      font-size:12px;
    }
    #container td
    {
      font-size:12px;
    }
  </style>


<div id="container">
  <div class="sub_header">Hvilket kategori skal produktet flyttes under?</div>
  <br>

  <table width="100%" id="produkter" cellpadding="4" cellspacing="0" border="0">
   <?foreach($categories_and_products as $key=>$value):?>
      <!--display all categories but the root category-->
      <?
        $catName = ($key==0)? 'Diverse':$value['name'];
      ?>
      <tr>
        <td width="20">
          <input type="radio" name="category_id" value="<?=$key?>">
        </td>
        <td>
          <?=$catName?>
        </td>
      </tr>
    <?endforeach?>
  </table>
  <br>
  <table cellpadding="3" border="0">
      <tr>
        <td> 
          <input type="button" class="button" value="Fortryd" onclick="window.close()"> 
          <input type="submit" class="button" value="Gem">
        </td>
      </tr>  
  </table>
  </form>
  </div>

</body> 
</html>

<?php 
if( !$pane) $pane = $_GET["pane"];
if( !$pane ) $pane = $_POST["pane"];
if( !$PHP_SELF ) $_SERVER["PHP_SELF"];

require("../util/dba.php");
require("../util/user.php");
require_once('../util/products.php');

session_start();
$dba  = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");
$producenter = new products( $dba );

$pcr_id = $_GET['pcr_id'];
$cat_id = $_GET['cat_id'];
$prod_id = $_GET['prod_id'];

if( !$pcr_id ) $pcr_id = $_POST['pcr_id'];
if(!$cat_id ) $cat_id = $_POST['cat_id'];
if(!$prod_id ) $prod_id = $_POST['prod_id'];

if( $_POST['add'] )
{
  $pcr_id = $producenter->addProducent();
  header('Location: http://'.$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']). '/index.php?pcr_id='.$pcr_id.'&pane=producer');
  die();
}
if( $_POST['add_product'] )
{
  $prod_id = $producenter->addProduct($pcr_id, $_POST['product_category']);
  $pane = 'product';
}
if( $_POST['remove_product'] ) $producenter->removeProduct($_POST['remove_product']);
if( $_POST['remove_category'] ) $producenter->removeCategory($_POST['remove_category']);

if( $_POST['new_category'] )
{
  $cat_id = $producenter->addProductCategory($pcr_id);
  $pane = 'category';
}
if( $_POST['update_category'])
{
  $producenter->updateCategory($cat_id,$_POST['name'],$_POST['home_page'],$_POST['description']); 
  unset($cat_id);
  $pane = 'product_categories';
}
if( $_POST['update_product'])
{
  $producenter->updateProduct($prod_id,
                              $_POST['name'],
                              $_POST['home_page'],
                              $_POST['usage_description'],
                              $_POST['logo_url'],
                              $_POST['description'],
                              $_POST['observation'],
                              $_POST['publish']);
  $producenter->relateToVaregrupper($prod_id,$_POST['varegrupper_id']);
  unset($prod_id);
  $pane = 'product_categories';
}

$panes = array('producers'=>'Producenter');
if($pane == 'producers')
{
  $pcr_id = '';
  $prod_id = '';
  $cat_id = '';
}
if($pane == 'producer')
{
  $prod_id = '';
  $cat_id = '';
}
if($pcr_id) 
{
  //$panes['producer'] = 'Producent';
  //$panes['product_categories'] = 'Kategorier og produkter';
}
if($cat_id)
{
  unset($panes['product_categories']); 
  $panes['category'] = 'Kategori';
}
if($prod_id) 
{
  unset($panes['product_categories']); 
  //$panes['product'] = 'Produkt';
}

if(!$pane) $pane = 'producers';

switch( $pane)
{
    case('producers'):
        $paneinclude='producers.php';
        break;
    case('producer'):
        $paneinclude='producer.php';
        break;
    case('product_categories'):
        $paneinclude='cat_and_products.php';
        break;
    case('category'):
        $paneinclude='category.php';
        break;
    case('product'):
        $paneinclude='product.php';
        break;
    case('news'):
        $paneinclude='news.php';
        break;
}
?>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <style>
          #settings td { font-weight:100;font-size:11px; } 
          #settings h2 { font-size:11px;margin-left:10px; }
          #subnav { background-color:#736E72;padding:0;margin:0;padding-left:10px;padding-top:20px; }
          #subnav a { color:#fff;font-size:11px;padding:10px;padding-top:5px;
                      text-decoration:none; }
          #subnav a:hover { background-color:#fff;color:#333; }
          #subnav a.selected { background-color:#fff;color:#333; }
        </style>
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
      <table cellpadding="0" cellspacing="0" border="0">
            <tr>
            <td><img src="../graphics/transp.gif" /></td>
            <?foreach( $panes as $key => $value ):?>
              <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&cat_id=<?=$cat_id?>&prod_id=<?=$prod_id?>&pane=<?=$key?>'" 
                style="cursor:hand;"><img 
                src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
              <td  onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&cat_id=<?=$cat_id?>&prod_id=<?=$prod_id?>&pane=<?=$key?>'" 
                class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" 
                style="cursor:hand;" ><?=$value?> </td>
              <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pcr_id=<?=$pcr_id?>&cat_id=<?=$cat_id?>&prod_id=<?=$prod_id?>&pane=<?=$key?>'" 
                style="cursor:hand;"><img 
                src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>
              <td><img src="../graphics/transp.gif" width="4"></td>
            <?endforeach?>
         </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
       <tr>
          <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
          <td class="tdborder_content" valign="top">
            <?if($pcr_id):?>
                <?
                    $ctx = ($_REQUEST['ctx'])? $_REQUEST['ctx']:'profile';

                    if($ctx=='products') $paneinclude = 'cat_and_products.php';
                    if($pane =='product_categories' || $pane =='category' || $pane=='product') $ctx = 'products';
                    if($pane =='news') $ctx='news';


                    $producenter->setProducent($pcr_id);
                    $producent = $producenter->loadProducer($pcr_id);
                ?>
                <div id="subnav">

                <table width="98%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="50%" style="color:#fff;font-size:11px;font-weight:100"><?=$producent['name']?></td>
                        <td align="right"> 
                            <a href="?pane=producer&pcr_id=<?=$pcr_id?>" class="<?=($ctx=='profile')?'selected':''?>">Profil</a>
                            <a href="?pane=product&pcr_id=<?=$pcr_id?>&ctx=products" class="<?=($ctx=='products')?'selected':''?>">Produkter</a>
                            <a href="?pane=news&pcr_id=<?=$pcr_id?>&ctx=news" class="<?=($ctx=='news')?'selected':''?>">Nyheder</a>
                        </td>
                   </tr>
                </table>
                </div>
            <?endif?>
            <?if($paneinclude ) require_once( $paneinclude )?>&nbsp;
          </td>
        </tr>
      </table>
    </body>
</html>


<?php
require_once("Advertiser.php");
  
$advertiser = new advertiser( $dba );

$edit = ($_GET['id'])? $_GET['id']:$_POST['id']; 
if( $_POST['add'] ) $edit = $advertiser->add($_POST['add']);
if( is_numeric( $_GET['delete'] ) ) $advertiser->remove( $_GET['delete'] );

$total = $advertiser->total();
?>
<style>
  #settings td { font-weight:100;font-size:11px; } 
  #subnav { background-color:#736E72;padding:0;margin:0;height:40px;padding-left:10px; }
  #subnav a { color:#fff;font-size:11px;line-height:50px;padding:10px;padding-top:5px;
              text-decoration:none; }
  #subnav a:hover { background-color:#fff;color:#333; }
  #subnav a.selected { background-color:#fff;color:#333; }
</style>
<script>
function removing(id)
{
    if(!confirm("Er du Sikkert?")) return;
    document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&delete='+ id;
}
</script>
<?if(is_numeric($edit)):?>
<?
$ctx = ($_REQUEST['ctx'])? $_REQUEST['ctx']:'profile';
if($_POST['save']=='1') $advertiser->update($_POST['id']);
$props = $advertiser->load($edit);
?>
<div id="subnav">

<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="50%" style="color:#fff;font-size:11px;font-weight:100"><?=$props['company_name']?></td>
        <td align="right"> 
            <a href="?pane=<?=$pane?>&id=<?=$edit?>&ctx=profile" class="<?=($ctx=='profile')?'selected':''?>">Profil</a>
            <a href="?pane=<?=$pane?>&id=<?=$edit?>&ctx=products" class="<?=($ctx=='products')?'selected':''?>">Produkter</a>
            <a href="?pane=<?=$pane?>&id=<?=$edit?>&ctx=news" class="<?=($ctx=='news')?'selected':''?>">Nyheder</a>
        </td>
   </tr>
</table>
</div>
<table id="settings" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td ><img src="../graphics/transp.gif" height="30"></td>
  </tr>
  <tr bgcolor="#e3e3e3">
    <td>					
        <?if($ctx=='profile') require_once('profile.php')?>
        <?if($ctx=='products') require_once('products.php')?>
        <?if($ctx=='news') require_once('news.php')?>
    </td>
 </tr>
  <tr>
    <td ><img src="../graphics/transp.gif" height="30"></td>
  </tr>
</table>
<?else:?>
    <?require_once('advertiserlist.php')?>
<?endif?>

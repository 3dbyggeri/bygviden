<?php
  require_once("../util/categori.php");
  $categori = new Categori( $dba );

  if( $add || $_POST['add'] )
  {
    if( !$categori_name ) $categori_name = $_POST['categori_name'];
    $categori->addCategori( $categori_name );
  }
  if( $edited || $_POST['edited'] )
{
  if( !$new_name ) $new_name = $_POST['new_name'];
  if( !$categori_id ) $categori_id = $_POST['categori_id'];

    $categori->setName( $categori_id, $new_name );
  }
  if( $delete || $_GET['delete'] )
  {
    if( !$delete ) $delete = $_GET['delete'];
    $categori->deleteCategori( $delete );
  }
  if($_GET['swap']) $categori->moving($_GET['swap'],$_GET['with']);

  $cats = $categori->getCategories();
?>
<form name="myform" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Kategorier<span class="alert_message"><?=$message?></span>
</td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="tdpadtext" colspan="2">&nbsp;</td>
           </tr>
           <?if( !count( $cats ) ):?> 
           <tr class="color2" colspan="2">
                <td class="tdpadtext" align="center" style="padding:25px">Ingen kategorier</td>
           </tr>
           <?endif?>
           <?for( $i = 0; $i < count( $cats ); $i++ ):?>
            <?$color=($i%2==0)?'color1':'color2';?>
             <tr class="<?=$color?>">
                  <td class="tdpadtext">
                    <?if( $cats[$i]['id'] == $_GET['edit'] ):?>
                      <input type="hidden" name="categori_id" value="<?=$cats[$i]['id']?>">
                      <input type="text" class="input" name="new_name" value="<?=$cats[$i]['name']?>">
                    <?else:?>
                      <a href="<?=$_SERVER['PHP_SELF']?>?edit=<?=$cats[$i]['id']?>" class="tabelText"><?=$cats[$i]['name']?></a>
                    <?endif?>
                  </td>
                  <td class="tdpadtext" align="right">
                    <?if( $cats[$i]['id'] == $_GET['edit'] ):?>
                      <input type="submit" value="Cancel" class="knap" name="cancel">
                      <input type="submit" value="OK" class="knap" name="edited">
                    <?else:?>

                      <?if($i==0):?>
                          <img src="../graphics/pil_up.gif" border="0" style="filter:alpha(opacity=50);-moz-opacity: 0.5;opacity: 0.5;">
                      <?else:?>
                          <a title="Op" href="<?=$_SERVER['PHP_SELF']?>?swap=<?=$cats[$i]['id']?>&with=<?=$prev?>"><img
                            src="../graphics/pil_up.gif" border="0"></a>
                      <?endif?>


                      &nbsp;

                      <?if($i==count($cats)-1):?>
                          <img src="../graphics/pil_down.gif" style="filter:alpha(opacity=50);-moz-opacity: 0.5;opacity: 0.5;">
                      <?else:?>
                          <a title="Ned" href="<?=$_SERVER['PHP_SELF']?>?swap=<?=$cats[$i + 1]['id']?>&with=<?=$cats[$i]['id']?>"><img
                            src="../graphics/pil_down.gif" border="0"></a>
                      <?endif?>

                      &nbsp;&nbsp;

                      <a title="Slet" href="<?=$_SERVER['PHP_SELF']?>?delete=<?=$cats[$i]['id']?>" class="tabelText">[x]</a>
                    <?endif?>
                  </td>
             </tr>
             <?
                $prev = $cats[$i]['id'];
             ?>
           <?endfor?>

           <tr>
                <td class="tdpadtext" align="center">
                  <input type="text" class="input" style="width:150px" name="categori_name" value="Kategori navn">
                  <input type="submit" name="add" class="knap" style="width:150px" value="Tilføj kategori">
                </td>
           </tr>
        </table>
    </td>
  </tr>
</table>
</form>

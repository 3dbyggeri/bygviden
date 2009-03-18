<?php
require_once("../util/branche.php");
require_once("../util/categori.php");
if( !$id ) $id = $_GET['id'];
if( !$id ) $id = $_POST['id'];
if( !$branche ) $branche = $_GET['branche'];
if( !$branche ) $branche = $_POST['branche'];

if( !is_numeric($id) ) die("Parameter expected id");
if( !$branche ) die("Parameter expected branche");

$myBranche = new Branche( $dba, $id, $branche );
$categori = new Categori( $dba );

if( $save || $_POST['save'] )
{
  if( !$category_id ) $category_id = $_POST['category_id']; 
  $myBranche->saveCategories( $category_id );
  $message = "Dinne ændringer er blevet gemt";
}

$props = $myBranche->loadProperties();
$cats = $categori->getCategories();
$brancheCats = $myBranche->loadCategories();
?>
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="branche" value="<?=$branche?>">
<input type="hidden" name="saving">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header"><?=$props['name']?>  <span class="alert_message"><?=$message?></span>
</td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="tdpadtext" colspan="2">Valgt de kategorier som skal være aktive</td>
           </tr>
           <tr>
                <td class="tdpadtext" colspan="2">&nbsp;</td>
           </tr>
           <?for( $i = 0; $i < count( $cats ); $i++ ):?>
            <?$color=($i%2==0)?'color2':'color3';?>
             <tr class="<?=$color?>">
                  <td class="tdpadtext" colspan="2">
                      <input type="checkbox" name="category_id[]" value="<?=$cats[$i]['id']?>" <?=( in_array( $cats[$i]['id'], $brancheCats ) )?'checked':''?>>
                      &nbsp;
                      <?=$cats[$i]['name']?>
                  </td>
             </tr>
           <?endfor?>
           <tr>
                <td colspan="2" class="tdpadtext" style="padding-bottom:25px">&nbsp;</td>
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
            <td  align="right">
              <input type="button" value="Fortryd" onclick="document.location.href='about:blank'" class="knap">
              <input type="submit" value="Gemt" name="save" class="knap"> 
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>

<?php
$props = $producenter->loadCategory($cat_id);
?>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="pcr_id" value="<?=$pcr_id?>">
<input type="hidden" name="cat_id" value="<?=$cat_id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td><img src="../graphics/transp.gif" height="20"></td>
    </tr> 
    <tr> 
      <td><img src="../graphics/transp.gif" height="15"></td>
    </tr>

    <tr> 
      <td>
          <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
           <tr>
                <td class="tdpadtext" >Kategori navn</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="name" class="input" value="<?=$props['name']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Web adresse for kategorien</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="home_page" class="input" value="<?=$props['home_page']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Kategori beskrivelse</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <textarea name="description" rows="4" cols="53" class="input" wrap="virtual"><?=$props['description']?></textarea>
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
            </td>
            <td  align="right" nowrap>
              <input type="button" onClick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=product_categories&pcr_id=<?=$pcr_id?>'" value="Fortryd" name="cancel" class="knap" style="width:150px"> 
              <input type="submit" value="Gemt" name="update_category" class="knap" style="width:150px"> 
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

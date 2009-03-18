<?php
$props = $producenter->loadProduct($prod_id);
//$props['varegruppe_name']= $producenter->getVaregruppeName($props['varegruppe_id']);
?>
<script>
  function chooseVareGroup()
  {
    
    style = 'scrollbars=yes,toolbar=no,status=no,menubar=no,location=no';
    style+= ',directories=no,resizable=yes,width=400,height=600';
    w = window.open('tree_select.php','select',style);
    w.focus();
  }
  function selectedVaregruppe(id,name)
  {
    addToList(document.my_form.varegrupper,name,id);
    return;
    document.my_form.varegruppe_id.value = id;
    document.my_form.varegruppe_name.value = name;
  }
</script>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="pcr_id" value="<?=$pcr_id?>">
<input type="hidden" name="prod_id" value="<?=$prod_id?>">
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
                <td class="tdpadtext" >Produkt navn</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="name" class="input" value="<?=$props['name']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Web adresse for produktet</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="home_page" class="input" value="<?=$props['home_page']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >URL til produkts anvisning</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="usage_description" class="input" value="<?=$props['usage_description']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >URL til produkt logo</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <input type="text" size="53" name="logo_url" class="input" value="<?=$props['logo_url']?>">
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Produkt beskrivelse</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <textarea name="description" rows="4" cols="53" class="input" wrap="virtual"><?=$props['description']?></textarea>
                </td>
           </tr>
           <tr>
                <td class="tdpadtext">Varegrupper</td>
           </tr>
           <tr>
                <td class="tdpadtext">
                  <script>
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
                      if(!listField) listField = document.my_form.varegrupper;
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
                      var vg = '';
                      listField = document.my_form.varegrupper;
                      for (var i = 0; i < listField.length; i++)
                      {
                        if(vg!='')  vg+=',';
                        vg+= listField[i].value;
                      }
                      document.my_form.varegrupper_id.value = vg;
                    }
                  </script>
                  
                  <input type="hidden" name="varegrupper_id" class="input">
                  <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td>
                        <select size="5" name="varegrupper" style="width:250px">
                        <?  $varegrupper = $producenter->getVaregrupper($prod_id); ?>
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
                <td class="tdpadtext">
                  <input type="checkbox" name="publish" value="y" <?=($props['publish'] == 'y')? 'checked':''?>> Publiceret 
                </td>
           </tr>
           <tr>
                <td class="tdpadtext" >Bem&aelig;rkninger fra Dansk Byggeri</td>
           </tr>
           <tr>
                <td class="tdpadtext" >
                  <textarea name="observation" rows="4" cols="53" class="input" wrap="virtual"><?=$props['observation']?></textarea>
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
              <input onclick="saveVaregrupper()" type="submit" value="Gemt" name="update_product" class="knap" style="width:150px"> 
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

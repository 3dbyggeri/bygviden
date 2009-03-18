<?php
require_once("../util/branche.php");
$id = $_GET['id'];
if( !$id ) $id = $_POST['id'];
$branche = $_GET['branche'];
if( !$branche ) $branche = $_POST['branche'];
if( !is_numeric($id) ) die("Parameter expected id");
if( !$branche ) die("Parameter expected branche");

$myBranche = new Branche( $dba, $id, $branche );

if( $saving || $_POST['saving'] )
{
  if( !$elementname ) $elementname = $_POST['elementname'];
  if( !$elementid ) $elementid = $_POST['elementid'];
  if( !$elementname ) $elementname = 'Referance';

  $myBranche->setName( $elementname );
  $myBranche->setElementId( $elementid );
  $message = "Dinne ændringer er blevet gemt";
  
  //reload tree
  echo "<script>top.treefrm.document.location.href='../branchetree/tree.php?branche=$branche';</script>";
}

$props = $myBranche->loadProperties();
?>
<script language="javascript">
  function selectNode( id, name )
  {
    document.tree.elementid.value = id;
    document.tree.elementname.value = name;
    document.tree.saving.value = 1;
    document.tree.submit();
  }
  function clearNode()
  {
    document.tree.elementid.value = '';
    document.tree.elementname.value = '';
  }
</script>
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="branche" value="<?=$branche?>">
<input type="hidden" name="elementid" value="<?=$props['element_id']?>">
<input type="hidden" name="elementname" value="<?=$props['element_name']?>">
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
                <td class="tdpadtext" colspan="2">Valgt en bygnings element som branche elementet skal referer til</td>
           </tr>
           <tr>
                <td class="tdpadtext" align="left"  valign="top">
                  <?require_once("select_tree.php");?>
                </td>
           </tr>
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
              <!--
              <input type="button" value="Cancel" onclick="document.location.href='about:blank'" class="knap">
              <input type="submit" value="Save" name="save" class="knap"> 
              -->
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>

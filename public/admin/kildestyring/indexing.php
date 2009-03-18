<?
include_once('../../page/aci.php');

$aci = new ACI();
$aci->debug = true;

if($_GET['remove']) $aci->removeFromIndex($id,$_GET['remove']);

$contents = $aci->hitsfordoc($id);
$hits = $aci->getHits($contents);
?>
<script>
function removing()
{
    var docs_id ='';
    var num_docs = 0;
    for(var i=0; i < document.usage_form.docs.length; i++)
    {
        if(document.usage_form.docs[i].checked)
        {
            if(docs_id!='') docs_id+='+';
            docs_id+= document.usage_form.docs[i].value; 
            num_docs++;
        }
    }
    if(docs_id=='') return;
    if(!confirm('Fjern '+ num_docs +' dokumener fra indeksen?')) return;
    document.location.href='?id=<?=$id?>&remove='+ docs_id;
}
    
</script>
<form name="usage_form">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="header">
            <?=$kilde->name?>  <span class="alert_message"><?=$message?></span>
          </td>
          <td align="right">
              <!--
              <input onclick="if(confirm('Sikkert?')) window.open('log.php?clear=<?=$id?>')" type="button" class="knap" size="30px" value="Fjern Spider cache"> 
              -->
              &nbsp;&nbsp;
              <input onclick="window.open('log.php?id=<?=$id?>')" type="button" class="knap" size="30px" value="Spider log"> 
              &nbsp;&nbsp;
          </td>
       </tr>
      </table>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
  	<td>
        <table id="stamdata" class="color1" width="100%" cellpadding="3" cellspacing="0" border="0" >
            <tr><td colspan="2">&nbsp;</td></tr>

            <?if(!count($hits)):?>
               <tr class="color2" colspan="2">
                    <td class="tdpadtext" align="center" style="padding:25px">
                        Ingen dokumenter er blevet indekseret for denne kilde
                    </td>
               </tr>
            <?endif?>
            <?for($i=0;$i<count($hits);$i++):?>
                <?$color=($i%2==0)?'color1':'color2';?>
                 <tr class="<?=$color?>">

                      <td class="tdpadtext" align="right">
                            <input type="checkbox" name="docs" value="<?=$hits[$i]['autonomy_id']?>" />
                      </td>
                      <td class="tdpadtext">
                            <a href="<?=$hits[$i]['reference']?>" target="_blank" class="tabelText"><?=$hits[$i]['title']?></a>
                            <br>
                            <a href="<?=$hits[$i]['reference']?>" target="_blank" class="tabelText"><?=$hits[$i]['reference']?></a>
                      </td>
                      <!--
                      <td class="tdpadtext" align="right">
                            <a href="?id=<?=$id?>&remove=<?=$hits[$i]['autonomy_id']?>" class="tabelText">[ Fjern fra index ]</a>
                      </td>
                      -->
                </tr>
            <?endfor?>

            <tr><td colspan="2">&nbsp;</td></tr>
        </table>
    </td>
 </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td >
		    <table cellpadding="0" cellspacing="0" border="0" width="325">
          <tr>
            <td class="tdpadtext">
              <input type="button" onclick="removing()" value="Fjern de valgte dokumenter fra indeksen" name="remove" class="knap">
            </td>
            <td  align="right">&nbsp;</td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>
</form>

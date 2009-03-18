<?php
require_once("admin/util/bruger.php");
if( !is_numeric( $_SESSION['bruger_id'] ) ) die('User id missing');
$user = $_GET['user'];

$bruger = new bruger( $dba );
$bruger->setId( $_SESSION['bruger_id'] );
$forbrug = $bruger->getForbrug( $faktura );

?>
<script>
  function displaydoc( id, betaling, url )
  {
    <?if( !$_SESSION['bruger_id'] ):?> 
      if( betaling ) 
      {
        alert('Adgang til publikationen kræver at du først logger ind \n med dit brugernavn og password');
        return;
      }
    <?endif?>
    //pop the publication in a new window
    url = ( url )? url:'';
	  w = window.open('view/document.php?pub='+ id +'&uri='+ url,'Publication');
    w.focus();
  }
</script>
<span class="sub_header">Personligt forbrug <?=$fakturaHead?></span>
<!--Red dotted line-->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="10"><img src="graphics/transp.gif" width="10" height="10"></td>
	</tr>
	<tr>
		<td height="1" background="graphics/red_dotted.gif"><img src="graphics/transp.gif" width="10" height="1"></td>
	</tr>
	<tr>
		<td height="10"><img src="graphics/transp.gif" width="10" height="10"></td>
	</tr>
</table>
<!--End of red dotted line-->
<?/*-------------------------------------------------
   p.kilde_url AS pub_url,
   p.name      AS pub_title,
   u.publication_id,
   u.url,
   u.title,
   u.pris,
   u.abonament_periode,
   u.readed

   2do:
   Compare the url and kilde_url, if they are not the same this is an agent request

*/?>
<?if( count( $forbrug ) ):?>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabelPad">
    <tr>
      <td class="label" valign="top" style="padding-right:15px;">Titel</td>
      <td class="label" valign="top">Betingelser</td>
      <td class="label" valign="top">Dato</td>
      <td class="label" valign="top" align="right">Pris</td>
    </tr>
    <tr>
			<td colspan="4" height="1"><!--<hr width="100%" size="1" color="#808080" noshade>--></td>
		</tr>
  <?for( $i = 0; $i < count( $forbrug ); $i++ ):?>
    <tr>
      <td valign="top" style="padding-right:15px;"><a href="javascript:displaydoc(<?=$forbrug[$i]['publication_id']?>,1)" class="infolinks"><?=$forbrug[$i]['title']?></a></td>
      <td valign="top" width="100" NOWRAP><?=( $forbrug[$i]['abonament_periode'])?$forbrug[$i]['abonament_periode'].' Md. abonnement':'Enkelt visning'?></td>
      <td valign="top" width="75" NOWRAP><?=$forbrug[$i]['readed']?></td>
      <td valign="top" align="right" width="75" NOWRAP><?=( $forbrug[$i]['pris'] )? $forbrug[$i]['pris']:0?> kr.<? $total+= $forbrug[$i]['pris'] ?></td>
    </tr>
  <?endfor?>
  <tr>
		<td colspan="4" height="1"><!--<hr width="100%" size="1" color="#808080" noshade>--></td>
	</tr>
  <tr>
    <td align="right" valign="top" colspan="2">&nbsp;</td>
    <td valign="top" ><strong>I alt:</strong></td>
    <td align="right" valign="top"><strong><?=( $total )? $total:0?> kr.</strong></td>
  </tr>
</table>
<?else:?>
  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabelPad">
    <tr>
      <td class="label" valign="top" align="center">Ingen forbrug</td>
    </tr>
  </table>   
<?endif?>

<?if( $_SESSION['is_mester'] ):?>
	<? $forbrug = $bruger->getSvendeForbrug( $faktura )?>
	<? unset( $total )?>
	  <br>
		<br>
	  <span class="sub_header">Dine brugers forbrug <?=$fakturaHead?></span>
		<!--Red dotted line-->
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td height="10"><img src="graphics/transp.gif" width="10" height="10"></td>
			</tr>
			<tr>
				<td height="1" background="graphics/red_dotted.gif"><img src="graphics/transp.gif" width="10" height="1"></td>
			</tr>
			<tr>
				<td height="10"><img src="graphics/transp.gif" width="10" height="10"></td>
			</tr>
		</table>
    <?if( count( $forbrug ) ):?>
		<!--End of red dotted line-->
	  <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabelPad">
	      <tr>
	        <td class="label" valign="top" style="padding-right:15px;">Bruger</td>
	        <td class="label" valign="top" style="padding-right:15px;">Titel</td>
	        <td class="label" valign="top">Betingelser</td>
	        <td class="label" valign="top">Dato</td>
	        <td class="label" valign="top" align="right">Pris</td>
	      </tr>
	     <tr>
				<td colspan="5" height="1"><!--<hr width="100%" size="1" color="#808080" noshade>--></td>
			</tr>
	    <?for( $i = 0; $i < count( $forbrug ); $i++ ):?>
	      <tr>
	        <td valign="top" style="padding-right:15px;"><a href="index.php?page=minside&section=brugerstyring&user=<?=$forbrug[$i]['id']?>"><?=( $forbrug[$i]['firmanavn2'] )? $forbrug[$i]['firmanavn2']:$forbrug[$i]['bruger_navn']?></a></td>
	        <td valign="top" style="padding-right:15px;"><a href="javascript:displaydoc(<?=$forbrug[$i]['publication_id']?>,1)"><?=$forbrug[$i]['title']?></a></td>
	        <td valign="top" width="100" NOWRAP><?=( $forbrug[$i]['abonament_periode'])?$forbrug[$i]['abonament_periode'].' Md. abonnement':'Enkelt visning'?></td>
	        <td valign="top" width="75" NOWRAP><?=$forbrug[$i]['readed']?></td>
	        <td align="right" valign="top" width="75" NOWRAP><?=( $forbrug[$i]['pris'] )? $forbrug[$i]['pris']:0?> kr.<? $total+= $forbrug[$i]['pris'] ?></td>
	      </tr>
	    <?endfor?>
	      <tr>
				<td colspan="5" height="1"><!--<hr width="100%" size="1" color="#808080" noshade>--></td>
			</tr>
	    <tr>
	    <td align="right" valign="top" colspan="3">&nbsp;</td>
	    <td valign="top" ><strong>I alt:</strong></td>
	    <td align="right" valign="top"><strong><?=( $total )? $total:0?> kr.</strong></td>
	  </tr>
	  </table>
  <?else:?>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tabelPad">
      <tr>
        <td class="label" valign="top" align="center">Ingen forbrug</td>
      </tr>
    </table>   
  <?endif?>
<?endif?>

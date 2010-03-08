<?php
  require_once("../util/bruger.php");
  $bruger = new bruger( $dba );
  $bruger_id = $_GET['bruger_id'];
  if( !$bruger_id ) $bruger_id = $_POST['bruger_id'];
  $bruger->setId( $bruger_id );
  $forbrug = $bruger->getForbrug();
  $props = $bruger->loadBruger();
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td colspan="5"><img src="../graphics/transp.gif" height="20"></td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="tabelText">
      Personlig forbrug
    </td>
  </tr>
  <tr>
    <td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr bgcolor="#e3e3e3">
    <td class="tabelText"> Titel </td>
    <td class="tabelText"> URL </td>
    <td class="tabelText"> Betingelserne </td>
    <td class="tabelText"> Pris </td>
  </tr>

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
    
  <?for( $i = 0; $i < count( $forbrug ); $i++ ):?>
    <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
      <td class="tabelText"><?=$forbrug[$i]['title']?></td>
      <td class="tabelText"><?=$forbrug[$i]['url']?></td>
      <td class="tabelText">
        <?=( $forbrug[$i]['abonament_periode'])?$forbrug[$i]['abonament_periode'].' md. abonament':'Enkelt visning'?>
      </td>
      <td class="tabelText" align="right"> 
        <?=( $forbrug[$i]['pris'] )? $forbrug[$i]['pris']:0?> kr.
        <? $total+= $forbrug[$i]['pris'] ?>
      </td>
    </tr>
  <?endfor?>
  <tr bgcolor="#e3e3e3">
    <td align="right" class="plain" colspan="2">&nbsp;</td>
    <td class="tabelText"><strong>I alt:</strong></td>
    <td align="right" class="tabelText"><?=( $total )? $total:0?> kr.</td>
  </tr>
</table>
<?if( !$props['parent'] ):?>
  <? unset( $total )?>
  <? $forbrug = $bruger->getSvendeForbrug()?>
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td colspan="5"><img src="../graphics/transp.gif" height="20"></td>
    </tr>
    <tr>
      <td colspan="5" align="center" class="tabelText">
        Under-brugernes forbrug
      </td>
    </tr>
    <tr>
      <td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
    </tr>
    <tr bgcolor="#e3e3e3">
      <td class="tabelText"> Bruger </td>
      <td class="tabelText"> Titel </td>
      <td class="tabelText"> URL </td>
      <td class="tabelText"> Betingelserne </td>
      <td class="tabelText"> Pris </td>
    </tr>
      <?for( $i = 0; $i < count( $forbrug ); $i++ ):?>
        <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
          <td> 
            <a href="index.php?brugerstyring=1&user=<?=$forbrug[$i]['id']?>"
            class="tabelText"><?=( $forbrug[$i]['navn'] )? $forbrug[$i]['navn']:$forbrug[$i]['bruger_navn']?></a> 
          </td>
          <td class="tabelText"><?=$forbrug[$i]['title']?></td>
          <td class="tabelText"><?=$forbrug[$i]['url']?></td>
          <td class="tabelText"> 
            <?=( $forbrug[$i]['abonament_periode'])?$forbrug[$i]['abonament_periode'].' md. abonament':'Enkelt visning'?>
          </td>
          <td class="tabelText" align="right"> 
            <?=( $forbrug[$i]['pris'] )? $forbrug[$i]['pris']:0?> kr.
            <? $total+= $forbrug[$i]['pris'] ?>
          </td>
        </tr>
      <?endfor?>
        <tr bgcolor="#e3e3e3">
          <td align="right" class="plain" colspan="3">&nbsp;</td>
          <td class="tabelText"><strong>I alt:</strong></td>
          <td align="right" class="tabelText"><?=( $total )? $total:0?> kr.</td>
        </tr>
      </table>
<?endif?>

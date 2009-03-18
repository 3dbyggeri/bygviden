<?php
require_once("admin/util/bruger.php");
if( !is_numeric( $_SESSION['bruger_id'] ) ) die('User id missing');
$user = $_GET['user'];

$bruger = new bruger( $dba );
$bruger->setId( $_SESSION['bruger_id'] );

if( $_GET['adduser'] ) $user = $bruger->addSvend();
if( $_GET['delete'] ) $bruger->deleteSvend( $_GET['delete'] );
if( $_POST['svend_edited'] )
{
  $extraklips = ( is_numeric( $_POST['klipkort_extra'] ) )? 
                $_POST['clipkort_amount'] + $_POST['klipkort_extra']:
                $_POST['clipkort_amount'];

  $bruger->updateSvend(  $_POST['user']
                        ,$_POST['bruger_navn'] 
                        ,$_POST['firmanavn2'] 
                        ,$_POST['email']
                        ,$_POST['password']
                        ,$_POST['password2']
                        ,$_POST['restricted_shop']
                        ,$extraklips
                      );
}

$svende = ( $user )?'':$bruger->getSvende();
?>
<span class="sub_header">Brugerstyring</span>
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
<?if( $user ):?>
  <? $props = $bruger->loadSvend( $user ) ?> 
  <form name="myform" method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <input type="hidden" name="section" value="<?=$section?>">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="user" value="<?=$user?>">
    <input type="hidden" name="clipkort_amount" value="<?=$props['clipkort_amount']?>">
  <table cellpadding="0" cellspacing="0" border="0">
    <tr style="padding-bottom:5px;">
      <td class="label">Login navn</td>
      <td><input class="login_input" type="text" name="bruger_navn" value="<?=$props['bruger_navn']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Fuld navn</td>
      <td><input class="login_input" type="text" name="firmanavn2" value="<?=$props['firmanavn2']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Mail</td>
      <td><input class="login_input" type="text" name="email" value="<?=$props['email']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Password</td>
      <td><input class="login_input" type="password" name="password"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Bekræft password</td>
      <td><input class="login_input" type="password" name="password2"></td>
    </tr>
    <tr style="padding-bottom:5px;">
			<td colspan="2" class="label">&nbsp;</td>
		</tr>
    <tr style="padding-bottom:5px;">
      <td colspan="2" class="label">
       <input type="radio" name="restricted_shop" value="n"
       <?=( $props['restricted_shop'] == 'n' )?'checked':''?>>
       Brugeren kan frit købe publikationer
     </td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td colspan="2" class="label">
       <input type="radio" name="restricted_shop" value="y"
       <?=( $props['restricted_shop'] != 'n' )?'checked':''?> >
       Brugeren kan kun købe ved at bruge et klippekort
     </td>
    </tr>
    <tr style="padding-bottom:5px;"> 
			<td colspan="2" class="label">&nbsp;</td>
		</tr>
    <tr style="padding-bottom:5px;">
      <td class="label" colspan="2">
      <?=( $props['clipkort_amount'] )? $props['clipkort_amount']:0 ?> kr.
      er disponibel på klippekortet
      </td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Tilføj beløb (kr.)</td>
      <td><input class="login_input" type="text" name="klipkort_extra"></td>
    </tr>
    <tr style="padding-bottom:5px;">
			<td colspan="2" class="label">&nbsp;</td>
		</tr>
    <tr>
      <td align="right" colspan="2">
        <span class="label"><?=$message?></span>&nbsp;&nbsp;
        <input type="button" class="login_button" value="Fortryd" 
          onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>&section=brugerstyring'">
        <input type="submit" class="login_button" value="Gem" name="svend_edited">
      </td>
    </tr>
  </table>
  </form>

<?else:?>
  <script>
    function slett( id, name )
    {
      if( confirm( 'Vil du slette brugeren '+ name +'?' ) )
      {
        document.location.href='<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>&section=brugerstyring&delete='+ id;
      }
    }
  </script>
          
  <?/*********************** SVENDE RECORDS **********************/?>
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <?for( $i = 0; $i < count( $svende ); $i++ ):?>
      <tr style="padding-bottom:4px;">
        <td>
          <a class="infolinks" 
            href="<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>&section=brugerstyring&user=<?=$svende[$i]['id']?>">
          <?=( $svende[$i]['firmanavn2'] )? $svende[$i]['firmanavn2']:$svende[$i]['bruger_navn']  ?>
          </a>
        </td>
        <td class="infolinks">
          <?if( $svende[$i]['restricted_shop'] =='n'):?>
            Frit forbrug
          <?else:?>
            Klippekort ( <?=($svende[$i]['clipkort_amount'])? $svende[$i]['clipkort_amount']:0?> kr. )
          <?endif?>
        </td>
        <td align="right">
          [<a class="infolinks"
            href="javascript:slett(<?=$svende[$i]['id']?>,'<?=$svende[$i]['firmanavn2']?>')">Slet bruger</a>]
          [<a class="infolinks" 
            href="<?=$_SERVER['PHP_SELF']?>?page=<?=$page?>&section=brugerstyring&user=<?=$svende[$i]['id']?>">Rediger bruger</a>]
        </td>
      </tr>
    <?endfor?>
  </table>
  <?if (count( $svende )):?>
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
	<?endif?>
  <table cellpadding="4" cellspacing="0" border="0" width="100%">
    <tr>
      <td align="right">
        <input type="button" 
        class="login_button" 
        value="Tilføj bruger" onclick="document.location.href='index.php?page=minside&section=brugerstyring&brugerstyring=1&adduser=1'">
      </td>
    </tr>
  </table>
  <br>
  <?/******************* END SVENDE RECORDS **********************/?>
<?endif?>

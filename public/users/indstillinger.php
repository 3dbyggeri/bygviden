<?php
require_once("admin/util/bruger.php");
if( !is_numeric( $_SESSION['bruger_id'] ) ) die('User id missing');

$bruger = new bruger( $dba );
$bruger->setId( $_SESSION['bruger_id'] );

if( $_POST['usersubmited'] )
{
  #$bruger->setBrugerNavn( $_POST['bruger_navn'] );
  $bruger->setMedlemsNr( $_POST['medlemsnr'] );
  $bruger->setActive( $_POST['active'] );
  $bruger->setFirmanavn1( $_POST['firmanavn1'] );
  $bruger->setFirmanavn2( $_POST['firmanavn2'] );
  $bruger->setFirmanavn3( $_POST['firmanavn3'] );
  $bruger->setGade( $_POST['gade'] );
  $bruger->setSted( $_POST['sted'] );
  $bruger->setPostnr( $_POST['postnr'] );
  $bruger->setCity( $_POST['city'] );
  $bruger->setLand( $_POST['land'] );
  $bruger->setEmail( $_POST['email'] );

  $full_name =  $_POST['firmanavn1'] .' '. $_POST['firmanavn2'] .' '. $_POST['firmanavn3'];
  $_SESSION['bruger_navn'] = ( trim( $full_name ) )? $full_name:$_POST['bruger_navn'];

  $message = 'Indstillinger er gemt ( '. date('H:i:s') .' )';

  if( $_POST['password1'] == $_POST['password2'] )
  {
    if( trim( $_POST['password1'] ) ) $bruger->setPassword( $_POST['password1'] );
  }
  else
  {
    $message = "Password er ikke ens";
  }
}
$props = $bruger->loadBruger();
?>
<form name="bruger" method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <input type="hidden" name="section" value="<?=$section?>">
  <input type="hidden" name="page" value="<?=$page?>">
  <input type="hidden" name="bruger_navn" value="<?=$_SESSION['bruger_navn']?>">
  <span class="sub_header">Personlige indstillinger</span>
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
  <style>
    .login_input{ width:200px }
  </style>

  <table cellpadding="0" cellspacing="0" border="0">
    <tr style="padding-bottom:5px;">
      <td class="label">Firmanavn:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="firmanavn1" value="<?=$props['firmanavn1']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Navn:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="firmanavn2" value="<?=$props['firmanavn2']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Titel:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="firmanavn3" value="<?=$props['firmanavn3']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Gade:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="gade" value="<?=$props['gade']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Sted:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="sted" value="<?=$props['sted']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Post nummer:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="postnr" value="<?=$props['postnr']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Land:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="land" value="<?=$props['land']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Email:</td>
      <td style="padding-left:5px;"><input class="login_input" type="text" name="email" value="<?=$props['email']?>"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Nyt password:</td>
      <td style="padding-left:5px;"><input class="login_input" type="password" name="password1"></td>
    </tr>
    <tr style="padding-bottom:5px;">
      <td class="label">Bekræft nyt password:</td>
      <td style="padding-left:5px;"><input class="login_input" type="password" name="password2"></td>
    </tr>
    <tr>
      <td align="right" colspan="2">
        <span class="label"><b><?=$message?></b></span>&nbsp;&nbsp;
        <input type="button" class="login_button" value="Fortryd" onclick="document.location.href='index.php'">
        <input type="submit" class="login_button" value="Gem" name="usersubmited">
      </td>
    </tr>
  </table>
</form>

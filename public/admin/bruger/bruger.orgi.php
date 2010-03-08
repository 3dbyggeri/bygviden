<?php
  require_once("../util/bruger.php");
  $bruger = new bruger( $dba );
  $bruger_id = $_GET['bruger_id'];
  if( !$bruger_id ) $bruger_id = $_POST['bruger_id'];
  $bruger->setId( $bruger_id );

  if( $_POST['usersubmited'] )
  {
    $bruger->setBrugerNavn( $_POST['bruger_navn'] );
    $bruger->setMedlemsNr( $_POST['medlemsnr'] );
    $bruger->setActive( $_POST['active'] );
    $bruger->setGratist( $_POST['gratist'] );
    $bruger->setFirma( $_POST['firma'] );
    $bruger->setNavn( $_POST['navn'] );
    $bruger->setTitel( $_POST['titel'] );
    $bruger->setGade( $_POST['gade'] );
    $bruger->setSted( $_POST['sted'] );
    $bruger->setPostnr( $_POST['postnr'] );
    $bruger->setCity( $_POST['city'] );
    $bruger->setLand( $_POST['land'] );
    $bruger->setTlf( $_POST['tlf'] );
    $bruger->setEmail( $_POST['email'] );
    $message = 'Gemt ( '. date('H:i:s') .' )';

    if( trim( $_POST['password'] ) ) $bruger->setPassword( $_POST['password'] );
    if( !$error ) die("<script>document.location.href='index.php'</script>");
  }
  $props = $bruger->loadBruger();
?>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="bruger_id" value="<?=$bruger_id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td align="center" class="alert_message">&nbsp;</td>
  </tr> 
    <td align="center">
        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="color1">
            <tr>
                <td class="tdpadtext">Login navn</td>
           </tr>
			     <tr>
                <td class="tdpadtext"><input type="text" class="input" name="bruger_navn" value="<?=$props['bruger_navn']?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Firmanavn 1</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="firma" value="<?=$props['firma']?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Firmanavn 2</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="navn" value="<?=$props['navn']?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Firmanavn 3</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="titel" value="<?=$props['titel']?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Gade</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="gade" value="<?=$props['gade']?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Sted</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="sted" value="<?=$props['sted']?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Postnummer</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="postnr" value="<?=$props['postnr']?>"></td>
           </tr>

		       <tr>
                <td class="tdpadtext">By</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="city" value="<?=$props['city']?>"></td>
           </tr>

		       <tr>
                <td class="tdpadtext">Land</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="land" value="<?=$props['land']?>"></td>
           </tr>

		        <tr>
                <td class="tdpadtext" colspan="2">
                  <input type="checkbox" name="active" <?=($props['active'] == 'n' )?'checked':''?>> Brugeren er ikke aktiv
                </td>
           </tr>
		        <tr>
                <td class="tdpadtext" colspan="2">
                  <input type="checkbox" name="gratist" <?=($props['gratist'] == 'y' )?'checked':''?>> Brugeren er en gratist 
                </td>
           </tr>

		        <tr>
                <td class="tdpadtext">E-mail</td>
           </tr>
            <tr>
                <td class="tdpadtext" style="padding-bottom: 20px;"><input type="text" class="input" name="email" value="<?=$props['email']?>"></td>
           </tr>

		        <tr>
                <td class="tdpadtext">Password</td>
           </tr>
            <tr>
               <td class="tdpadtext"><input type="text" class="input" name="password" value="<?=$props['password']?>"></td>
           </tr>
		        <tr>
                <td class="tdpadtext" colspan="2">&nbsp;</td>
           </tr>
        </table>
    </td>
  <tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="right" width="310" style="padding-top: 10px;">
        <span class="alert_message" style="font-size:10px"><?=$message?>&nbsp;</span>
        <input type="button" value="Fortryd" onclick="document.location.href='index.php'" class="knap"> 
	      <input type="submit" value="Gemt" name="usersubmited" class="knap"></td>
			</tr>
		</table>
	</td>
  </tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  <tr>
</table>
</form>

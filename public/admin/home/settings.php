<?
if( $usersubmited || $_POST["usersubmited"] )
{
  if( !$name )         $name = $_POST["name"];
  if( !$full_name )    $full_name = $_POST["full_name"];
  if( !$password )     $password = $_POST["password"];
  if( !$confirm_password ) $confirm_password = $_POST["confirm_password"];
  if( !$mail )         $mail = $_POST["mail"];


  if( $password == $confirm_password )
  {
    $user->setName( $name );
    $user->setFull_name( $full_name );
    $user->setMail( $mail );
    if( trim( $password ) ) $user->setPassword( $password );
    $message = "Dinne ændringer er blevet gemt";
  }
  else
  {
    $message = "Dinne password stemmer ikke ens";
  }
}
?>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">Redigere dinne personlige indstillinger</td>
  </tr> 
  <tr>
    <td align="center" class="alert_message"><?=$message?>&nbsp;</td>
  </tr> 
    <td align="center">
        <table width="100%" cellpadding="3" cellspacing="0" border="0" class="color1">
            <tr>
                <td class="tdpadtext">Login navn</td>
           </tr>
			     <tr>
                <td class="tdpadtext"><input type="text" class="input" name="name" value="<?=$user->name?>"></td>
           </tr>
		       <tr>
                <td class="tdpadtext">Fulde navn</td>
           </tr>
            <tr>
                <td class="tdpadtext"><input type="text" class="input" name="full_name" value="<?=$user->full_name?>"></td>
           </tr>
		        <tr>
                <td class="tdpadtext">E-mail</td>
           </tr>
            <tr>
                <td class="tdpadtext" style="padding-bottom: 20px;"><input type="text" class="input" name="mail" value="<?=$user->mail?>"></td>
           </tr>
		        <tr>
                <td class="tdpadtext">Ændre password</td>
           </tr>
            <tr>
               <td class="tdpadtext"><input type="password" class="input" name="password"></td>
           </tr>
		        <tr>
                <td class="tdpadtext">Bekræft password</td>
           </tr>
            <tr>
               <td class="tdpadtext" style="padding-bottom: 10px;"><input type="password" class="input" name="confirm_password"></td>
           </tr>
        </table>
    </td>
  <tr>
  <tr>
  	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="310">
			<tr>
				<td align="right" width="310" style="padding-top: 10px;"><input type="button" value="Fortryd" onclick="document.location.href='<?=$PHP_SELF?>'" class="knap"> 
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

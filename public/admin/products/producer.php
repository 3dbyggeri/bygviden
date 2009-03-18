<?php
$pcr_id = $_GET['pcr_id'];
if(!$pcr_id) $pcr_id= $_POST['pcr_id'];
$producenter->setProducent($pcr_id);

if($_POST['save'])
{
  $producenter->setName( $_POST['name'] );
  $producenter->setUserName($_POST['p_user_name']);
  $producenter->setUserPassword($_POST['p_user_password']);
  $producenter->setHomepage( $_POST['home_page'] );
  $producenter->setLogo_url( $_POST['logo_url'] );
  $producenter->setAdresse( $_POST['adresse'] );
  $producenter->setCVR( $_POST['CVR'] );
  $producenter->setTelefon( $_POST['telefon'] );
  $producenter->setFax( $_POST['fax'] );
  $producenter->setMail( $_POST['mail'] );
  $producenter->setAdminMail( $_POST['admin_mail'] );
  $producenter->setPublish( $_POST['publish'] );
  $producenter->setDescription( $_POST['description'] );
  $producenter->setObservation( $_POST['observation'] );
  $producenter->admin_name = $_POST['admin_name'];
  $producenter->admin_telefon = $_POST['admin_telefon'];
  $producenter->advertise_deal = $_POST['advertise_deal'];
  if($producent['advertise_deal'] != $_POST['advertise_deal']) $producenter->updateDeal = true;
  $producenter->updateProducent();
  $message = 'Your changes has been saved';
}

$producent = $producenter->loadProducer($pcr_id);
?>
<form name="my_form" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="pcr_id" value="<?=$pcr_id?>">
<table id="settings" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr> 
      <td><img src="../graphics/transp.gif" height="20"></td>
    </tr>
    <tr>
        <td><h2>Firma profil</h2></td>
    </tr>
    <tr> 
      <td>
          <table class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
            <tr >
              <td class="tdpadtext">Navn</td>
              <td>
                <input type="text" size="53" name="name" class="input" value="<?=$producent['name']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">Adresse</td>
              <td>
                <input type="text" size="53" name="adresse" class="input" value="<?=$producent['adresse']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">CVR nr.</td>
              <td>
                <input type="text" size="53" name="CVR" class="input" value="<?=$producent['CVR']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Telefon</td>
              <td>
                <input type="text" size="53" name="telefon" class="input" value="<?=$producent['telefon']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Fax</td>
              <td>
                <input type="text" size="53" name="fax" class="input" value="<?=$producent['fax']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Mail</td>
              <td>
                <input type="text" size="53" name="mail" class="input" value="<?=$producent['mail']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">Web adresse til hjemmeside</td>
              <td>
                <input type="text" size="53" name="home_page" class="input" value="<?=$producent['home_page']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">Web adresse til logo</td>
              <td>
                <input type="text" size="53" name="logo_url" class="input" value="<?=$producent['logo_url']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext" valign="top">Beskrivelse</td>
              <td>
                <textarea name="description" rows="4" cols="53" class="input" wrap="virtual"><?=$producent['description']?></textarea>
              </td>
            </tr>
            <tr>
              <td class="tdpadtext" valign="top">Annonc&oslash;rer aftale</td>
              <td>
                <select name="advertise_deal">
                    <option value="none" <?=($producent['advertise_deal']=='none')?'selected="selected"':''?>>Ingen</option>
                    <option value="trial" <?=($producent['advertise_deal']=='trial')?'selected="selected"':''?>>Pr&oslash;vetid</option>
                    <option value="active" <?=($producent['advertise_deal']=='active')?'selected="selected"':''?>>Forhandlet</option>
                </select>
                <?
                    if($producent['advertise_deal'] =='trial' || $producent['advertise_deal']=='active') 
                    {
                        echo '(dage:'.$producent['signup'].')';
                    }
                ?>
              </td>
            </tr>
            <tr >
              <td class="tdpadtext" valign="top">Status</td>
              <td>
                <input type="checkbox" name="publish" value="y" <?=($producent['publish'] == 'y')? 'checked':''?>> 
                publiceret 
              </td>
            </tr>
            <!--
            <tr>
              <td class="tdpadtext" valign="top">Bem&aelig;rkninger fra Dansk Byggeri</td>
              <td>
                <textarea name="observation" rows="4" cols="53" class="input" wrap="virtual"><?=$producent['observation']?></textarea>
              </td>
            </tr>
            -->

            <tr style="background-color:#fff">
                <td colspan="2">
                    <br />
                    <h2>Kontakt person</h2>
                </td>
            </tr>
            <tr>
              <td class="tdpadtext">Navn</td>
              <td>
                <input type="text" size="53" name="admin_name" class="input" value="<?=$producent['admin_name']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">Login navn</td>
              <td>
                <input type="text" size="53" name="p_user_name" class="input" value="<?=$producent['user_name']?>">
              </td>
            </tr>
            <tr >
              <td class="tdpadtext">Password</td>
              <td>
                <input type="text" size="53" name="p_user_password" class="input" value="<?=$producent['user_password']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">Email</td>
              <td>
                <input type="text" size="53" name="admin_mail" class="input" value="<?=$producent['admin_mail']?>">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">Telefon</td>
              <td>
                <input type="text" size="53" name="admin_telefon" class="input" value="<?=$producent['admin_telefon']?>">
              </td>
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
            <td class="tdpadtext">&nbsp;</td>
            <td  align="right" nowrap>
              <input type="button" onClick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=producers'" value="Fortryd" name="cancel" class="knap" style="width:150px"> 
              <input type="submit" value="Gemt" name="save" class="knap" style="width:150px"> 
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

<?
if($_POST && !$_POST['dropProducent'])
{
  $kilde->setUrl($_POST['kilde_url']);
  $kilde->setForlag($_POST['forlag_url']);
  $kilde->setLogo($_POST['logo_url']);
  $kilde->setDescription($_POST['description']);
  $kilde->setObservation($_POST['observation']);
  $kilde->setAdresse($_POST['adresse']);
  $kilde->setTelefon($_POST['telefon']);
  $kilde->setFax($_POST['fax']);
  $kilde->setMail($_POST['mail']);
  $kilde->setDigitalUdgave($_POST['digital_udgave']);
  $kilde->setCustomSummary($_POST['custom_summary']);
  $kilde->setLogin($_POST['log_in'], $_POST['log_name'],$_POST['log_password']);
  $kilde->setCrawlingDepth($_POST['crawling_depth']);
  $kilde->setCrawlingCuantitie($_POST['crawling_cuantitie']);
  $kilde->setIndholdsfortegnelse($_POST['indholdsfortegnelse']);
  $kilde->setForbiddenWords($_POST['forbidden_words']);
  $kilde->setRequiredWords($_POST['required_words']);
  $kilde->setDB($_POST['db']);
  $kilde->setBetegnelse($_POST['betegnelse']);
  $kilde->setBrancheRelevans($_POST['brancher']);

  $message = "Your changes has been saved";
}

$kilde->loadProperties();

if( $kilde->type == 'leverandor' )
{
    require_once('../util/products.php');
    $producenter = new products( $dba );

    if( $_POST['prod_id'] ) $producenter->setKildeForProducent($id,$_POST['prod_id']);
    if( $_POST['dropProducent'] ) $producenter->removeKildeForProducent($_POST['dropProducent']);
    $producent_properties = $producenter->getProducentIdForKilde($id);
}
?>
<script>
  function chooseProducent(kilde_id)
  {
    props = 'scrollbars=yes,toolbar=no,status=no,menubar=no,location=no,directories=no,resizable=yes,width=500,height=600';
    w = window.open('../products/selectProducer.php?id='+ kilde_id,'chooseProducer',props);
    w.focus();
  }
  function selectedProducent(prod_id)
  {
    document.tree.prod_id.value = prod_id;
    document.tree.submit();
  }
  function dropping(prod_id)
  {
    document.tree.dropProducent.value = prod_id;
    document.tree.submit();
  }
  function editThis(field)
  {
    var fieldName = 'document.tree.'+ field;
    props = 'scrollbars=no,toolbar=no,status=no,menubar=no,location=no,directories=no,resizable=yes,width=580,height=360';
    w = window.open('editor/edit.php?formfield='+ fieldName +'&fieldID='+ field,'editor',props);
    w.focus();
  }
  function toggleLogin()
  {
    document.tree.toggle_login.value = 1;
    document.tree.submit();
  }
</script>
<form name="tree" action="index.php" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="prod_id">
<input type="hidden" name="dropProducent">
<style>
  #bruger_rabat_table td, input {
    font-family:verdana,sans-serif;
    font-size:11px;
  }
  #stamdata td
  {
    border-bottom:1px solid #FFF6C6;
    font-size:11px;
  }
  #head td
  {
    border:none;
  }
</style>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="header">
            <?=$kilde->name?>  
            <div class="alert_message" style="margin-left:-10px"><?=$message?></div>
          </td>
          <td align="right">
            <?if( $kilde->type == 'publikation' ):?>
              <!-- <input onclick="window.open('log.php?id=<?= $id?>')" type="button" class="knap" size="30px" value="Spider log"> 
              &nbsp;&nbsp; -->
            <?endif?>

            <?if( $kilde->type == 'leverandor'):?>
                <?if($producent_properties ):?>
                    <input type="button" value="Drop forbindelse til producenten" 
                      onclick="dropping(<?=$producent_properties['id']?>)" style="width:225px;margin-right:10px" class="knap">
                <?else:?>
                    <input type="button" value="Hent stamdata hos producenten" 
                      class="knap" style="width:225px;margin-right:10px" onclick="chooseProducent(<?=$id?>)"> 
                <?endif?>

            <?endif?>

          </td>
       </tr>
      </table>
    </td>
	</tr>
  <tr> 
    <td><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr> 
    <td>
        <table id="stamdata" class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">
            <tr><td colspan="2">&nbsp;</td></tr>

          <?if(!$producent_properties ):?>

            <tr >
              <td>
               Element betegnelse i frontend
              </td>
              <td>
                <input type="text" size="53" name="betegnelse" class="input" value="<?=$kilde->betegnelse?>">
              </td>
            </tr>

            <?if( $kilde->type == 'publikation' ):?>
              <tr >
                <td colspan="2">
                  <input type="checkbox" name="digital_udgave" <?=( $kilde->digital_udgave== 'n' )?'checked':''?> >
                  Publikationen findes ikke i digital udgave
                </td>
              </tr>
              <!-- <tr >
                <td colspan="2">
                  <input type="checkbox" name="custom_summary" <?=( $kilde->custom_summary == 'y' )?'checked':''?> >
                  Brug beskrivelsen som resume
                </td>
              </tr> -->
              <tr>
                <td colspan="2">
                  <input type="checkbox" name="indholdsfortegnelse" <?=( $kilde->indholdsfortegnelse == 'y' )?'checked':''?> >
                  Listes under tilgængelige publikationer ("biblioteket")
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <input type="checkbox" onclick="toggleLogin()" name="log_in" <?=( $kilde->log_in== 'y' )?'checked':''?> >
                  Password beskyttet
                </td>
              </tr>
              <?if( $kilde->log_in == 'y' ):?>
                <tr>
                  <td style="padding-left:30px">
                    Log in navn
                  </td>
                  <td >
                    <input type="text" size="53" name="log_name" class="input" value="<?=$kilde->log_name?>">
                  </td>
                </tr>
                <tr >
                  <td style="padding-left:30px">
                    Log in password
                  </td>
                  <td>
                    <input type="text" size="53" name="log_password" class="input" value="<?=$kilde->log_password?>">
                  </td>
                </tr>
              <?endif?>
              <!-- <tr >
                <td>
                  Ord som ikke må optræde i publikationens web adresse
                </td>
                <td>
                  <input type="text" size="53" name="forbidden_words" class="input" value="<?=$kilde->forbidden_words?>">
                </td>
              </tr>
              <tr >
                <td >
                  Ord som skal optræde i publikationens web adresse
                </td>
                <td>
                  <input type="text" size="53" name="required_words" class="input" value="<?=$kilde->required_words?>">
                </td>
              </tr>
              <tr >
                <td>
                  Maximun antal nivoer som spideren skal følge
                </td>
                <td>
                  <input class="input" type="text" 
                    name="crawling_depth" value="<?=$kilde->crawling_depth?>">
                </td>
              </tr>
              <tr >
                <td>
                  Maximun antal dokumenter som spideren skal hente 
                </td>
                <td>
                  <input class="input" type="text" 
                    name="crawling_cuantitie" value="<?=$kilde->crawling_cuantitie?>">
                </td>
              </tr>
              <tr >
                <td>
                  Database
                </td>
                <td>
                  <? $dbs = array('diverse'=>'Byggetekniske vejledninger',
                                  'producenter'=>'Producenter',
                                  'specifik_tek'=>'Tekniske anvisninger / branchebeskrivelser',
                                  'normkrav'=>'Normer',
                                  'lovkrav'=>'Lovkrav',
                                  'erfa'=>'Byg-erfa',
                                  'dyrup'=>'Dyrup',
                                  'beckers'=>'Bekers',
                                  'flygger'=>'Flygger',
                                  'hygros'=>'Hygros',
                                  'sadolin'=>'Sadolin',
                                  'sigma'=>'Sigma',
                                  'MBK'=>'MBK'
                                  ); ?>
                  <select name="db">
                    <?foreach( $dbs as $key=>$value ):?>
                      <option value="<?=$key?>" <?=( $kilde->db == $key )?'selected':''?>><?=$value?></option>
                    <?endforeach?>
                  </select>
                </td>
              </tr> -->
            <?endif?>
            <?if( $kilde->type == 'leverandor' ):?>
              <tr >
                <td>
                  Adressse
                </td>
                <td>
                  <input type="text" size="53" name="adresse" class="input" value="<?=$kilde->adresse?>">
                </td>
              </tr>
              <tr >
                <td>
                  Telefon
                </td>
                <td>
                  <input type="text" size="53" name="telefon" class="input" value="<?=$kilde->telefon?>">
                </td>
              </tr>
              <tr >
                <td>
                  Fax
                </td>
                <td>
                  <input type="text" size="53" name="fax" class="input" value="<?=$kilde->fax?>">
                </td>
              </tr>
              <tr >
                <td>
                  Mail
                </td>
                <td>
                  <input type="text" size="53" name="mail" class="input" value="<?=$kilde->mail?>">
                </td>
              </tr>
            <?endif?>
            <tr>
              <td>
                Web adresse til kilden 
              </td>
              <td>
                <input type="text" size="53" name="kilde_url" class="input" value="<?=$kilde->kilde_url?>">
              </td>
            </tr>
            <tr>
              <td>
                Web adresse til forlag 
              </td>
              <td>
                <input type="text" size="53" name="forlag_url" class="input" value="<?=$kilde->forlag_url?>">
              </td>
            </tr>
            <?if( $kilde->type == 'kategori' ):?>
              <tr>
                <td colspan="2">
                  <input type="checkbox" name="indholdsfortegnelse" <?=( $kilde->indholdsfortegnelse == 'y' )?'checked':''?> >
                  Samlet publikation 
                </td>
              </tr>
            <?endif?>
            <tr>
              <td valign="top">
                Beskrivelse
              </td>
              <td>
                <input type="button" value="Redigere" class="knap" 
                  onclick="editThis('description')">
                <textarea style="display:none;" 
                  name="description"><?=strip_tags($kilde->description,'<br><b><a>')?></textarea>
                <p id="description_paragraph"><?=strip_tags($kilde->description,'<br><b><a>')?></p>
              </td>
            </tr>
            <tr>
              <td valign="top">
                Bemærkninger fra Dansk Byggeri
              </td>
              <td>
                <input type="button" value="Redigere" 
                  onclick="editThis('observation')"
                  class="knap">
                <textarea style="display:none;" name="observation"><?=$kilde->observation?></textarea>
                <p id="observation_paragraph"><?=$kilde->observation?></p>
              </td>
            </tr>
          <?endif?>

          <?if( $producent_properties ):?>
            <style>
              .simple { font-size:10px;font-weight:normal;color:#333; }
            </style>
            <tr>
              <td class="tdpadtext">
                 Producent navn: 
                 <span class="simple"><?=$producent_properties['name']?> 
              </td>
            </tr>
            <tr>
              <td valign="top" class="tdpadtext">
                 Beskrivelse: 
                 <span class="simple"><?=$producent_properties['description']?> 
              </td>
            </tr>
            <tr>
              <td valign="top" class="tdpadtext">
                 Website: 
                 <span class="simple"><?=$producent_properties['home_page']?> 
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">
                 Adresse: 
                 <span class="simple"><?=$producent_properties['adresse']?> 
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">
                 Telefon: 
                 <span class="simple"><?=$producent_properties['telefon']?> 
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">
                 Fax: 
                 <span class="simple"><?=$producent_properties['fax']?> 
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">
                 Mail: 
                 <span class="simple"><?=$producent_properties['mail']?> 
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">
                 Administrator mail: 
                 <span class="simple"><?=$producent_properties['admin_mail']?> 
              </td>
            </tr>
            
          <?endif?>

          <!-- /STAMDATA -->

          <!--  BRANCHE RELEVANS -->
          <?if( $kilde->type == 'publikation' ):?>
          <?$brancher = $kilde->getBrancheRelevans()?>
            <!-- <tr class="color2">
              <td colspan="2">
                Branche relevans
              </td>
            </tr> -->
            <?for( $i = 0; $i < count( $brancher); $i++ ):?>
              <!-- <tr>
                <td colspan="2">
                  <input type="checkbox" name="brancher[]" value="<?=$brancher[$i]['id']?>" <?=$brancher[$i]['checked']?>>
                  <?=$brancher[$i]['label']?>
                </td>
              </tr> -->
            <?endfor?>
          <?endif?>
          <!-- / BRANCHE RELEVANS -->
           <tr><td colspan="2" style="padding-left: 10px; padding-bottom: 10px;">&nbsp;</td></tr>
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
            <?if($producent_properties):?> 
                <input type="button" value="Redigere stamdata hos producenten" 
                  onclick="document.location.href='../products/index.php?pane=producer&pcr_id=<?=$producent_properties['id']?>'" 
                  style="width:250px" class="knap">
            <?else:?>
              <input type="submit" value="Fortryd" name="cancel" class="knap">
              <input type="submit" value="Gemt" name="editproperties" class="knap"> 
            <?endif?>
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>

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
</script>
<input type="hidden" name="prod_id">
<input type="hidden" name="dropProducent">
<? if( $_GET['branche_relevans_open'] ) $_SESSION['branche_relevans_open'] =  $_GET['branche_relevans_open'];?>
<? if( $_GET['urls_open'] ) $_SESSION['urls_open'] =  $_GET['urls_open'];?>
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
            <?=$kilde->name?>  <span class="alert_message"><?=$message?></span>
          </td>
          <td align="right">
            <?if( $kilde->type == 'publikation' ):?>
              <input onclick="window.open('log.php?id=<?=$id?>')" type="button" class="knap" size="30px" value="Spider log"> 
              &nbsp;&nbsp;
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
        <?if( $kilde->type == 'leverandor' ):?>

          <?
            require_once('../util/products.php');
            $producenter = new products( $dba );

            if( $_POST['prod_id'] ) $producenter->setKildeForProducent($id,$_POST['prod_id']);
            if( $_POST['dropProducent'] ) $producenter->removeKildeForProducent($_POST['dropProducent']);
            //check if there is a link between this kilde and the producer
            $producent_properties = $producenter->getProducentIdForKilde($id);
          ?>
        <?endif?>
        <table id="stamdata" class="color1" width="100%" cellpadding="3" cellspacing="0" border="0">

          <!-- STAMDATA -->
          <tr class="color2">
            <td colspan="2">
              
              <table id="head" width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  <td class="plainText">
                    <a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&urls_open=<?=($_SESSION['urls_open'] == 'y')?'n':'y'?>"><img 
                      src="../graphics/<?=($_SESSION['urls_open'] == 'y' )?'downnode':'upnode'?>.gif" border="0"></a>
                    Stamdata
                    
                  </td>
                  <td align="right" class="plainText">
                    &nbsp;
                    <?if( $kilde->type == 'leverandor' && !$producent_properties ):?>
                      <input type="button" value="Hent stamdata hos producenten" 
                        class="knap" style="width:250px" onclick="chooseProducent(<?=$id?>)"> 
                    <?else:?>
                      (Hentes hos producentets indstillingerne)
                    <?endif?>
                  </td>
                </tr>
              </table>



            </td>
          </tr>
          <?if( $_SESSION['urls_open'] == 'y' && !$producent_properties ):?>

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
              <tr >
                <td colspan="2">
                  <input type="checkbox" name="custom_summary" <?=( $kilde->custom_summary == 'y' )?'checked':''?> >
                  Brug beskrivelsen som resume
                </td>
              </tr>
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
              <tr >
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
              </tr>
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

          <?if( $_SESSION['urls_open'] == 'y' && $producent_properties ):?>
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
            <tr>
              <td class="tdpadtext">
                <input type="button" value="Go til producent administrations side" 
                  onclick="document.location.href='../products/index.php?pane=producer&pcr_id=<?=$producent_properties['id']?>'" 
                  style="width:250px" class="knap">
              </td>
            </tr>
            <tr>
              <td class="tdpadtext">
                <input type="button" value="Drop forbindelse til producenten" 
                  onclick="dropping(<?=$producent_properties['id']?>)" style="width:250px" class="knap">
              </td>
            </tr>
            
          <?endif?>

          <!-- /STAMDATA -->

          <!--  BRANCHE RELEVANS -->
          <?if( $kilde->type == 'publikation' ):?>
          <?$brancher = $kilde->getBrancheRelevans()?>
            <tr><td colspan="2">&nbsp;</td></tr>
            <tr class="color2">
              <td colspan="2">
                <a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&branche_relevans_open=<?=($_SESSION['branche_relevans_open'] == 'y')?'n':'y'?>"><img 
                src="../graphics/<?=($_SESSION['branche_relevans_open'] == 'y' )?'downnode':'upnode'?>.gif" border="0"></a>
                Branche relevans
              </td>
            </tr>
            <?if($_SESSION['branche_relevans_open'] == 'y' ):?>
            <?for( $i = 0; $i < count( $brancher); $i++ ):?>
              <tr>
                <td colspan="2">
                  <input type="checkbox" name="brancher[]" value="<?=$brancher[$i]['id']?>" <?=$brancher[$i]['checked']?>>
                  <?=$brancher[$i]['label']?>
                </td>
              </tr>
            <?endfor?>
            <?endif?>
          <?endif?>
          <!-- / BRANCHE RELEVANS -->
          

          <!-- STATUS FOR KILDEN -->
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr class="color2">
            <td colspan="2">
              <a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&publish_open=<?=($kilde->publish_open == 'y')?'n':'y'?>"><img 
                src="../graphics/<?=($kilde->publish_open == 'y' )?'downnode':'upnode'?>.gif" border="0"></a>
              Status for kilden
            </td>
          </tr>
          <?if( $kilde->publish_open == 'y' ):?>
          <tr>
            <td>
              Spideren er <?=( $kilde->crawling =='y')?" ":"ikke"?> aktiv 
            </td>
            <td valign="top">
              <input type="button" class="stor_knap" style="width:250px" 
                onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&spider_state=<?=$kilde->crawling?>'"  
                value="<?=( $kilde->crawling == 'y' )?"Stop spideren":"Start spideren"?>" >
            </td>
          </tr>
          <tr>
            <td>
              Kilden er <?=( $kilde->publish )?" ":" ikke "?> publiceret 
            </td>
            <td>
              <input type="button" class="stor_knap" style="width:250px" 
              onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&publish_state=<?=($kilde->publish)?'n':'y'?>'" 
              value="<?=( $kilde->publish )?"Nedtag kilden":"Publicere kilden"?>" >
            </td>
          </tr>
          <?if( !$kilde->publish ):?>
            <tr>
              <td>
                <input type="checkbox" name="publishSchedule" class="plainText" <?=( $kilde->publishDate["y"] )?"checked":""?>>
                Publicere kilden den [ d.m.å ] 
              </td>
              <td>
                <?=$publishDate->render()?>
              </td>
            </tr>
          <?endif?>
          <tr>
            <td>
              <input type="checkbox" name="unpublishSchedule" class="plainText" <?=( $kilde->unpublishDate["y"] )?"checked":""?>>
               Nedtage kilden den [ d.m.å ] 
            </td>
            <td>
              <?=$unpublishDate->render()?>
            </td>
          </tr>
          <?endif?>
          <!-- /STATUS FOR KILDEN -->

          <!-- BRUGSBETINGELSERNE -->
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr class="color2">
            <td colspan="2">
              <a href="<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&brugs_open=<?=($kilde->brugs_open == 'y')?'n':'y'?>"><img 
                src="../graphics/<?=($kilde->brugs_open == 'y' )?'downnode':'upnode'?>.gif" border="0"></a>
                Brugsbetingelserne
            </td>
          </tr>
          <?if( $kilde->brugs_open == 'y' ):?>
          <tr>
            <td colspan="2">
              <select name="brugsbetingelser" class="select_list">

                <option value="fuldtekst_alle" <?=( $kilde->brugsbetingelser == 'fuldtekst_alle')?'selected':''?>>
                  Fuldtekst for alle
                </option>
                <option value="fuldtekst_medlemmer" <?=( $kilde->brugsbetingelser == 'fuldtekst_medlemmer')?'selected':''?>>
                  Fuldtekst kun for medlemmer
                </option>
                <option value="resume_alle" <?=( $kilde->brugsbetingelser == 'resume_alle')?'selected':''?>>
                  Kun resume for alle
                </option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">
                <input type="button" class="stor_knap" style="width:250px" 
                onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&overrule_betaling=<?=($kilde->overrule_betaling=='y')?'n':'y'?>'"
                value="<?=( $kilde->overrule_betaling != 'y' )?"Specificere betalings model":"Ignorer betalings model"?>" >
            </td>
          </tr>
          <?if($kilde->overrule_betaling == 'y' ):?>
          <tr>
            <td colspan="2">
              <select class="select_list" name="betaling" onchange="toggleBetaling('<?=$kilde->betaling?>')">
                <option value="n" <?=( $kilde->betaling =='n')?'selected':''?>>Gratis</option>
                <option value="y" <?=( $kilde->betaling =='y')?'selected':''?>>Betaling</option>

              </select>
            </td>
          </tr>
          <!--kun vis der er betaling-->
          <?if( $kilde->betaling == 'y' ):?>
            <tr>
              <td colspan="2">
                <input type="checkbox" name="enkelt_betaling" <?=( $kilde->enkelt_betaling =='y')?'checked':''?>> 
                Enkelt visning pris 
                &nbsp;
                <input class="input" style="width:50px" name="enkelt_pris" value="<?=$kilde->enkelt_pris?>"> 
                kr.
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="checkbox" name="abonament_betaling" <?=($kilde->abonament_betaling=='y')?'checked':''?>> 
                Abonament pris for
                <select class="select_list_small" name="abonament_periode">
                  <?for( $i = 1; $i < 25; $i++ ):?>
                    <option value="<?=$i?>" <?=( $kilde->abonament_periode == $i )?'selected':''?>><?=$i?> Måneder</option>
                  <?endfor?>
                </select>

                &nbsp;<input class="input" 
                  style="width:50px" name="abonament_pris" 
                  value="<?=$kilde->abonament_pris?>"> kr.
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <script>
                  function toggleUserModel()
                  {
                    var el = document.getElementById('bruger_rabat_table');
                    el.style.display =(el.style.display=='block')? 'none':'block';
                  }
                </script>
                <input type="checkbox" onclick="toggleUserModel()" name="bruger_rabat" <?=( $kilde->bruger_rabat =='y')?'checked':''?>> 
                Bruger rabat ordning
                <br><br>
                <?
                  $bruger_model = array();
                  $style="style='display:none'";
                  if($kilde->bruger_rabat == 'y') 
                  {
                    $bruger_model = $kilde->getBrugerData();
                    $style="style='display:block'";
                  }
                ?>

                <table id="bruger_rabat_table" cellpadding="5" cellspacing="0" border="0" <?=$style?>>
                  <?foreach($kilde->bruger_data_model as $k=>$v):?>
                    <tr>
                      <td><?=$v?></td>
                      <td><input type="text" name="<?=$k?>" value="<?=$bruger_model[$k]?>"> kr.</td>
                    </tr>
                  <?endforeach?>
                </table>
              </td>
            </tr>
          <?endif?>
          <?endif?>
          <?endif?>
           <tr>
                <td colspan="2" style="padding-left: 10px; padding-bottom: 10px;">&nbsp;</td>
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
              <input type="submit" value="Cancel" name="cancel" class="knap">
              <input type="submit" value="Save" name="editproperties" class="knap"> 
            </td>
           </tr>
				</table>
      </td>
    </tr>
    <tr> 
        <td ><img src="../graphics/transp.gif" height="15"></td>
    <tr>
  </table>

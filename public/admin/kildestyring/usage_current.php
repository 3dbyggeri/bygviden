<?
if( $_GET['publish_state'] ) $kilde->toggle_publish( $_GET['publish_state'] );
if( !$spider_state ) $spider_state = $_GET['spider_state'];
if( $spider_state ) $kilde->toggle_spider($spider_state);
if( $_GET['betaling'] ) $kilde->toggle_payment( $_GET['betaling'] );
if( $_GET['overrule_betaling'] ) $kilde->overrule_betaling( $_GET['overrule_betaling'] );

if($_POST)
{
    //the order of this statements is important
    if( $_POST['publishSchedule']   ) 
    {
        $kilde->setPublishDate( $_POST['day_publish'], $_POST['month_publish'], $_POST['year_publish'] );
    }
    else { $kilde->setPublishDate(); }

    if( $_POST['unpublishSchedule'] ) 
    {
        $kilde->setUnPublishDate( $_POST['day_unpublish'], $_POST['month_unpublish'], $_POST['$year_unpublish'] );
    }
    else { $kilde->setUnPublishDate(); }

    $kilde->updatePayment();
}

$kilde->loadProperties();

$publishDate = new date_widget("publish");
$udgivelses_dato = new date_widget('udgivelse');
$revisions_dato  = new date_widget('revision');

if( $kilde->publishDate["y"] ) 
{
    $publishDate->setDate( $kilde->publishDate["d"], 
                           $kilde->publishDate["m"],
                           $kilde->publishDate["y"] );
}

$unpublishDate = new date_widget("unpublish");

if( $kilde->unpublishDate["y"] ) 
{
    $unpublishDate->setDate( $kilde->unpublishDate["d"],
                             $kilde->unpublishDate["m"],
                             $kilde->unpublishDate["y"] );
}
?>
<form name="myform" action="index.php" method="post">
<input type="hidden" name="id" value="<?=$id?>">
<script>
      function toggleBetaling(list) 
      {
        var betaling = list.options[ list.selectedIndex ].value; 
        document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&betaling='+ betaling;
      }
</script>
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
          <!-- STATUS FOR KILDEN -->
          <tr class="color2">
            <td colspan="2">
              Status for kilden
            </td>
          </tr>
          <tr>
            <td>
              Spideren er <?=( $kilde->crawling =='y')?" ":"ikke"?> aktiv 
            </td>
            <td valign="top">
              <input type="button" class="stor_knap" style="width:250px" 
                onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&spider_state=<?=$kilde->crawling?>'"  
                value="<?=( $kilde->crawling == 'y' )?"Stop spideren":"Start spideren"?>" >
            </td>
          </tr>
          <tr>
            <td>
              Kilden er <?=( $kilde->publish )?" ":" ikke "?> publiceret 
            </td>
            <td>
              <input type="button" class="stor_knap" style="width:250px" 
              onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&publish_state=<?=($kilde->publish)?'n':'y'?>'" 
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
          <!-- /STATUS FOR KILDEN -->

          <!-- BRUGSBETINGELSERNE -->
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr class="color2">
            <td colspan="2">
                Brugsbetingelserne
            </td>
          </tr>
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
                onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?pane=<?=$pane?>&id=<?=$id?>&overrule_betaling=<?=($kilde->overrule_betaling=='y')?'n':'y'?>'"
                value="<?=( $kilde->overrule_betaling != 'y' )?"Specificere betalings model":"Ignorer betalings model"?>" >
            </td>
          </tr>
          <?if($kilde->overrule_betaling == 'y' ):?>
          <tr>
            <td colspan="2">
              <select class="select_list" name="betaling" onchange="toggleBetaling(this)">
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
</form>

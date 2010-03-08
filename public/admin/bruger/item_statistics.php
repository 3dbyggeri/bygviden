<?php
require_once("../util/statistics.php");
require_once("../util/date_widget.php");
require_once("../util/bruger.php");
$statistics = new statistics( $dba );
$bruger = new bruger( $dba );
$bruger_id = $_GET['bruger_id'];
if( !$bruger_id ) $bruger_id = $_POST['bruger_id'];
$bruger->setId( $bruger_id );
$props = $bruger->loadBruger();

$periode = 0;
$period_label = ' i de seneste 30 dage';
$day_label = 'i de sidste 24 timer';

if($_POST['fetch_period'])
{
  $periode = 1;
  $period_label = 'i perioden';
  $day_label = 'i de sidste 24 timer af perioden';
  $to = array('m'=>$_POST['month_to'],'d'=>$_POST['day_to'],'Y'=>$_POST['year_to']); 
  $from = array('m'=>$_POST['month_from'],'d'=>$_POST['day_from'],'Y'=>$_POST['year_from']); 
}
else
{
  $to = array('m'=>date("m"),'d'=>date('d'),'Y'=>date('Y'));
  // Get 30 days back in seconds
  $s = 60 * 60 * 24 * 30;
  $t = time() - $s;
  $from = array('m'=>date("m",$t),'d'=>date('d',$t),'Y'=>date('Y',$t));
}

$from_date = new date_widget('from',$from['d'],$from['m'],$from['Y']);
$to_date = new date_widget('to',$to['d'],$to['m'],$to['Y']);
$userStat = $statistics->getUserStat($from,$to,$bruger_id);
$hitsOverview = $statistics->getUserOverview($from,$to,$bruger_id);
$last24 = $statistics->getUserLast24Hours($to,$bruger_id);
$publications = $statistics->getUserPublications($from,$to,$bruger_id);

$unique_hits = array();
$all_hits = array();
for($i=0;$i<count($hitsOverview);$i++)
{
  $all_hits[$hitsOverview[$i]['mydate']]+= $hitsOverview[$i]['total'];
  $unique_hits[$hitsOverview[$i]['mydate']]+=1;
}

$unique_24_hits = array();
$all_24_hits = array();

for($i=0;$i<count($last24);$i++)
{
  $all_24_hits[$last24[$i]['myhour']]+= $last24[$i]['total'];
  $unique_24_hits[$last24[$i]['myhour']]+=1;
}
$total_pubs = 0;
for($i=0;$i<count($publications);$i++) $total_pubs+= $publications[$i]['total'];

$graphHeight = 400;
?>
<form name="tree" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="bruger_id" value="<?=$bruger_id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">
      Statistik for <?=$props['firma']?> <?=$props['navn']?> <?=$props['titel']?>
    </td>
  </tr>   
  <tr> 
    <td align="center">&nbsp;</td>
  </tr> 
  <tr> 
      <td>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="color1">
            <tr>
                <td class="tdpadtext">&nbsp;</td>
                <td class="tdpadtext"><img src="../graphics/transp.gif" height="20" width="250"></td>
            </tr>	
            <tr>
                <td class="tdpadtext">Side visninger i <?=($periode)?'periode':'sidste 30 dage'?>:</td>
                <td class="alert_message" style="font-size:10px">[<?=array_sum($userStat)?>]</td>
            </tr>	
            <tr>
                <td class="tdpadtext">Unique besøg i <?=($periode)?'periode':'sidste 30 dage'?>:</td>
                <td class="alert_message" style="font-size:10px">[<?=count($userStat)?>]</td>
            </tr>	
            <tr>
                <td class="tdpadtext">Antal publikationer læst i <?=($periode)?'periode':'sidste 30 dage'?>:</td>
                <td class="alert_message" style="font-size:10px">[<?=$total_pubs?>]</td>
            </tr>	

            <!--total hits overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Visninger <?=$period_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = array_sum($all_hits);
                  $max = 0;
                  $m = 11;

                  foreach ($all_hits as $key => $value)
                  {
                     if($all_hits[$key] > $max) $max = $all_hits[$key];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($all_hits)):?>
                    <tr>
                      <td align="center" style="font-size:9px">Ingen visning</td>
                    </tr>
                  <?endif?>
                  <?foreach ($all_hits as $key => $value):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$key?></td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" width="<?=( $value * $unit )?>" alt="<?=$key?>"> <?=$value?>
                      </td>
                    </tr>
                  <?endforeach?>
                </table>
              </td>
            </tr>	
            <!--end total hit overview-->

            <!--unique besog overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Unique besøg <?=$period_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = array_sum($unique_hits);
                  $max = 0;
                  $m = 11;

                  foreach ($unique_hits as $key => $value)
                  {
                     if($unique_hits[$key] > $max) $max = $unique_hits[$key];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($unique_hits)):?>
                    <tr>
                      <td align="center" style="font-size:9px">Ingen besøg</td>
                    </tr>
                  <?endif?>
                  <?foreach ($unique_hits as $key => $value):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$key?></td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" width="<?=( $value * $unit )?>" alt="<?=$key?>"> <?=$value?>
                      </td>
                    </tr>
                  <?endforeach?>
                </table>
              </td>
            </tr>	
            <!--end unique besog overview-->

            <!--day hit overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Side visninger <?=$day_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = array_sum($all_24_hits);
                  $max = 0;
                  $m = 11;

                  foreach ($all_24_hits as $key => $value)
                  {
                     if($all_24_hits[$key] > $max) $max = $all_24_hits[$key];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($all_24_hits)):?>
                    <tr>
                      <td align="center" style="font-size:9px">Ingen visning</td>
                    </tr>
                  <?endif?>
                  <?foreach ($all_24_hits as $key => $value):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$key?>:00</td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" width="<?=( $value * $unit )?>" alt="<?=$key?>"> <?=$value?>
                      </td>
                    </tr>
                  <?endforeach?>
                </table>
              </td>
            </tr>	
            <!--end total day hit overview-->


            <!--day session overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Unique besøg <?=$day_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = array_sum($unique_24_hits);
                  $max = 0;
                  $m = 11;

                  foreach ($unique_24_hits as $key => $value)
                  {
                     if($unique_24_hits[$key] > $max) $max = $unique_24_hits[$key];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($unique_24_hits)):?>
                    <tr>
                      <td style="font-size:9px" align="center">
                        Ingen besøg
                      </td>
                    </tr>
                  <?endif?>
                  <?foreach ($unique_24_hits as $key => $value):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$key?>:00</td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" width="<?=( $value * $unit )?>" alt="<?=$key?>"> <?=$value?>
                      </td>
                    </tr>
                  <?endforeach?>
                </table>
              </td>
            </tr>	
            <!--end total day hit overview-->

            <!--publication overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Læste publikationer <?=$period_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = $total_pubs;
                  $max = 0;
                  $m = 11;

                  for($i=0;$i<count($publications);$i++)
                  {
                     if($publications[$i]['total'] > $max) $max = $publications[$i]['total'];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($publications)):?>
                    <tr>
                      <td style="font-size:9px" align="center">
                        Ingen læste publikationer
                      </td>
                    </tr>
                  <?endif?>
                  <?for($i=0;$i<count($publications);$i++):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$publications[$i]['mydate']?></td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" width="<?=( $publications[$i]['total'] * $unit )?>"> <?=$publications[$i]['total']?>
                      </td>
                    </tr>
                  <?endfor?>
                </table>
              </td>
            </tr>	
            <!--end publications overview-->


            
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">
                  Periode fra dato (m.d.y) - til dato (m.d.y):<br><br>
                  <?=$from_date->render()?>
                  <?=$to_date->render()?>
                  <input type="submit" name="fetch_period" class="knap" style="width:150px" value="Vis data for perioden">
                  <br><br>
                </td>
            </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
          </table>
  </tr>
  <tr> 
    <td class="tdpadtext">
      <!--* available data since <?=$startStatisticData?>-->
    </td>
  <tr>
  <tr> 
    <td ><img src="../graphics/transp.gif" height="15"></td>
  <tr>
</table>
</form>

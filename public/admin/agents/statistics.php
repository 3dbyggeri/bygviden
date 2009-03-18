<?php
require_once("../util/statistics.php");
require_once("../util/date_widget.php");
$statistics = new statistics( $dba );
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
$hitsOverview = $statistics->getSearchOverview($from,$to);
$last24 = $statistics->getLast24HoursSearch($to);
$lastSearches = $statistics->getLastSearches($to);

$from_date = new date_widget('from',$from['d'],$from['m'],$from['Y']);
$to_date = new date_widget('to',$to['d'],$to['m'],$to['Y']);
$graphHeight = 400;
?>
<form name="my_form" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="id" value="<?=$id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header">
      Søgning statistik <span class="alert_message"><?=$message?></span>
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

            <!--search overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Resultat visninger <?=$period_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = count($hitsOverview);
                  $max = 0;
                  $m = 11;

                  for($i=0;$i<count($hitsOverview);$i++)
                  {
                     if($hitsOverview[$i]['total'] > $max) $max = $hitsOverview[$i]['total'];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($hitsOverview)):?>
                    <tr>
                      <td style="font-size:10px;" align="center">Ingen søgning</td>
                    </tr>
                  <?endif?>
                  <?for($i=0;$i<count($hitsOverview);$i++):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$hitsOverview[$i]['mydate']?></td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" 
                          width="<?=( $hitsOverview[$i]['total'] * $unit )?>"><?=$hitsOverview[$i]['total']?>
                      </td>
                    </tr>
                  <?endfor?>
                </table>
              </td>
            </tr>	
            <!--end total hit overview-->

            <!--day hit overview-->
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">Resultat visninger <?=$day_label?></td>
            </tr>	
            <tr>
              <td colspan="2" class="tdpadtext">
                <?
                  $n = count($last24);
                  $max = 0;
                  $m = 11;

                  for($i=0;$i<count($last24);$i++)
                  {
                     if($last24[$i]['total'] > $max) $max = $last24[$i]['total'];
                  }

                  //check for division by cero
                  if($max) $unit = $graphHeight / $max;
                  else $unit = 1;
                  $c = 0;
                ?>
                <table cellpadding="2" cellspacing="0" border="0">
                  <?if(!count($last24)):?>
                    <tr>
                      <td style="font-size:10px;" align="center">Ingen søgning</td>
                    </tr>
                  <?endif?>
                  <?for($i=0;$i<count($last24);$i++):?>
                    <?$c++?>
                    <tr>
                      <td style="font-size:9px"><?=$last24[$i]['myhour']?>:00</td>
                      <td style="font-size:9px">
                        <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                          height="10" width="<?=( $last24[$i]['total']* $unit )?>"> <?=$last24[$i]['total']?>
                      </td>
                    </tr>
                  <?endfor?>
                </table>
              </td>
            </tr>	
            <!--end total day hit overview-->

            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" 
                  colspan="2"><a name="topvisit">Seneste søgninger <?=($periode)? 'i perioden':''?></td>
            </tr>	
            <tr>
                <td colspan="2">
                  <table width="100%" cellpadding="3" cellspacing="0" border="0">
                    <?if( !count( $lastSearches) ):?>
                       <tr class="color2" style="height:30px">
                          <td class="tabelText" colspan="2" align="center">Ingen søgninger</td>
                       <tr> 
                    <?else:?>
                      <tr style="font-size:10px;font-weight:900">
                        <td>Dato</td>
                        <td>Søge ord</td>
                        <td>Antal resultater</td>
                        <td>Førte søgning til læsning?</td>
                      </tr>
                    <?endif?>
                    <?for( $i = 0; $i < count( $lastSearches); $i++ ):?>
	                   <tr class="<?=($i%2==0)?"color2":"color3"?>" style="font-size:10px">
                        <td> <?=$lastSearches[$i]['mydate']?> </td>
                        <td> <?=$lastSearches[$i]['query']?> </td>
                        <td> <?=$lastSearches[$i]['results']?> </td>
                        <td> <?=($lastSearches[$i]['pub_id'])?'Ja':'Nej'?></td>
                     </tr>
                    <?endfor?>
                  </table>
                </td>
            </tr>	

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

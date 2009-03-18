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

$from_date = new date_widget('from',$from['d'],$from['m'],$from['Y']);
$to_date = new date_widget('to',$to['d'],$to['m'],$to['Y']);
$docStats = $statistics->getDocStats($from,$to,$kilde->type,$kilde->id);
$docDayStats = $statistics->getDocDayStats($to,$kilde->type,$kilde->id);
$docsOverview = $statistics->getDocsOverview($from,$to,$kilde->type,$kilde->id);
$last24 = $statistics->getLast24HoursDokuments($to,$kilde->type,$kilde->id);

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
      Statistik for <?=$kilde->name?></span>
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
                <td class="tdpadtext">Visninger i <?=($periode)?'perioden':'de sidste 30 dage'?>:</td>
                <td class="alert_message" style="font-size:12px">[<?=$docStats?>]</td>
            </tr>	
            <tr>
                <td class="tdpadtext">Visninger indenfor de sidste 24 timer <?=($periode)?'af perioden':''?>:</td>
                <td class="alert_message" style="font-size:12px">[<?=$docDayStats?>]</td>
            </tr>	

            <!--dokument overview-->
            <?if(count($docsOverview)):?>
              <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
              <tr>
                  <td class="tdpadtext">Visninger <?=$period_label?></td>
                  <td class="alert_message">&nbsp;</td>
              </tr>	

              <tr>
                <td colspan="2" class="tdpadtext">
                  <table cellpadding="2" cellspacing="0" border="0">
                    <?
                      $c=0;
                      $max = 0;
                      for($i=0;$i<count($docsOverview);$i++) 
                        if($docsOverview[$i]['total'] > $max) $max = $docsOverview[$i]['total'];

                      if($max) $unit = $graphHeight / $max;
                      else $unit = 1;
                    ?>

                    <?for($i=0;$i<count($docsOverview);$i++):?>
                      <?$c++?>
                      <tr>
                        <td style="font-size:9px"><?=$docsOverview[$i]['mydate']?></td>
                        <td style="font-size:9px">
                          <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                            height="10" width="<?=( $docsOverview[$i]['total'] * $unit )?>" 
                            alt="<?=$docsOverview[$i]['mydate']?>"> <?=$docsOverview[$i]['total']?>
                        </td>
                      </tr>
                    <?endfor?>
                  </table>
                </td>
              </tr>	
            <?endif?>
            <!--end dokument overview-->

            <!--day dokument overview-->
            <?if(count($last24)):?>
              <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
              <tr>
                  <td class="tdpadtext">Visninger <?=$day_label?></td>
                  <td class="alert_message">&nbsp;</td>
              </tr>	
              <tr>
                <td colspan="2" class="tdpadtext">
                  <table cellpadding="2" cellspacing="0" border="0">
                    <?
                      $c=0;
                      $max = 0;
                      for($i=0;$i<count($last24);$i++) 
                        if($last24[$i]['total'] > $max) $max = $last24[$i]['total'];

                      if($max) $unit = $graphHeight / $max;
                      else $unit = 1;
                    ?>

                    <?for($i=0;$i<count($last24);$i++):?>
                      <?$c++?>
                      <tr>
                        <td style="font-size:9px"><?=$last24[$i]['myhour']?>:00</td>
                        <td style="font-size:9px">
                          <img align="middle" src="../graphics/<?=($c%2==0)?'':'dark_'?>orange.gif" 
                            height="10" width="<?=( $last24[$i]['total'] * $unit )?>" 
                            alt="<?=$last24[$i]['myhour']?>:00"> <?=$last24[$i]['total']?>
                        </td>
                      </tr>
                    <?endfor?>
                  </table>
                </td>

              </tr>	
            <?endif?>
            <!--end day dokument overview-->

            <?if($kilde->type !='publikation'):?>
              <?$topReaded = $statistics->getTopReadedDokuments($from,$to,$kilde->type,$kilde->id)?>
              <?if( count( $topReaded ) ):?>
                <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
                <tr>
                    <td class="tdpadtext" 
                      colspan="2"><a name="topvisit">Mest læste dokumenter 
                      <?=($periode)? 'i perioden':'de sidste 30 dage'?></td>
                </tr>	
                <tr>
                    <td colspan="2">
                      <table width="100%" cellpadding="3" cellspacing="0" border="0">
                        <?for( $i = 0; $i < count( $topReaded ); $i++ ):?>
                         <tr class="<?=($i%2==0)?"color2":"color3"?>">
                            <td style="font-size:10px;" >
                              <a href="../../view/document.php?pub=<?=$topReaded[$i]['publication_id']?>" 
                                target="_blank" 
                                style="padding-left:10px;color:#000000;text-decoration:none"
                               ><?=$topReaded[$i]['viden_lev_name']?>/<?=$topReaded[$i]['category_name']?>/<?=$topReaded[$i]['name']?></a>
                            </td>
                            <td class="plainText"><?=$topReaded[$i]["total"]?></td>
                         </tr>
                        <?endfor?>
                      </table>
                    </td>
                </tr>	
              <?endif?>
            <?endif?>

            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" colspan="2">
                  Periode fra dato (m.d.y) - til dato (m.d.y):<br><br>
                  <?=$from_date->render()?>
                  <?=$to_date->render()?>
                  <input type="submit" name="fetch_period" class="knap" 
                    style="width:150px" value="Vis data for perioden">
                  <br><br>
                </td>
            </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
          </table>
  </tr>
  <tr> 
    <td class="tdpadtext">&nbsp;</td>
  <tr>
  <tr> 
    <td ><img src="../graphics/transp.gif" height="15"></td>
  <tr>
</table>
</form>




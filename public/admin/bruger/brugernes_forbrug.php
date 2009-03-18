<?php
  require_once('../util/bruger.php');
  require_once('../util/date_widget.php');
  $start_period = new date_widget('start');
  $end_period   = new date_widget('end');

  $bruger = new bruger( $dba );

  if( $_POST['fetch_usage'] )
  {
    $usage = $bruger->usageFromDayMonthYearToDayMonthYear( $_POST['day_start'],
                                                           $_POST['month_start'],
                                                           $_POST['year_start'],
                                                           $_POST['day_end'],
                                                           $_POST['month_end'],
                                                           $_POST['year_end'] );
    $start_period->setDate( $_POST['day_start'],
                            $_POST['month_start'],
                            $_POST['year_start'] );
                        
    $end_period->setDate( $_POST['day_end'],
                          $_POST['month_end'],
                          $_POST['year_end'] );
  }
  if( $_POST['fetch_usage_not_billed'] )
  {
    $usage = $bruger->usageFromLastFaktura();
  }
  if( $_GET['faktura']  )
  {
    $bruger->fakturereForDayMonthYear( $_GET['d2'], $_GET['m2'], $_GET['y2'] );
  }
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td colspan="6"><img src="../graphics/transp.gif" height="20"></td>
  </tr>
  <tr>
    <form name="myform" action="<?=$_SERVER['PHP_SELF']?>" method="post">
      <input type="hidden" name="pane" value="<?=$pane?>">
    <td nowrap colspan="6" align="center" class="tabelText">
      Fra:<?=$start_period->render()?>
      Til:<?=$end_period->render()?>
      <br><br>
      <input type="submit" value="Vis forbrug i perioden" name="fetch_usage" class="knap" style="width:250px">
    </td>
    </form>
  </tr>
  <tr>
    <form name="myform" action="<?=$_SERVER['PHP_SELF']?>" method="post">
      <input type="hidden" name="pane" value="<?=$pane?>">
    <td nowrap colspan="6" align="center" class="tabelText">
      <br>
      <input type="submit" value="Vis ikke faktureret forbrug" name="fetch_usage_not_billed" class="knap" style="width:250px">
    </td>
    </form>
  </tr>
  <tr>
    <td colspan="6"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
</table>
<?if( count( $usage ) ):?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr bgcolor="#e3e3e3">
    <td class="tabelText"> Medlemsnr.</td>
    <td class="tabelText"> Navn</td>
    <td class="tabelText"> Under-bruger</td>
    <td class="tabelText"> Antal</td>
    <td class="tabelText"> Beløb</td>
  </tr>
  <?foreach( $usage as $key=>$value ):?>
    <?$group = $value?>
    <?for( $j = 0; $j < count( $group ); $j++ ):?>
      <?
        if( $group[$j]['mester_id'] )
        {
          $medlemsnr = $group[$j]['mester_medlemsnr'];
          $navn      = $group[$j]['mester_name1'] .' '. $group[$j]['mester_name2'];
          $under_bruger =  $group[$j]['name1'] .' '. $group[$j]['name2'];
        }
        else
        {
          $medlemsnr = $group[$j]['medlemsnr'];
          $navn      = $group[$j]['name1'] .' '. $group[$j]['name2'];
          $under_bruger =  '-';
        }
        $antal = $group[$j]['number'];
        $pris  = $group[$j]['pris'];
        $total_pris += $group[$j]['pris'];
        $total_antal+= $group[$j]['number'];
      ?>
      <tr class="<?=($j%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
        <td class="tabelText"><?=$medlemsnr?></td>
        <td class="tabelText"><a 
          href="index_bruger.php?bruger_id=<?=( $group[$j]['mester_id'] )? $group[$j]['mester_id']:$group[$j]['user_id']?>&pane=forbrug" 
          class="tabelText"><?=$navn?></a></td>
        <td class="tabelText"><?=$under_bruger?></td>
        <td class="tabelText"><?=$antal?></td>
        <td class="tabelText"><?=$pris?></td>
      </tr>
    <?endfor?>
  <?endforeach?>
  <tr bgcolor="#e3e3e3">
    <td class="tabelText"> &nbsp;</td>
    <td class="tabelText"> &nbsp;</td>
    <td class="tabelText"> Total</td>
    <td class="tabelText"> <?=$total_antal?></td>
    <td class="tabelText"> <?=$total_pris?></td>
  </tr>
  <tr>
    <td colspan="5"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr>
    <td colspan="5" align="center" class="tabelText">
      <?
        if( $_POST['day_start'] )
        {
          $param = 'd1='.$_POST['day_start'].'&m1='.$_POST['month_start'].'&y1='.$_POST['year_start'];
          $param2= '&d2='.$_POST['day_end'].'&m2='.$_POST['month_end'].'&y2='.$_POST['year_end'];
          $param.= $param2;
        }
        else
        {
          $param = 'billingToDate=1';
          $param2 = '&d2='. date('d') .'&m2='. date('m') .'&y2='. date('Y');
        }
      ?>
      <a href="brugernes_forbrug_xsl.php?<?=$param?>" target="_blank"><img src="../graphics/excel_icon.gif" border="0"></a>
      <a href="brugernes_forbrug_xsl.php?<?=$param?>" target="_blank" class="tabelText">Download som Excel ark</a>
      <br><br>
      <input type="button" class="knap" 
        onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?faktura=1&pane=<?=$pane?><?=$param2?>'"
        value="Registrere som faktureret" style="width:250px">
    </td>
  </tr>
  </table>
<?endif?>


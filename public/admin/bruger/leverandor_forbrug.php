<?php
  require_once("../util/bruger.php");
    require("../util/date_widget.php");
    $start_period = new date_widget("start");
    $end_period   = new date_widget("end");
  
    $bruger = new bruger( $dba );

    if( $_POST['fetch_lev_usage'] )
    {
      $usage = $bruger->levUsageFromDayMonthYearToDayMonthYear( $_POST['day_start'],
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
      $usage = $bruger->levUsageFromLastFaktura();
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
      <input type="submit" value="Vis forbrug i perioden" name="fetch_lev_usage" class="knap" style="width:250px">
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
    <td class="tabelText"> Vidensleverandør</td>
    <td class="tabelText"> Antal publikationer</td>
    <td class="tabelText"> Beløb</td>
  </tr>
  <?for( $i = 0; $i < count( $usage ); $i++ ):?>
    <tr class="<?=($i%2==0)?"color1":"color2"?>" style="padding-top:3px;padding-bottom:3px;">
      <td class="tabelText"><?=$usage[$i]['videns_navn']?></td>
      <td class="tabelText"><?=$usage[$i]['number']?></td>
      <td class="tabelText"><?=$usage[$i]['pris']?></td>
    </tr>
    <?
       $total_antal+= $usage[$i]['number'];
       $total_pris += $usage[$i]['pris'];
    ?>
  <?endfor?>
  <tr bgcolor="#e3e3e3">
    <td class="tabelText"> Total</td>
    <td class="tabelText"> <?=$total_antal?></td>
    <td class="tabelText"> <?=$total_pris?></td>
  </tr>
  <tr>
    <td colspan="3"><img src="../graphics/transp.gif" height="15"></td>
  </tr>
  <tr>
    <td colspan="3" align="center" class="tabelText">
      <?
        if( $_POST['day_start'] )
        {
          $param = 'd1='.$_POST['day_start'].'&m1='.$_POST['month_start'].'&y1='.$_POST['year_start'];
          $param.= '&d2='.$_POST['day_end'].'&m2='.$_POST['month_end'].'&y2='.$_POST['year_end'];
        }
        else
        {
          $param = 'billingToDate=1';
        }
      ?>
      <a href="lev_forbrug_xsl.php?<?=$param?>" target="_blank"><img src="../graphics/excel_icon.gif" border="0"></a>
      <a href="lev_forbrug_xsl.php?<?=$param?>" target="_blank" class="tabelText">Download som Excel ark</a>
    </td>
  </tr>
  </table>
<?endif?>

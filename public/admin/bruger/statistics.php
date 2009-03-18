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

$topUsers = $statistics->getTopActiveUsers($from,$to);
$readingUsers = $statistics->getTopReadingUsers($from,$to);
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
      Bruger statistik 
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
                <td class="tdpadtext" 
                  colspan="2"><a name="topvisit">Mest aktive logged-in bruger (flest antal side visninger)
                   <?=($periode)? 'i perioden':' de sidste 30 dage'?></td>
            </tr>	
            <tr>
                <td colspan="2">
                  <table width="100%" cellpadding="3" cellspacing="0" border="0">
                    <?for( $i = 0; $i < count( $topUsers); $i++ ):?>
                      <?
                        $user = $topUsers[$i]['firmanavn1'];
                        $user.=' '.$topUsers[$i]['firmanavn2'];
                        $user.=' '.$topUsers[$i]['firmanavn3'];

                        if(!trim($user)) continue;
                      ?>
	                   <tr class="<?=($i%2==0)?"color2":"color3"?>">
                        <td style="font-size:10px;padding-left:10px" >
                          <a href="index_bruger.php?bruger_id=<?=$topUsers[$i]['user_id']?>"><?=$user?></a>
                        </td>
                        <td class="plainText"><?=$topUsers[$i]["total"]?></td>
                     </tr>
                    <?endfor?>
                  </table>
                </td>
            </tr>	

            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" 
                  colspan="2"><a name="topvisit">Mest l�sende logged-in bruger 
                   <?=($periode)? 'i perioden':' de sidste 30 dage'?></td>
            </tr>	
            <tr>
                <td colspan="2">
                  <table width="100%" cellpadding="3" cellspacing="0" border="0">
                    <?for( $i = 0; $i < count( $readingUsers); $i++ ):?>
                      <?
                        $user = $readingUsers[$i]['firmanavn1'];
                        $user.=' '.$readingUsers[$i]['firmanavn2'];
                        $user.=' '.$readingUsers[$i]['firmanavn3'];

                        if(!trim($user)) continue;
                      ?>
	                   <tr class="<?=($i%2==0)?"color2":"color3"?>">
                        <td style="font-size:10px;padding-left:10px" >
                          <a href="index_bruger.php?bruger_id=<?=$readingUsers[$i]['user_id']?>"><?=$user?></a>
                        </td>
                        <td class="plainText"><?=$readingUsers[$i]["total"]?></td>
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

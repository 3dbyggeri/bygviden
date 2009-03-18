<?php
require_once("../util/bruger.php");
require_once("../util/dba.php");

$dba = new dba();
$bruger = new bruger( $dba );

if( !$_GET['billingToDate'] )
{
  if( !$_GET['d1'] ) die( 'start day missing');
  if( !$_GET['m1'] ) die( 'start month missing');
  if( !$_GET['y1'] ) die( 'start year missing');
  if( !$_GET['d2'] ) die( 'end day missing');
  if( !$_GET['m2'] ) die( 'end month missing');
  if( !$_GET['y2'] ) die( 'end year missing');

  $usage = $bruger->usageFromDayMonthYearToDayMonthYear( $_GET['d1'],
                                                         $_GET['m1'],
                                                         $_GET['y1'],
                                                         $_GET['d2'],
                                                         $_GET['m2'],
                                                         $_GET['y2'] );

  $fileName = 'forbrug_'. $_GET['d1'].'_'.$_GET['m1'].'_'.$_GET['y1'];
  $fileName.= '__'.$_GET['d2'].'_'.$_GET['m2'].'_'.$_GET['y2'];
}
else
{
  $usage = $bruger->usageFromLastFaktura();
  $fileName = 'forbrug_fra_sidste_faktura_til_'. date('d').'_'.date('m').'_'.date('Y');
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$fileName.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
  <tr>
    <td> Medlemsnr.</td>
    <td> Navn</td>
    <td> Under-bruger</td>
    <td> Antal</td>
    <td> Beløb</td>
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
      <tr>
        <td><?=$medlemsnr?></td>
        <td><?=$navn?></td>
        <td><?=$under_bruger?></td>
        <td><?=$antal?></td>
        <td><?=$pris?></td>
      </tr>
    <?endfor?>
  <?endforeach?>
  <tr>
    <td></td>
    <td></td>
    <td>Total</td>
    <td><?=$total_antal?></td>
    <td><?=$total_pris?></td>
  </tr>
</table>

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

  $usage = $bruger->levUsageFromDayMonthYearToDayMonthYear(  $_GET['d1'],
                                                             $_GET['m1'],
                                                             $_GET['y1'],
                                                             $_GET['d2'],
                                                             $_GET['m2'],
                                                             $_GET['y2'] );

  $fileName = 'leverandor_forbrug_'. $_GET['d1'].'_'.$_GET['m1'].'_'.$_GET['y1'];
  $fileName.= '__'.$_GET['d2'].'_'.$_GET['m2'].'_'.$_GET['y2'];
}
else
{
  $usage = $bruger->levUsageFromLastFaktura();
  $fileName = 'leverandor_forbrug_fra_sidste_faktura_til_'. date('d').'_'.date('m').'_'.date('Y');
}


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$fileName.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table>
  <tr>
    <td> Vidensleverandør</td>
    <td> Antal publikationer</td>
    <td> Beløb</td>
  </tr>
  <?for( $i = 0; $i < count( $usage ); $i++ ):?>
    <tr>
      <td><?=$usage[$i]['videns_navn']?></td>
      <td><?=$usage[$i]['number']?></td>
      <td><?=$usage[$i]['pris']?></td>
    </tr>
    <?
       $total_antal+= $usage[$i]['number'];
       $total_pris += $usage[$i]['pris'];
    ?>
  <?endfor?>
  <tr>
    <td> Total</td>
    <td> <?=$total_antal?></td>
    <td> <?=$total_pris?></td>
  </tr>
</table>

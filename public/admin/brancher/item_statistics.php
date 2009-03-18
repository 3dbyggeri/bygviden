<?php
require_once("../util/statistics.php");
require_once("../util/date_widget.php");
include_once( "../util/tree.php" );
include_once( "../util/brancheTree.php" );
require_once("../util/branche.php");

if( !$id ) $id = $_GET['id'];
if( !$id ) $id = $_POST['id'];
if( !$branche ) $branche = $_GET['branche'];
if( !$branche ) $branche = $_POST['branche'];
if( !is_numeric($id) ) die("Parameter expected id");
if( !$branche ) die("Parameter expected branche");

$myBranche = new Branche( $dba, $id, $branche );
$props = $myBranche->loadProperties();
$statistics = new statistics( $dba );
$periode = 0;
$period_label = ' i de seneste 30 dage';
$day_label = 'i de sidste 24 timer';
$tree = new brancheTree( $dba, session_id(),'general');

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
$topVisited = $statistics->getTopVisitedBrancher($from,$to,$props['element_id']);
$topVisitedSingle = $statistics->getTopVisitedBrancher($from,$to,$props['element_id'],$branche);
if(count($topVisitedSingle)) $topVisitedSingle = $topVisitedSingle[0]['total'];
else $topVisitedSingle = 0;
$brancher = $tree->getBrancher();
for($i=0;$i<count($brancher);$i++) $b[$brancher[$i]['name']] = $brancher[$i]['label'];
$brancher = $b;
?>
<form name="my_form" action="<?=$PHP_SELF?>" method="post">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="pane" value="<?=$pane?>">
<input type="hidden" name="branche" value="<?=$branche?>">
<input type="hidden" name="elementid" value="<?=$props['element_id']?>">
<input type="hidden" name="elementname" value="<?=$props['element_name']?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr> 
    <td><img src="../graphics/transp.gif" height="20"></td>
  </tr> 
  <tr>
    <td class="header"> Branche statistik for <?=$props['name']?> </td>
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
                <td class="tdpadtext">
                  Visninger for <?=$props['name']?> under 
                  <?=$brancher[$branche]?> branche i <?=($periode)?'periode':'alt'?>:</td>
                <td class="alert_message" style="font-size:10px">[<?=$topVisitedSingle?>]</td>
            </tr>	

            <tr> <td colspan="2" class="tdpadtext">&nbsp;</td> </tr>	
            <tr>
                <td class="tdpadtext" 
                  colspan="2"><a name="topvisit">
                    General fordeling af besøg til <?=$props['name']?> 
                    bland brancher <?=($periode)? 'i perioden':''?></td>
            </tr>	
            <tr>
                <td colspan="2">
                  <table width="100%" cellpadding="3" cellspacing="0" border="0">
                    <?if( !count( $topVisited ) ):?>
                       <tr class="color2" style="height:30px">
                          <td class="tabelText" colspan="2" align="center">Ingen visning</td>
                       <tr> 
                    <?endif?>
                    <?for( $i = 0; $i < count( $topVisited ); $i++ ):?>
	                   <tr class="<?=($i%2==0)?"color2":"color3"?>">
                        <td style="font-size:10px;padding-left:10px" >
                          <?$t=$topVisited[$i]['branche_id']?>
                          <?=($brancher[$t])? $brancher[$t]:$t?>
                        </td>
                        <td class="plainText"><?=$topVisited[$i]["total"]?></td>
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


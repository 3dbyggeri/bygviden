<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Bygviden.dk</title>
	<link href="../styles/bygviden.css" rel="stylesheet" type="text/css">
</head>

<body style="margin:0px">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="25" background="../graphics/red_dot_5x5px.gif"><img src="../graphics/top_bar_1.gif" width="205" height="25" border="0"></td>
		<td height="25" align="right" background="../graphics/red_dot_5x5px.gif"><img src="../graphics/top_bar_2.gif" width="565" height="25"></td>
	</tr>
</table>
<br><br>
<center>
<form name="myform" action="order.php" method="post">
<div style="width:600px;"><span style="color: #074C8A;">
<br>
<?if( ! $kilde->hasBrugerRabat() ):?>
  Publikationen koster 
  <br>
  <?if($payment_status['enkelt_betaling']):?>
    <?=$payment_status['enkelt_pris']?> kr. som enkelt visning
    <br>
  <?endif?>

  <?if($payment_status['abonament_betaling']):?>
    <?if($payment_status['enkelt_betaling']):?>
     eller<br> 
    <?endif?>
    <?=$payment_status['abonament_pris']?> 
    kr. som <?=$payment_status['abonament_periode']?> m&aring;nedes abonnement 
    <br>
  <?endif?>

<?else:?>
  
  <?if($_SESSION['is_mester']):?>
    <?
      $antal_users = $bruger->getAntalUsers();
      if(!$antal_users ) $antal_users = 1;
      $priser = $kilde->getBrugerRabatPriser();
    ?>
    V&aelig;lg det antal bruger som vil f&aring; adgang til abonnementet:
    <br><br>

    <table cellpadding="4" cellspacing="0" border="0">
    <? $c=0?>
    <?foreach($kilde->bruger_data_model_boundaries as $k=>$v):?>
      <?if(intval($antal_users) > intval($k)) continue; ?>
      <?if(!$priser[$v]) continue;?>
       <tr>
          <td> <input type="radio" name="bruger_interval" value="<?=$v?>"  <?=($c==0)? 'checked':''?>></td>
          <td style="font-family:verdana,sans-serif;font-size:12px"> <?=$kilde->bruger_data_model[$v]?></td>
          <td align="right" style="font-family:verdana,sans-serif;font-size:12px">&nbsp; <?=$priser[$v]?> Kr.</td>
       </tr>
       <? $c++?>
    <?endforeach?>
    </table>
    <br>

    <input type="submit" name="bruger_rabat" 
      value="V&aelig;lg" style="width:150px;color:#000000;font-size:12px">
    <br><br>

  <?else:?>
    Adgang til denne publikation kan k&oslash;bes af din virksomhed samlet for alle brugere.
  <?endif?>

<?endif?>

<br>
<script language:"javaScript">
  function hideEnkelt()
  {
    var myForm = document.myform;
    myForm.enkelt_visning.style.visibility="hidden";
  }
  function hideAbonament()
  {
    var myForm = document.myform;
    myForm.abonament.style.visibility="hidden";
  }
</script>



  <?if(!$_SESSION['is_mester'] && $_SESSION['clipkort'] > 0):?>
    <input type="hidden" name="reload_opener" value="1">
  <?endif?>
  <input type="hidden" name="pub" value="<?=$_GET['pub']?>">
  <input type="hidden" name="uid" value="<?=$_GET['uid']?>">
  <input type="hidden" name="doc" value="<?=$_GET['doc']?>">
  <input type="hidden" name="title" value="<?=$_GET['title']?>">

  <input type="button" onclick="window.close()" value="Luk vindue" style="width:150px;color:#000000;font-size:12px">
<?if($payment_status['enkelt_betaling']):?>
  <input type="submit" onclick="hideEnkelt()" name="enkelt_visning" value="Køb som enkelt visning" style="width:150px;color:#000000;font-size:12px">
<?endif?>
<?if($payment_status['abonament_betaling']):?>
  <input type="submit" onclick="hideAbonament()" name="abonament" value="Køb som abonnement" style="width:150px;color:#000000;font-size:12px">
<?endif?>

</form>
</div>

</center>
</body>
</html>

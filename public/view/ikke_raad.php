<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Bygviden.dk</title>
	<link href="../styles/bygviden.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="25" background="../graphics/red_dot_5x5px.gif"><img src="../graphics/top_bar_1.gif" width="205" height="25" border="0"></td>
		<td height="25" align="right" background="../graphics/red_dot_5x5px.gif"><img src="../graphics/top_bar_2.gif" width="565" height="25"></td>
	</tr>
</table>
<span class="normalText">
Du har kun <?=( $_SESSION['clipkort'] )?$_SESSION['clipkort']:0?> kr. tilbage på dit klippekort.
<br>
Publikationen koster 
<br>
<?if( $kilde->enkelt_betaling ):?>
  <?=( $kilde->enkelt_pris )? $kilde->enkelt_pris: 0?> kr. som enkelt visning
  <br>
<?endif?>

<?if( $kilde->abonament_betaling ):?>
  <?if( $kilde->enkelt_betaling ):?>
   eller<br> 
  <?endif?>
  <?=( $kilde->abonament_pris )? $kilde->abonament_pris: 0?> 
  kr. som <?=$kilde->abonament_periode?> måneders abonnement .
  <br>
<?endif?>
<br>
</span>
</body>
</html>
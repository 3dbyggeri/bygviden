<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?
$form_field = $_REQUEST['formfield'];
$field_id = $_REQUEST['fieldID'];
?>
<html>
<head>
	<title>Tekst redigering</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<script language="JavaScript" type="text/javascript" src="html2xhtml.js"></script>
	<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
	<script language="JavaScript" type="text/javascript" src="richtext.js"></script>
  <style>
    body {background-color:#fff0A4;}
    #rte1 {background-color:#fff; }
  </style>
</head>
<body onload="loadText()">
<!-- START Demo Code -->
<form name="RTEDemo" action="<?=$_SERVER["PHP_SELF"]?>" method="post" onsubmit="return submitForm();">
<input type="hidden" name="formfield" value="<?=$form_field?>">
<input type="hidden" name="fieldID" value="<?=$field_id?>">
<script language="JavaScript" type="text/javascript">
<!--
function loadText()
{
  return;
  if(!opener) return;
  var val = opener.<?=$form_field?>.value;
  alert(val)
  insertHTML(val);
}
function submitForm() 
{
  updateRTE('rte1');
  if(!opener) return;
  var txt = document.RTEDemo.rte1.value;
  opener.<?=$form_field?>.value = txt;
  var el = opener.document.getElementById('<?=$field_id?>_paragraph');
  if(el) el.innerHTML = txt;
  window.close();
  return false;
}
initRTE("images/", "", "editor.css", true);
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
<script language="JavaScript" type="text/javascript">
<!--
var val = '';
if(opener) 
{
  val = opener.<?=$form_field?>.value;
}
writeRichText('rte1',val, 520, 200, true, false);
//-->
</script>

<div style="text-align:right;width:540px">
  <input type="button" value="Fortryd" 
    onclick="window.close()"
    style="background-color:#ff9900;border:0">
  <input type="submit" value="Gemt" style="background-color:#ff9900;border:0">
</div>
</form>
<?php
function rteSafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = $strText;
	
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	$tmpString = str_replace("'", "&#39;", $tmpString);
	
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
//	$tmpString = str_replace("\"", "\"", $tmpString);
	
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), " ", $tmpString);
	$tmpString = str_replace(chr(13), " ", $tmpString);
	
	return $tmpString;
}
?>
<!-- END Demo Code -->

</body>
</html>

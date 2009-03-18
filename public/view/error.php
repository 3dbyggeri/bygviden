<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Bygviden.dk</title>
	<link href="../styles/bygviden.css" rel="stylesheet" type="text/css">
  <link href="../styles/main.css" rel="stylesheet" type="text/css" />
  <link href="../styles/links.css" rel="stylesheet" type="text/css" />
  <script language="javascript" src="../script.js"></script>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <link href="../tema/style.css" rel="stylesheet" type="text/css" />
<style>
    body {background-image:none; }
</style>
</head>

<body style="margin:0px">

    <div id="header">
        <div id="top">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td><img src="../tema/graphics/logo.gif" border="0" width="363" height="73"></td>
                    <td valign="top" id="member">&nbsp;</td>
               </tr>
            </table>

        </div>
            <table id="topmenu" width="100%" cellpadding="2" cellspacing="0" border="0">
                <tr>
                    <td valign="top" class="menu">
                        <ul><li>&nbsp;</li></ul>
                    </td>
                    <td class="login" valign="top" align="right" nowrap>
                        &nbsp;
                    </td>
               </tr>
            </table>
    </div>
   <div id="content">
<div id="left_content">



<br><br>
<center>
<?php
$id = $_GET['id'];
$errors = Array(
          '1'=>'Publikationen er ikke offentlige tilg&aelig;ngelige'
          ,'2'=>'Publikationens id mangler'
          ,'3'=>'Publikationen er ikke tilg&aelig;ngelige i digital udgave'
          ,'4'=>'Fuldtekst versionen af publikationen er ikke tilg&aelig;ngelige'
          ,'5'=>'Fuldtekst versionen af publikationen er 
                 kun tilg&aelig;ngelige for medlemmer.<br> 
                 Hvis du er medlem skal du f&oslash;rst v&aelig;re logget ind'
          ,'6'=>'Visning af publikationen kr&aelig;ver en registreret bruger.<br>
                 Hvis du er medlem skal du f&oslash;rst v&aelig;re logget ind'
          ,'7'=>'Publikationen har ikke en pris defineret'
          ,'8'=>'P&aring; grund af tekniske vanskeligheder kan denne side ikke vises ( id:8 )'
          ,'9'=>'Desv&aelig;rre, der mangler parameter n&oslash;dvendige for at fuldf&oslash;re foresp&oslash;rgelsen.( id:9 )'
          ,'10'=>'Forkert brugernavn eller password'
          ,'11'=>'Tr&aelig;branchens Oplysningsr&aring;d stiller ikke denne publikation til r&aring;dighed i en digital version.'
          ,'12'=>'Fuldtekst versionen af publikationen er 
                  kun tilg&aelig;ngelige for medlemmer af Dansk Byggeri.<br>'
          );
$msg = $errors[ $_GET['id'] ];          

if( $_POST['log_in'] )
{
  $msg = '';
  $id = 6;
}
?>
<span class="error" style="font-size:12px"><?=$msg?></span>
<?if( $id == 6 || $id == 10 || $id==5):?>
  <br><br>
  <center>
    <?
      $location='view/document.php?pub='. $_GET['pub'];
      $path_prefix = '../';
      $pub = $_GET['pub'];
    ?>
    <?require_once('../log_in_inc.php')?>
  </center>
<?endif?>
</center>
</div>
</div>
</body>
</html>

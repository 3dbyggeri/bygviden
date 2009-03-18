<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Bygviden.dk</title>
	<link href="styles/bygviden.css" rel="stylesheet" type="text/css">
  <link href="styles/main.css" rel="stylesheet" type="text/css" />
  <link href="styles/links.css" rel="stylesheet" type="text/css" />
  <script language="javascript" src="script.js"></script>
</head>

<body style="margin:0px">
	<div id="topbar">
		<img src="graphics/bygvidenlogo.gif" alt="Bygviden.dk" height="28" width="79" border="0" class="toplogo" />
		<img src="graphics/bygvidenslogan.gif" alt="Bygviden strukturerer og formidler byggeteknisk viden efter dine behov" height="28" width="419" border="0" class="topslogan" />
	</div>

<br><br>
<center>
  <center>
    <?
      $location='view/document.php?pub='. $_REQUEST['pub'];
      $path_prefix = '';
      $pub = $_REQUEST['pub'];
    ?>

        <div id="headline">Opdatere dit password</div>
        <h2 style="color:#cc3300"><?=$MSG?></h2>
        <table cellpadding="0" cellspacing="0" border="0" class="formtable" style="width:500px">
          <tr>
            <td align="center">
                <p>
                    Det er f&oslash;rste gang du logger ind p&aring; din konto, skift venligst dit password
                </p>
              <div style="font-size:10px">
                <form name="login" action="<?=$path_prefix?>log_in_inc.php" method="post">
                  <input type="hidden" name="location" value="<?=$location?>">
                  <input type="hidden" name="pub" value="<?=$pub?>">
            
                  Password<br>
                  <input class="login_input" type="password" name="password1" style="width:145px;">
                  
                  <script>
                    document.login.password1.focus();
                  </script>
              
                  <br>
                  Gentag password
                  <br>
                  <input type="password" class="login_input" name="password2" style="width:145px;">
                </div>
              </td>
            </tr>
            <tr>
              <td align="center"><input type="submit" class="button" name="update_password" value="Opdater"></td>
            </tr>
            </form>
          </table>

  </center>
</center>
</body>
</html>

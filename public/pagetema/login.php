<?php
require_once('../admin/util/bruger.php');
require_once('../admin/util/dba.php');
require_once('../config.php');

session_start();        
$msg = '';

if($_REQUEST['msg']) $msg = $_REQUEST['msg'];

if($_POST['update_password'])
{
  $bruger = new bruger( new dba() );
  if($_REQUEST['password1'] != $_REQUEST['password2']) 
  {
     $msg = "De to password stemmer ikke overens";
  } 
  else
  {
      $bruger->updatePassword( $_SESSION['update_password'],$_REQUEST['password1']);
      $script=($_REQUEST['ff'])? "href='".$_REQUEST['ff']."'":'reload(true)'; 
      die('<script>top.location.'.$script.'</script>');
  }
}

if($_POST['log_in']) 
{
  $bruger = new bruger( new dba() );
  $bruger->logIn( $_POST['name'], $_POST['password'] );


  if($_SESSION['bruger_id'])
  {
    if(!$_SESSION['update_password'])
    {
      $script=($_REQUEST['ff'])? "href='".$_REQUEST['ff']."'":'reload(true)'; 

      if($_SESSION['producent'] && !$_REQUEST['ff']) $script = 'href="/index.php?action=minside";';

      die('<script>top.location.'.$script.'</script>');
    }
  } else { $msg = 'Forkert navn eller password'; }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Bygviden :: Log in</title>
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
        <style>
            body { margin:0;padding:0; }
            body, td { font-family:arial,sans-serif;font-size:12px; }
            #top
            {
                height:35px;
                background-image:url('../tema/graphics/top.jpg');
                background-repeat:repeat-x;
                border-bottom:10px solid #001C43;
                margin-bottom:50px;
            }
            #msg {text-align:center;color:#C8081D;font-weight:900}
        </style>
        <script>
            function focusing()
            {
                document.getElementById('user_name').focus();
            }
        </script>
    </head>
    <body onload="focusing()">
        <div id="top"></div>
        <div id="msg"><?=$msg?></div>

        <center>
<div style="padding:10px">
            <h2>bygviden.dk outsources </h2>
<p style="width:400px">
Dansk Byggeris bestyrelse har besluttet at outsource bygviden.dk til Teknologisk Institut. Dansk Byggeri indg&aring;r i en redaktionsgruppe og har dermed indflydelse p&aring; det faglige indhold p&aring; portalen.
</p>
<p style="width:400px">
Fra den 1. maj 2009 vil det v&aelig;re muligt at logge p&aring; bygviden.dk og dermed muligt at k&oring;be og downloade publikationer.
</p>
</div>
        <!--
        <form name="myform" action="login.php" method="post">
            <input type="hidden" name="ff" value="<?=$_REQUEST["ff"]?>"/>
            <?if($_SESSION['update_password']):?>

                <?if($UPDATE_PASSWORD_MSG):?>
                    <p style="color:#cc3300"><?=$UPDATE_PASSWORD_MSG?></p>
                <?endif?>
                <p>
                    Det er f&oslash;rste gang du logger ind p&aring; din konto, skift venligst dit password
                </p>

                <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td> Password </td>
                        <td>
                            <input type="password" class="textfield" name="password1" id="user_name">
                        </td>
                    </tr>
                    <tr>
                        <td> Gentag password </td>
                        <td>
                            <input type="password" class="textfield" name="password2"><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="submit" name="update_password" class="button" value="Opdater">
                        </td>
                    </tr>
                </table>

            <?else:?>
                <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td> Brugernavn </td>
                        <td>
                            <input type="text" class="textfield" name="name" id="user_name">
                        </td>
                    </tr>
                    <tr>
                        <td> Password </td>
                        <td>
                            <input type="password" class="textfield" name="password"><br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right">
                            <input type="submit" name="log_in" class="button" value="Log ind">
                        </td>
                    </tr>
                </table>

            <?endif?>
        </form>
       -->
        </center>

    </body>
</html>

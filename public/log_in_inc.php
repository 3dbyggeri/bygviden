<?
require_once('admin/util/dba.php');
require_once('admin/util/bruger.php');

if( $_POST['update_password'])
{
  session_start();        
  $dba    = new dba();    //instantiate the objects we need
  $bruger = new bruger( $dba );


  if($_REQUEST['password1'] != $_REQUEST['password2'])
  {
    $MSG = 'De to password var ikke ens';
    require_once('view/danskeark.php');
    die();
  }
  $bruger->updatePassword( $_SESSION['update_password'],$_REQUEST['password1']);

  if( $_SESSION['bruger_id'] )
  {
    $location = ($_POST['location'])? $_POST['location']:'index.php';
    $str = "<script>document.location.href='$location';</script>"; 
    die("$str");
  }
  else
  {
    die("<script>document.location.href='view/error.php?id=10&pub=". $_REQUEST['pub'] ."';</script>");
  }
}
if( $_POST['log_in'] )
{
  session_start();        //start session
  $dba    = new dba();    //instantiate the objects we need
  $bruger = new bruger( $dba );
  $bruger->logIn( $_POST['name'], $_POST['password'] );

  if($_SESSION['update_password'])
  {
    require_once('view/danskeark.php');
    die();
  }

  if( $_SESSION['bruger_id'] )
  {
    $location = ($_POST['location'])? $_POST['location']:'index.php';
    $str = "<script>document.location.href='$location';</script>"; 
    die("$str");
  }
  else
  {
    die("<script>document.location.href='view/error.php?id=10&pub=". $_REQUEST['pub'] ."';</script>");
  }
}
?>
<div id="headline">Log ind</div>
<table cellpadding="0" cellspacing="0" border="0" class="formtable" style="width:500px">
  <tr>
    <td align="center">
      <div style="font-size:10px">

        <?if( $_GET['wronglogin'] ):?>
            <strong>Forkert brugernavn eller password !</strong><br><br>
        <?endif?>

        <form name="login" action="<?=$path_prefix?>log_in_inc.php" method="post">
          <input type="hidden" name="location" value="<?=$location?>">
          <input type="hidden" name="pub" value="<?=$pub?>">
    
          Brugernavn<br>
          <input class="login_input" type="text" name="name" style="width:145px;">
          
          <script>
            document.login.name.focus();
          </script>
      
          <br>
          Password
          <br>
          <input type="password" class="login_input" name="password" style="width:145px;">
        </div>
      </td>
    </tr>
    <tr>
      <td align="center"><input type="submit" class="button" name="log_in" value="Log ind"></td>
    </tr>
    </form>

  </table>

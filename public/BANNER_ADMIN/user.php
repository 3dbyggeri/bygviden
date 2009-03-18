<?php
include_once( "../admin/util/dba.php" );

session_start();

if(!$_SESSION['authenticated']) die('');
if($_SESSION['uid']!=1) die('');

$dba = new dba();

function get_salesman($id)
{
  global $dba;
  $sql = "SELECT * FROM ".$dba->getPrefix() ."banner_salesman where id=".$id;
  return $dba->singleArray( $sql );
}

function save_user($id,$name,$password)
{
  global $dba;
  if($id==0)
  {
    $sql ="INSERT INTO ".$dba->getPrefix()."banner_salesman (name,password) VALUES('". addslashes($name) ."','".addslashes($password). "')";
  }
  else
  {
    $sql="UPDATE ".$dba->getPrefix()."banner_salesman SET name='". addslashes($name) ."'"; 
    if($password) $sql.=",password='".addslashes($password)."'";
    $sql .= " WHERE id=".$id;
  }
  $dba->exec($sql);
  die('<script>opener.document.location.href="users.php";window.close()</script>');
}

$id = $_REQUEST['id'];
if(!is_numeric($id)) $id ='0';
$flash='';

if($_POST['save']=='1')
{
   $name = $_POST['name'];
   $password_1 = $_POST['password_1'];
   $password_2 = $_POST['password_2'];
   if(!$name) $flash='Du skal taste et navn'; 
   if($password_1 != $password_2) $flash='Det to password er ikke ens';
   if(!$password_1 && $id=='0') $flash='Du skal angive et password';

   if(!$flash) save_user($id,$name,$password_1);
}

$title = ($id=='0')? 'Oprette nye bruger':'Redigere bruger';
$user = get_salesman($id);

?>
<html>
	<head>
		<title>Bruger</title>
        <style>
            body, td { font-size:11px;font-family:verdana,sans-serif;color:#333; }
            #overview { background-color:#fff; }
            #overview .odd { background-color:#e3e3e3; }
            #overview .even { background-color:#cdcdcd; }
            .header { background-color:#999;}
            a {color:#000; }
            h1 { border-bottom:1px solid #999;font-weight:100;font-size:18px;padding-bottom:5px;margin-bottom:25px; }
            input { width:250px }
            #actions { padding-top:15px;padding-right:15px; }
            #actions input { width:100px }
            #flash {font-weight:900;size:14px;color:#cc3300; }
        </style>
	</head>
  <body bgcolor="#FFFFFF" >
        <form name="myform" method="post" action="user.php">
            <input type="hidden" name="save" value="1" />
            <input type="hidden" name="id" value="<?=$id?>" />

        <h1><?=$title?></h1>

        <div id="flash"><?=$flash?></div>

        <table width="100%" cellpadding="4" cellspacing="0" border="0">
            <tr>
                <td>
                  Navn  
                </td>
                <td>
                    <input type="text" name="name" value="<?=$user['name']?>" />
                </td>
           </tr>

            <tr>
                <td>
                  Password
                </td>
                <td>
                    <input type="password" name="password_1" value="" />
                </td>
           </tr>
            <tr>
                <td>
                  Bekr&aelig;ft password
                </td>
                <td>
                    <input type="password" name="password_2" value="" />
                </td>
           </tr>
           <tr>
            <td colspan="2" id="actions" align="right">
                <input type="button" value="Fortryd" onclick="window.close()"/>
                <input type="submit" value="Gemt" />
            </td>
           </tr>
        </table>
        </form>

  </body>
</html>

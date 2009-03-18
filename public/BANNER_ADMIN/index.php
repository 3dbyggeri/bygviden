<?php
include_once( "../admin/util/dba.php" );

$dba = new dba();
session_start();
$flash = '';
$name = trim($_POST['name']);
$password = trim($_POST['password']);
if($name && $password)
{
    $sql = "SELECT *
            FROM ".$dba->getPrefix() ."banner_salesman 
            WHERE name='".addslashes($name)."'
            AND password='".addslashes($password)."'";
    $user = $dba->singleArray( $sql );
    if($user)
    {
       $_SESSION['authenticated'] = 1; 
       $_SESSION['name'] = $user['name']; 
       $_SESSION['uid'] = $user['id']; 
       header("Location:overview.php"); 
       die('');
    }
    else
    {
        $flash="Forket navn eller password";
    }
}
session_destroy();
?>
<html>
    <head>
        <title>Bygviden</title>
        <style>
            body, td { font-size:11px;font-family:verdana,sans-serif;color:#333; }
            body { margin:30px }
            #login { text-align:left;width:400px;border:1px solid #e3e3e3; background-color:#f3f3f3;padding:20px;margin:50px;}
            input { width:250px }

            #flash {font-weight:900;size:14px;color:#cc3300; }
        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <form name="myform" method="post" action="index.php">
           <center>
           <div id="login">
                <div id="flash"><?=$flash?></div>
                <table  width="100%" cellpadding="0" cellspacing="5" border="0">
                    <tr>
                        <td>
                            Navn
                        </td>
                        <td>
                            <input type="text" name="name" />
                        </td>
                   </tr>
                    <tr>
                        <td>
                            Password
                        </td>
                        <td>
                            <input type="password" name="password" />
                        </td>
                   </tr>
                   <tr>
                    <td align="center" colspan="2" >
                        <br />
                        <input type="submit" value="Login" style="width:100px">
                    </td>
                    </tr>
                  </table>
           </div>
           </center>
        </form>
    </body>
</html>

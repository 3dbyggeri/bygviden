<?php
include_once( "../admin/util/dba.php" );

session_start();

if(!$_SESSION['authenticated']) header('Location:index.php');
if($_SESSION['uid']!=1) header('Location:overview.php');

$dba = new dba();

function get_salesman()
{
  global $dba;
  $sql = "SELECT * FROM ".$dba->getPrefix() ."banner_salesman ";
  $result = $dba->exec( $sql );
  $n      = $dba->getN( $result );
  $salesman= Array();
  for( $i = 0; $i < $n; $i++ )
  {
    $salesman[$i] = $dba->fetchArray( $result );
  }
  return $salesman;
}

if( is_numeric($_POST['remove']) )
{
    $sql = "DELETE FROM ".$dba->getPrefix()."banner_salesman WHERE id=".$_POST['remove'];
    $dba->exec( $sql );
}
?>
<html>
	<head>
		<title>Brugerne</title>
        <style>
            body, td { font-size:11px;font-family:verdana,sans-serif;color:#333; }
            #overview { background-color:#fff; }
            #overview .odd { background-color:#e3e3e3; }
            #overview .even { background-color:#cdcdcd; }
            .header { background-color:#999;}
            a {color:#000; }

            h1 { border-bottom:1px solid #999;font-weight:100;font-size:18px;padding-bottom:5px;margin-bottom:25px; }
            h1 #settings { font-size:11px;float:right; }
        </style>
        <script>
            function user(id)
            {
                var props = 'width=400,height=300,toolbar=no,directories=no,status=no';
                var w = window.open('user.php?id='+ id,'term',props);
                w.focus();
            }
            function removing(id,name)
            {
                if(!confirm('Vil du slette brugeren '+ name +'?')) return;
                document.myform.remove.value = id;
                document.myform.submit();
            }
        </script>
	</head>
  <body bgcolor="#FFFFFF" >
        <form name="myform" method="post" action="users.php">
            <input type="hidden" name="remove">
        </form>

        <h1>
            <span id="settings">
                Velkomen <?=$_SESSION['name']?> | 
                <a href="index.php">log-ud</a> 
                | <a href="overview.php">Oversigt</a>
            </span>
            Bruger Administration
        </h1>

        <table cellpadding="4" cellspacing="1" border="0" id="overview" width="500">
            <tr class="header">
                <td>S&aelig;lger</td>
                <td width="100" align="right">
                    <a href="javascript:user('0')">Tilf&oslash;je s&aelig;lger</a>
                </td>
            </tr>
            <?
                $users = get_salesman();
                $n = count($users);
            ?>
            <?for( $i = 0; $i < $n; $i++ ):?>
                <tr class="<?=($i%2==0)?'odd':'even'?>">
                    <td><?=$users[$i]['name']?></td>
                    <td align="right">
                        <a href="javascript:user('<?=$users[$i]['id']?>')">Redigere</a> | 
                        <a href="javascript:removing('<?=$users[$i]['id']?>','<?=$users[$i]['name']?>')">Slet</a></td>
                </tr>
            <?endfor?>
        </table>
  </body>
</html>

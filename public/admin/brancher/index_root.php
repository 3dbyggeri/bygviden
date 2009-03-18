<?php 
    require_once("../util/dba.php");
    require_once("../util/user.php");

    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$PHP_SELF ) $_SERVER["PHP_SELF"];
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];
    
    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");
    
    $panes = array(); //array( 'statistics'=>'Statistik');
    if( !$pane ) $pane = ""; 
    if( !$panes[ $pane ] ) $pane = "";

    switch( $pane )
    {
        case('statistics'):
            $paneinclude= 'statistics.php';
            break;
    }
?>
<html>
    <head>
        <title>Branche elements</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
		    <script language="javascript" src="../scripts/global_funcs.js"></script>
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
    <form name="tree" action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <input type="hidden" name="toggle_login" value="0">
        <input type="hidden" name="pane" value="<?=$pane?>">
        <table cellpadding="0" cellspacing="0" border="0">
              <tr>
			        <td><img src="../graphics/transp.gif" /></td>
              <?foreach( $panes as $key => $value ):?>
                <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?branche=<?=$branche?>&id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
                <td  onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?branche=<?=$branche?>&id=<?=$id?>&pane=<?=$key?>'"class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?> </td>
                <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?branche=<?=$branche?>&id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>

                <td><img src="../graphics/transp.gif" width="4"></td>
              <?endforeach?>
           </tr>
        </table>
        <?if($paneinclude ):?> 
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top">
                <?if($paneinclude ) require_once( $paneinclude )?>
                &nbsp;
                </td>
       </tr>
      </table>
      <?endif?>
    </form>
    </body>
</html>

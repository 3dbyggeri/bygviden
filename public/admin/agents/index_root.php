<?php 
    require_once("../util/dba.php");
    require_once("../util/user.php");

    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$PHP_SELF ) $_SERVER["PHP_SELF"];
    
    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");
    
    $panes = array( 'statistics'=>'Søg statistik' );
    $paneinclude= 'statistics.php';
    if( !$pane ) $pane = 'statistics';
    if( !$panes[ $pane ] ) $pane = 'statistics';

    switch( $pane )
    {
        case('statistics'):
            $paneinclude= 'statistics.php';
            break;
    }
?>
<html>
    <head>
        <title>Kilder</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <style>
          .label
          {
            color:#999999;
            font-family:verdana,sans-serif;
            font-size:10px;
            font-weight:900;
          }
          .infolinks
          {
            color:#666666;
            font-family:verdana,sans-serif;
            font-size:10px;
          }
          .description
          {
            color:#666666;
            font-family:verdana,sans-serif;
            font-size:10px;
            background-color:#e3e3e3;
          }
        </style>
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
    <form name="tree" action="<?=$PHP_SELF?>" method="post">
        <input type="hidden" name="toggle_login" value="0">
        <table cellpadding="0" cellspacing="0" border="0">
              <tr>
			        <td><img src="../graphics/transp.gif" /></td>
              <?foreach( $panes as $key => $value ):?>
                <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
                <td  onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$key?>'"class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?> </td>
                <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>

                <td><img src="../graphics/transp.gif" width="4"></td>
              <?endforeach?>
           </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="1"> <img src="../graphics/transp.gif" border="0" width="1" height="350"> </td>
                <td class="tdborder_content" valign="top">
                <?if($paneinclude ) require_once( $paneinclude )?>
                &nbsp;
                </td>
       </tr>
      </table>
    </form>
    </body>
</html>

<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
                                                     // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0
?>
<?php 
    /*********************************************************************/
    /*   bruger index.php                                                        */
    /*********************************************************************/
    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$PHP_SELF ) $_SERVER["PHP_SELF"];
    
    if( $_POST['logoff'] ) die("<script>top.document.location.href='../log.php';</script>");

    require("../util/dba.php");
    require("../util/user.php");

    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");

    $panes = array( 'bruger'=>'Bruger',
                    'forbrug'=>'Forbrug',
                    'statistics'=>'Statistik');

    if( !$pane ) $pane = "bruger";
    if( !$panes[ $pane ] ) $pane = "bruger";

    switch( $pane )
    {
        case('bruger'):
            $paneinclude='bruger.php';
            break;
        case('forbrug'):
            $paneinclude='bruger_forbrug.php';
            break;
        case('statistics'):
            $paneinclude='item_statistics.php';
            break;
    }
?>
<html>
    <head>
        <title>Bruger</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
        <script language="javascript" src="../scripts/scroll_master.js"></script>
    </head>
  <body class="content_body" bgcolor="#FFFFFF" onload="restoreScrollPosition('<?=urlencode($_SERVER['PHP_SELF'] )?>')"  onunload="saveScrollPosition( '<?=urlencode($_SERVER['PHP_SELF'])?>')">
        <table cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td><img src="../graphics/transp.gif" /></td>
            <?foreach( $panes as $key => $value ):?>
              <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?bruger_id=<?=$_GET['bruger_id']?>&pane=<?=$key?>'" 
                style="cursor:hand;"><img 
                src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
              <td  onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?bruger_id=<?=$_GET['bruger_id']?>&pane=<?=$key?>'" 
              class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" 
              style="cursor:hand;" ><?=$value?> </td>
              <td onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?bruger_id=<?=$_GET['bruger_id']?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>

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
    </body>
</html>

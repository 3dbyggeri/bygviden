<?php 
    $id = $_REQUEST["id"];
    $pane = $_REQUEST["pane"];
    
    require("../util/dba.php");
    require("../util/user.php");
    require("../util/kilde.php");
    require("../util/date_widget.php");

    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    $kilde = new kilde( $dba, $id );

    if(!$user->isLogged()) die("<script>top.document.location.href='../log.php';</script>");
    if(!is_numeric($id)) die("Parameter id spected");
    $kilde->loadProperties();

    $panes = array( "indstillinger"=>'Indstillinger','usage_current'=>'Brugsbetingelserne'); 
    // if( $kilde->type == 'publikation' ) $panes['indexing'] = 'Indeks';

    $paneinclude= 'indstillinger.php';
    if($pane) $_SESSION['pane'] = $pane;
    if( !$pane ) $pane = $_SESSION['pane']; 
    if( !$pane ) $pane = "indstillinger";
    if( !$panes[ $pane ] ) $pane = "indstillinger";

    switch( $pane )
    {
        case('indstillinger'):
            $paneinclude= 'indstillinger.php';
            break;
        case('usage'):
            $paneinclude= 'usage.php';
            break;
        case('indexing'):
            $paneinclude= 'indexing.php';
            break;
        case('usage_current'):
            $paneinclude= 'usage_current.php';
            break;
    }
?>
<html>
    <head>
        <title>Kilder</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
		    <script language="javascript" src="../scripts/scroll_master.js"></script>
    </head>
    <body bgcolor="#FFFFFF" class="content_body" onload="restoreScrollPosition('<?=urlencode($_SERVER['PHP_SELF'] )?>')"  onunload="saveScrollPosition( '<?=urlencode($_SERVER['PHP_SELF'])?>')">
        <table cellpadding="0" cellspacing="0" border="0">
              <tr>
			        <td><img src="../graphics/transp.gif" /></td>
              <?foreach( $panes as $key => $value ):?>
                <td nowrap onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/left<?=( $pane == $key )? "_selected":"_unselected"?>.gif"></td>
                <td  nowrap onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$key?>'"class="faneblad<?=( $pane == $key )? "_selected":"_unselected"?>" style="cursor:hand;" ><?=$value?> </td>
                <td nowrap onclick="document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&pane=<?=$key?>'" style="cursor:hand;"><img src="../graphics/horisontal_button/right<?=($pane==$key )? "_selected":"_unselected"?>.gif"></td>

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

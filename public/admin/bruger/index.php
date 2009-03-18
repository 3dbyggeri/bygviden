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

    $panes = array( 'brugerne'=>'Brugerne',
                    'bruger_forbrug'=>'Brugernes forbrug',
                    "leverandor_forbrug"=>'Videns leverandør forbrug');

    if( !$pane ) $pane = "brugerne";
    if( !$panes[ $pane ] ) $pane = "brugerne";

    switch( $pane )
    {
        case("brugerne"):
            $paneinclude="brugerne.php";
            break;
        case('bruger_forbrug'):
            $paneinclude='brugernes_forbrug.php';
            break;
        case('leverandor_forbrug'):
            $paneinclude='leverandor_forbrug.php';
            break;
        case('statistics'):
            $paneinclude='statistics.php';
            break;
    }
?>
<html>
    <head>
        <title>Brugerne</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
    </head>
    <body bgcolor="#FFFFFF" class="content_body">
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
    </body>
</html>

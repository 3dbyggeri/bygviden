<?php 
    /*********************************************************************/
    /*   main.php                                                        */
    /*********************************************************************/
    if( !$pane) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$PHP_SELF ) $_SERVER["PHP_SELF"];
    
    if( $_POST['logoff'] ) die("<script>top.document.location.href='../log.php';</script>");

    require("../util/dba.php");
    require("../util/user.php");

    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");

    $panes = array( 
                    //"overview"=>"Oversigt",
                    "subscribers"=>"Abonnenter",
                    "newsletters"=>"Nyhedsbrev",
                    //"advertisers"=>"Annonc&oslash;rer"
		          );
    if( !$pane ) $pane = "subscribers";
    if( !$panes[ $pane ] ) $pane = "subscribers";

    switch( $pane )
    {
        case("overview"):
            $paneinclude="overview.php";
            break;
        case("subscribers"):
            $paneinclude="subscribers.php";
            break;
        case("newsletters"):
            $paneinclude="newsletters.php";
            break;
        case("advertisers"):
            $paneinclude="advertisers.php";
            break;
    }
?>
<html>
    <head>
        <title>Home</title>
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

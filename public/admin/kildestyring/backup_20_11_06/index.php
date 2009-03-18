<?php 
    if( !$pane ) $pane = $_GET["pane"];
    if( !$pane ) $pane = $_POST["pane"];
    if( !$PHP_SELF ) $_SERVER["PHP_SELF"];
    if( !$id ) $id = $_GET["id"];
    if( !$id ) $id = $_POST["id"];
    if( !$publish_open ) $publish_open = $_GET['publish_open'];
    if( !$brugs_open ) $brugs_open = $_GET['brugs_open'];
    if( !$spider_state ) $spider_state = $_GET['spider_state'];
    
    require("../util/dba.php");
    require("../util/user.php");
    require("../util/kilde.php");
    require("../util/date_widget.php");

    session_start();
    $dba  = new dba();
    $user = new user( $dba );
    if( !$user->isLogged() ) die("<script>top.document.location.href='../log.php';</script>");
    if( !is_numeric( $id ) ) die("Parameter id spected");

    $kilde = new kilde( $dba, $id );

    if( $publish_open ) $kilde->toggle($publish_open,'publish');
    if( $brugs_open   ) $kilde->toggle($brugs_open,'brugs');
    if( $spider_state ) $kilde->toggle_spider($spider_state);
    if( $_GET['publish_state'] ) $kilde->toggle_publish( $_GET['publish_state'] );
    if( $_GET['betaling'] )      $kilde->toggle_payment( $_GET['betaling'] );
    if( $_GET['overrule_betaling'] ) $kilde->overrule_betaling( $_GET['overrule_betaling'] );

    if( $_POST["editproperties"] || $_POST['toggle_login'] )
    {
        if( $_SESSION['urls_open'] != 'n' )
        {
          $kilde->setUrl(                $_POST['kilde_url'] );
          $kilde->setForlag(             $_POST['forlag_url'] );
          $kilde->setLogo(               $_POST['logo_url'] );
          $kilde->setDescription(        $_POST['description'] );
          $kilde->setObservation(        $_POST['observation'] );
          $kilde->setAdresse(            $_POST['adresse'] );
          $kilde->setTelefon(            $_POST['telefon'] );
          $kilde->setFax(                $_POST['fax'] );
          $kilde->setMail(               $_POST['mail'] );
          $kilde->setDigitalUdgave(      $_POST['digital_udgave'] );
          $kilde->setCustomSummary(      $_POST['custom_summary'] );
          $kilde->setLogin( $_POST['log_in'], $_POST['log_name'], $_POST['log_password'] );
          $kilde->setCrawlingDepth( $_POST['crawling_depth'] );
          $kilde->setCrawlingCuantitie( $_POST['crawling_cuantitie'] );

          $kilde->setIndholdsfortegnelse( $_POST['indholdsfortegnelse'] );
          $kilde->setForbiddenWords(      $_POST['forbidden_words'] );
          $kilde->setRequiredWords(       $_POST['required_words'] );
          $kilde->setDB(                  $_POST['db'] );
          $kilde->setBetegnelse(          $_POST['betegnelse'] );
        }
        
        if( $kilde->isBrugs_open() ) $kilde->updatePayment();

        if( $kilde->isPublish_open() )
        {
          //the order of this statements is important
          if( $_POST['publishSchedule']   ) 
            $kilde->setPublishDate( $_POST['day_publish'],
                                    $_POST['month_publish'], 
                                    $_POST['year_publish'] );
          else $kilde->setPublishDate();

          if( $_POST['unpublishSchedule'] ) 
            $kilde->setUnPublishDate( $_POST['day_unpublish'],
                                      $_POST['month_unpublish'],
                                      $_POST['$year_unpublish'] );
          else $kilde->setUnPublishDate();
        }

        if( $_SESSION['branche_relevans_open'] != 'n' )
        {
          $kilde->setBrancheRelevans( $_POST['brancher'] );
        }
        $message = "Your changes has been saved";
    }

    $kilde->loadProperties();
    $publishDate = new date_widget("publish");

    $udgivelses_dato = new date_widget('udgivelse');
    $revisions_dato  = new date_widget('revision');

    if( $kilde->publishDate["y"] ) 
    {
        $publishDate->setDate( $kilde->publishDate["d"], 
                               $kilde->publishDate["m"],
                               $kilde->publishDate["y"] );
    }

    $unpublishDate = new date_widget("unpublish");

    if( $kilde->unpublishDate["y"] ) 
    {
        $unpublishDate->setDate( $kilde->unpublishDate["d"],
                                 $kilde->unpublishDate["m"],
                                 $kilde->unpublishDate["y"] );
    }
    
    $label = array('leverandor'=>'Videns leverandør indstillinger',
                   'kategori'=>'Kategori indstillinger',
                   'publikation'=>'Publikation indstillinger');
    $panes = array( "indstillinger"=>$label[$kilde->type],'statistics'=>'Statistik' );
    $paneinclude= 'indstillinger.php';
    if( !$pane ) $pane = "indstillinger";
    if( !$panes[ $pane ] ) $pane = "indstillinger";

    switch( $pane )
    {
        case('indstillinger'):
            $paneinclude= 'indstillinger.php';
            break;
        case('statistics'):
            $paneinclude= 'statistics_item.php';
            break;
    }
?>
<html>
    <head>
        <title>Kilder</title>
        <link rel="stylesheet" href="../style/style.css" type="text/css">
		    <script language="javascript" src="../scripts/scroll_master.js"></script>
        <script language="javascript">
          function toggleBetaling( ) 
          {
            betaling = document.tree.betaling.options[ document.tree.betaling.selectedIndex ].value; 
            document.location.href='<?=$_SERVER['PHP_SELF']?>?id=<?=$id?>&betaling='+ betaling;
          }
          function toggleLogin()
          {
            document.tree.toggle_login.value = 1;
            document.tree.submit();
          }

        </script>
    </head>
    <body bgcolor="#FFFFFF" class="content_body" onload="restoreScrollPosition('<?=urlencode($_SERVER['PHP_SELF'] )?>')"  onunload="saveScrollPosition( '<?=urlencode($_SERVER['PHP_SELF'])?>')">
    <form name="tree" action="<?=$PHP_SELF?>" method="post">
        <input type="hidden" name="toggle_login" value="0">
        <input type="hidden" name="id" value="<?=$id?>">
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

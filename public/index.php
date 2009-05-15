<?php

require_once('tema.inc.php');

$menu = array( 'forside'=>'Forside', 'tema'=>'Temaer', 'bygningsdel'=>'Bygningsdele', 
               'bibliotek'=>'Bibliotek', 'products'=>'Byggevarer', 'about'=>'Om bygviden.dk');

session_start();        
checkLogOut(); 
checkBrancheValg();

$current_action = findAction(); 
$current_title = $menu[$current_action];
$current_page = '?action='. $current_action;

$leftMenu = '';
$breadcrump = array();
$headline = '';
$content = '';

$inc = 'pagetema/'.$current_action.'.php';

if(file_exists($inc)) require_once($inc); 

$page = getPage($current_action);

if($_REQUEST['action'] == 'search') $add_key_word = ($_REQUEST['query'])? $_REQUEST['query']:'Søg';

if($current_action =='bibliotek') $add_key_word = 'Bibliotek';
if($current_action =='products') $add_key_word = 'Produkter';
if($current_action =='bygningsdel')
{
    $branche = $_SESSION['branche']; 
    $brancheLabel = ($brancher[$branche])? $brancher[$branche]:$brancher['general'];

    if($page->isFrontPage()) 
    { 
        $add_key_word = 'Bygningsdel - '. $brancheLabel; 
    }
    else
    {
        $add_key_word = $page->current_byg['element_name'];
    }

    $full_title = ' &#187; '. $brancheLabel .' &#187; '. $page->current_byg['element_name'];
}
if($current_action=='forside') $add_key_word =  'Bygningsdel - '.$brancher['general']; 

$add_key_word = str_replace(',','.',$add_key_word);
$add_key_word = urlencode($add_key_word);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Bygviden :: <?=$current_title?><?=$full_title?></title>
        <meta name="resource-type" content="document" />
        <meta name="robots" content="INDEX, FOLLOW" />
        <meta name="revisit-after" content="7" />
        <meta name="Rating" content="General" />
        <meta name="distribution" content="Global" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
        <link href="tema/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="tema/script.js"></script>
        <script type="text/javascript">
        <?php
            $baseurl = sprintf('http%s://%s%s',
                          (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == TRUE ? 's': ''),
                          $_SERVER['HTTP_HOST'],
                          $_SERVER['REQUEST_URI']
                        );
            $u = explode("/",$baseurl);
            $f = array_pop($u);
            $ff = explode("?",$f);
            $script_name = $ff[0];
            $baseurl = '';
            for($i=0;$i<count($u);$i++) 
            {
                if($baseurl!='') $baseurl.='/';
                $baseurl.= $u[$i];
            }
        ?>
            var GB_ROOT_DIR = '<?=$baseurl?>/tema/greybox/';
            var SERVER_NAME = '<?=$baseurl?>';
            var SCRIPT_NAME = '<?=$script_name?>';
        </script>
        <script type="text/javascript" src="<?=$baseurl?>/tema/greybox/AJS.js"></script>
        <script type="text/javascript" src="<?=$baseurl?>/tema/greybox/AJS_fx.js"></script>
        <script type="text/javascript" src="<?=$baseurl?>/tema/greybox/gb_scripts.js"></script>
        <link href="<?=$baseurl?>/tema/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
    </head>
    <!--<?=$add_key_word?>-->
    <body bgcolor="#FFFFFF" onload="resizing()" onresize="resizing()">
        <div id="header">
            <div id="top">

                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td><img src="tema/graphics/logo.gif" border="0" width="363" height="73"></td>
                            <td valign="top" id="member">
                                <?if(isLogged()):?>
                                    Velkommen <?=$_SESSION['bruger_navn']?> | 
                                    <a href="?action=minside">Profil &#187;</a>
                                <?endif?>
                            </td>
                       </tr>
                    </table>

                    <form method="post" name="lg"  
                        action="?action=<?=findAction()?>"><input type="hidden" 
                        name="log_out" value="1"></form>
            </div>
            <div id="msg">
            </div>
            <form id="cse-search-box" name="searching" action="/">
            <table id="topmenu" width="100%" cellpadding="2" cellspacing="0" border="0">
                <tr>
                    <td valign="top" class="menu">
                        <ul>
                            <?$idx=0;?>
                            <?foreach($menu as $action=>$label):?>
                                <li>
                                    <a href="?action=<?=$action?>"><?=$label?></a>
                                    <?if($idx < count($menu) - 1):?>
                                        <span class="spacer">|</span>
                                    <?endif?>
                                </li>
                                
                                <?$idx++;?>
                            <?endforeach?>
                        </ul>
                    </td>
                    <td class="login" valign="top" align="right" nowrap>
                            <?if(isLogged()):?>
                                <a href="javascript:document.lg.submit()">Log out</a>
                            <?else:?>
                                <a href="pagetema/login.php" onclick="return GB_show('Log in', this.href,300,400)">Log in</a>
                            <?endif?>
                                <span class="spacer">|</span>
                                <input type="hidden" name="cx" value="015764437916890128434:rxnarlt6_eg" />
                                <input type="hidden" name="cof" value="FORID:10;NB:1" />
                                <input type="hidden" name="ie" value="ISO-8859-1" />
                                <input type="hidden" name="action" value="search" />
                                <input type="text" name="q" size="31" />
                                <a href="javascript:document.searching.submit()">S&oslash;g</a>
                    </td>
               </tr>
            </table>
            </form>
        </div>

        <div id="content">
                
            <table width="98%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td id="left_content" valign="top">

                        <div id="headline"><?=$page->Headline()?></div>
                        <?=$page->Content()?>

                    </td>
                    <td valign="top" id="right_content">
                        <?=$page->rightMenu()?>

                        <div id="rightgoogleadd" style="margin-left:15px">
                        <script type="text/javascript"><!--
                            google_ad_client = "pub-6194810790403167";
                            google_alternate_color = "FFFFFF";
                            google_ad_width = 180;
                            google_ad_height = 150;
                            google_ad_format = "180x150_as";
                            google_ad_type = "text";
                            google_ad_channel = "";
                            google_color_border = "FFFFFF";
                            google_color_bg = "FFFFFF";
                            google_color_link = "1F4569";
                            google_color_text = "000000";
                            google_color_url = "be150a";
                            google_ui_features = "rc:0";
                            //-->
                        </script>
                        <script type="text/javascript"
                          src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                        </div>
                    </td>
               </tr>
            </table>


        </div>
        <div id="footer">

            <div id="logoer"><img src="/graphics/transp.gif" border="0" width="1" height="1" /></div>
            <table id="banner" width="100%" border="0">
                <tr> 
                    <td id="copyright" valign="top">
                        <a href="javascript:alert('<?=$add_key_word?>')"><img src="/graphics/transp.gif" border="0" width="5" height="5" /></a>
                        <a href="?action=about&item=copy">&copy; Copyright bygviden.dk 2002</a> | 
                        <a href="?action=about&item=ansvar">Ansvarsfraskivelse</a> | 
                        <a href="?action=about&item=kontakt">Kontakt</a> | 
                        <a href="?action=about&item=om">Om bygviden.dk</a> | 
                        <a href="?action=about&item=help">Hj&aelig;lp</a>
                        <br>
                        <img usemap="#partners" src="tema/graphics/partner.gif" 
                            border="0" style="border:1px solid #fff;margin-top:12px;margin-left:-3px;"/>
                        <map name="partners">
                            <area href="http://www.teknologisk.dk" target="_blank" alt="Teknologisk Institut" shape="rect" coords="0,0,123,32">
                            <area href="http://www.danskbyggeri.dk" target="_blank" alt="Dansk Byggeri" shape="rect" coords="123,0,217,32">
                        </map>
                    </td>
                    <td align="right" nowrap>

                        <!-- "BygViden_venstre" (section "soegord") -->
                       <!--
                        <script type="text/javascript" language="JavaScript" src="http://e2.emediate.se/eas?cu=3915;cre=mu;js=y;target=_new;sw=<?=$add_key_word?>"></script>
                        <noscript>
                        <a target=_blank href="http://e2.emediate.se/eas?cu=3915;ty=ct;target=_new;sw=<?=$add_key_word?>"><img src="http://e2.emediate.se/eas?cu=3915;cre=img" border="0" alt="EmediateAd" width="250" height="60"></a>
                        </noscript>
                        -->
                        <!--<img src="tema/graphics/add1.gif" border="0" />-->

                        <!-- "BygViden_hojre" (section "soegord") -->
                       <!--
                        <script type="text/javascript" language="JavaScript" src="http://e2.emediate.se/eas?cu=3916;cre=mu;js=y;target=_new;sw=<?=$add_key_word?>"></script>
                        <noscript>
                        <a target=_blank href="http://e2.emediate.se/eas?cu=3916;ty=ct;target=_new;sw=<?=$add_key_word?>"><img src="http://e2.emediate.se/eas?cu=3916;cre=img" border="0" alt="EmediateAd" width="250" height="60"></a>
                        </noscript>
                        -->
                        <!--<img src="tema/graphics/add2.gif" border="0" />-->
                    </td>
                </tr>
            </table>
        </div>

<?php
if($_SERVER['HTTP_HOST'] == "www.bygviden.dk") {
?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8100842-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<?php
}
?>


    </body>
</html>

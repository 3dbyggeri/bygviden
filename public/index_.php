<?php
require_once('admin/util/bruger.php');
require_once('admin/util/dba.php');
require_once('page/page.php');
require_once('page/search.php');
include_once('page/aci.php');
require_once('page/publication.php');
require_once('config.php');

/*
if($_SERVER['REMOTE_ADDR'] != '83.93.93.216')
{
 die("<br><br><center><img src='graphics/bygvidenlogo_stor.gif'><br><br>Vi er igang med at opdatere bygviden. Kom igen senere.</center>");
}
*/

function isLogged()
{
return $_SESSION['bruger_id']? true:false;
}

function checkLogOut()
{
  // check if the user wants to log out
  if($_GET['log_out']) session_unset() ; 
}
function checkLogIn()
{
  if($_POST['update_password'])
  {
      session_start();        
      $bruger = new bruger( new dba() );
      if($_REQUEST['password1'] != $_REQUEST['password2']) return;
      $bruger->updatePassword( $_SESSION['update_password'],$_REQUEST['password1']);
  }
  if(!$_POST['log_in']) return;
  $bruger = new bruger( new dba() );
  $bruger->logIn( $_POST['name'], $_POST['password'] );
}
function checkBrancheValg()
{
  if(!$_SESSION['branche'] && $_COOKIE['branche'] ) $_SESSION['branche'] = $_COOKIE['branche'];

  if($_GET['fag'])
  {
      $_SESSION['branche'] = $_GET['fag'];
      unset($_SESSION['element']);
      setcookie ('branche', $_GET['fag'], time()+86400* 1000 ); //expire in a year
      return;
  }
  if(!$_SESSION['branche']) $_SESSION['branche'] ='general';
}

function findAction()
{
  if($_GET['action']) return $_GET['action'];
  if($_POST['action']) return $_POST['action'];
  if($_SESSION['branche']) return 'bygningsdel';
  return 'bygvningsdel';
}
function getPage($action)
{
  switch($action)
  {
    case('bygningsdel'): return new Bygningsdel();
    case('search'): return new Search();
    case('minside'): return new MinSide();
    case('bibliotek'): return new Bibliotek();
    case('products'): return new Produkter();
    case('about'): return new About();
    case('tolerancer'): return new Tolerancer();
  }
  return new View();
}

session_start();        
checkLogOut(); 
checkLogIn(); 
checkBrancheValg();

$current_action = findAction(); 

$titles = array(
               'bygningsdel'=>'Bygningsdel',
               'search'=>'S&oslash;g',
               'bibliotek'=>'Bibliotek',
               'minside'=>'Bruger side',
               'products'=>'Produkter',
               'tolerancer'=>'Tolerancer',
               'about'=>'Om Bygviden');
$current_title = $titles[$current_action];
$current_page = 'index.php?action='. $current_action;

$leftMenu = '';
$breadcrump = array();
$headline = '';
$content = '';

$inc = 'page/'.$current_action.'.php';
if(file_exists($inc)) require_once($inc); 
$page = getPage($current_action);

$add_key_word = '';
$full_title =  '';

if($_REQUEST['action']=='bygningsdel')
{
    $add_key_word = $page->Headline();
}
if($current_action =='bygningsdel')
{
    $branche = $_SESSION['branche']; 
    $brancheLabel = ($brancher[$branche])? $brancher[$branche]:$brancher['general'];
    if($page->isFrontPage())
    {
        $add_key_word = 'Bygningsdel - '. $brancheLabel; 
    }

    $full_title = ' &#187; '. $brancheLabel .' &#187; '. $page->full_title();
}
if($current_action =='bibliotek') $add_key_word = 'Bibliotek';
if($current_action =='products') $add_key_word = 'Produkter';

if($_REQUEST['action'] == 'search')
{
    $add_key_word = ($_REQUEST['query'])? $_REQUEST['query']:'S?g';

}
$add_key_word = str_replace(',','.',$add_key_word);
$add_key_word = urlencode($add_key_word);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title>Bygviden :: <?=$current_title?><?=$full_title?></title>
<meta name="resource-type" content="document" />
<meta name="generator" content="WIZI|CMS" />
<meta name="robots" content="INDEX, FOLLOW" />
<meta name="revisit-after" content="7" />
<meta name="Rating" content="General" />
<meta name="distribution" content="Global" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<link href="styles/links.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="script.js"></script>
</head>

<script>
</script>

<body>
	<table cellpadding="1" cellspacing="0" border="0" height="100%" width="100%">
        <tr>
            <td colspan="2" align="center" height="90" valign="top" style="background-color:#291B6B;" >

                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding-left:4px" align="left">


                            <!-- "BygViden_venstre" (section "soegord") -->
                            <script type="text/javascript" language="JavaScript" src="http://e2.emediate.se/eas?cu=3915;cre=mu;js=y;target=_new;sw=<?=$add_key_word?>"></script>
                            <noscript>
                            <a target=_blank href="http://e2.emediate.se/eas?cu=3915;ty=ct;target=_new;sw=<?=$add_key_word?>"><img src="http://e2.emediate.se/eas?cu=3915;cre=img" border="0" alt="EmediateAd" width="250" height="60"></a>
                            </noscript>
                            
                        </td>
                        <td align="center" style="background:url('graphics/blue_red_bg.gif');background-repeat:repeat-y;background-position:center">
                            <a href="http://www.bygviden.dk"><img src="graphics/bygvidenlogo_stor.gif" border="0"></a>
                        </td>
                        <td align="right" style="padding-right:4px;background-color:#CD010D" >

                            <!-- "BygViden_hojre" (section "soegord") -->
                            <script type="text/javascript" language="JavaScript" src="http://e2.emediate.se/eas?cu=3916;cre=mu;js=y;target=_new;sw=<?=$add_key_word?>"></script>
                            <noscript>
                            <a target=_blank href="http://e2.emediate.se/eas?cu=3916;ty=ct;target=_new;sw=<?=$add_key_word?>"><img src="http://e2.emediate.se/eas?cu=3916;cre=img" border="0" alt="EmediateAd" width="250" height="60"></a>
                            </noscript>

                        </td>
                   </tr>
                </table>


            </td>
        </tr>

		<tr>
			<td  valign="top" width="200px">
				<div id="lefttilforsiden"><a href="index.php?action=bygningsdel&element=1"   
                  class="tilforside"><img src="graphics/tilforsideikon.gif" 
                  class="imgforsideikon" alt="Tilbage til forsiden" height="21" 
                  width="13" border="0" align="left" />Tilbage til forsiden</a>
               </div>

                
                <? // ======================= TREE ======================//?>
                <?if($current_action=='bygningsdel'):?>
                    <?foreach($brancher as $key=>$value):?>
                        <a id="fag_chooser" href="?fag=<?=$key?>"><?=$value?></a>
                        <?if($key==$_SESSION['branche']):?>
                            <?=$page->LeftMenu()?>
                        <?endif?>
                    <?endforeach?>
                <?else:?>
                    <?=$page->LeftMenu()?>
                <?endif?>

                <img src="graphics/transp.gif" width="200" height="0">

                <div style="padding-left:10px">
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
			<td id="rightcontainer">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
                    <tr>
						<td id="navigation">
                              <? // ======================= TOP NAVIGATION ==================== // ?>
                              <?$itemNumber = 0?>
                              <?foreach($menu as $action=>$label):?>
                                  <?$itemNumber++?>
                                  <div id="naviitem<?=$itemNumber?>" 
                                  class="navi<?=($action==$current_action)?'active':''?>"><a href="index.php?action=<?=$action?>" 
                                  class="naviitem"><?=$label?></a></div>
                              <?endforeach?>
                              <? // ===================== END TOP NAVIGATION ==================== // ?>
						</td>
                        <td id="navigation" align="right">


                          <?if(isLogged()):?>
                           <span id="welcome_user">Velkommen <?=$_SESSION['bruger_navn']?></span>
                          <?elseif($_SESSION['update_password']):?>
                           <a href="javascript:login_form()" class="naviitem" style="font-size:11px;font-weight:900">Skift dit password</a>
                          <?else:?>
                           <a href="javascript:login_form()" class="naviitem" id="log_in">LOG IND</a>
                          <?endif?>
                            
                        </td>
					</tr>
					<tr>
						<td id="content">
                                <img src="graphics/transp.gif" width="550" height="0">
							    <div style="margin-left:10px">

								<div id="headline"><?=$page->Headline()?></div>
                                <?=$page->Content()?>

                                <div class="bottom" style="font-size:9px;font-weight:normal;margin-top:20px">
                                    <a href="http://www.danskbyggeri.dk" target="_blank"><img src="graphics/dansk_byggeri.gif" 
                                        alt="Dansk Byggeri" border="0"  style="margin-right:20px" /></a>

                                    <a href="http://www.danskeark.org" target="_blank"><img src="graphics/danskeark_logo.gif" 
                                        alt="Danske Arkitektvirksomheder" border="0" align="absbottom" style="margin-right:20px;margin-bottom:-10px" /></a>

                                    <a href="http://www.frinet.dk" target="_blank"><img src="graphics/fri_logo.gif" 
                                        alt="FRI Foreningen af R&aring;dgivende Ingeni&oslash;rer" border="0"  style="margin-right:20px" /></a>

                                    <a href="http://www.kraks.dk" target="_blank"><img src="graphics/kraks_logo.gif" 
                                        alt="Kraks" border="0"  /></a>

                                    <p style="margin-top:15px">
                                        <a href="index.php?action=about&item=copy">&copy; Copyright bygviden.dk 2002</a>
                                         - <a href="index.php?action=about&item=ansvar">Ansvarsfraskrivelse</a>
                                         - <a href="index.php?action=about&item=kontakt">Kontakt</a>
                                         - <a href="index.php?action=about&item=om">Om bygviden.dk</a>
                                         - <a href="index.php?action=about&item=help">Hj&aelig;lp</a>
                                    </p>
                                </div>

                                </div>
						</td>
                        <td valign="top" style="background-color:#F6F7E7;">
							<div>
                                <?require_once('page/rightColumn.php')?>
                            </div>
                            <img src="graphics/transp.gif" width="300" height="0">
                        </td>
					</tr>
				</table>


			</td>
		</tr>
	</table>
  <script language="JavaScript" src="Analyze.js"></script>
  <script language="JavaScript" type="text/javascript" src="http://ssl.siteimprove.com/js/siteanalyze.js"></script>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2127559-1";
urchinTracker();
</script>
</body>
</html>




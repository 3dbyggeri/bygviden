<?php
require("../util/dba.php");
require("../util/user.php");
require_once('Newsletter.php');
require_once("../../chimp/inc/MCAPI.class.php");

session_start();
$dba  = new dba();
$user = new user( $dba );
if( !$user->isLogged() ) die("{'status':'ERROR','msg':'Not logged in'}");

$id = $_GET['id'];

if(!$id) die("{'status':'ERROR','msg':'Newsletter id missing'}");
if($_GET['test_email']) sendTestNewsLetter($id,$_GET['test_email']);
if($_GET['send']) sendNewsLetter($id);
if($_GET['preview']) preview($id);
if($_GET['list']) listcampaigns();

function listcampaigns()
{
	$api = new MCAPI('rim@danskbyggeri.dk','danskbyggeri');
  $c = $api->campaigns(); 
  echo '<xmp>';
  print_r($c);
  echo '</xmp>';
  die('xx');
}
function preview($id)
{
  $c= buildContent($id);
  die($c['html']);
}
function setUpCampaign($id,$api)
{
  $c= buildContent($id);
	$lists = $api->lists();
	$list_id = $lists[0]['id'];

    for($i = 0; $i < count($lists);$i++)
    {
        if($lists[$i]['name'] == 'Bygviden Nyhedsbrev') $list_id = $lists[$i]['id'];
    }

  $subject = $c['name'];
  $from_email = 'bygviden@danskbyggeri.dk';
  $from_name ='Bygviden Nyhedsbrev';

  $cid = $api->campaignCreate($list_id,
                              $subject,
                              $from_email,
                              $from_name,
                              array('html'=>$c['html'],'text'=>$c['text'])
                              ); 

  if(!$cid)
  {
    $msg ='Error code:'. $api->errorCode ."\n";
    $msg.='Error message:'. $api->errorMessage."\n";
    die("{'status':'ERROR','msg':$msg}");
  }
  return $cid;
}
function sendTestNewsLetter($id,$email)
{
	$api = new MCAPI('rim@danskbyggeri.dk','danskbyggeri');
  $cid = setUpCampaign($id,$api);

  if(!$api->campaignSendTest($cid, array($email)))
  {
    $msg ='Error code:'. $api->errorCode ."\n";
    $msg.='Error message:'. $api->errorMessage."\n";
    die("{'status':'ERROR','msg':$msg}");
  }

  die("{'status':'OK','msg':'Test Kampagne sendt'}");
}
function sendNewsLetter($id)
{
	$api = new MCAPI('rim@danskbyggeri.dk','danskbyggeri');
  $cid = setUpCampaign($id,$api);

  if(!$api->campaignSendNow($cid))
  {
    $msg ='Error code:'. $api->errorCode ."\n";
    $msg.='Error message:'. $api->errorMessage."\n";
    die("{'status':'ERROR','msg':$msg}");
  }

  //register the time the campaign was sendt
  //and the campaign id
  $newsletter = new newsletter(new dba());
  $newsletter->mailed($id,$cid);

  die("{'status':'OK','msg':'Kampagne sendt'}");
}
function buildContent($id)
{
    $newsletter = new newsletter(new dba());

    $props = $newsletter->load($id);
    $paragraphs = $newsletter->loadParagraphs($id);

    $t = $props['name'] .'\n';
    $t.= '-------------------------------------------------------------------\n';

    $m = '<html><head><title>Bygviden :: '.$props['name'].'</title>';
    $m.= '<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />';
    $m.= '<style>body, td {font-family:verdana,sans-serif;font-size:12px;color:#333;}; </style>';
    $m.='</head><body style="background-color:#f8f8f8">';
    $m.='<center>';
    $m.='<table width="700" cellpadding="0" cellspacing="0" style="border:1px solid #999;margin:20px;margin-top:0">';
    $m.='<tr><td style="background-color:#ffffff">';
    $m.='<div><a href="http://www.bygviden.dk"><img src="http://www.bygviden.dk/toplogo700.jpg" border="0" /><a></div>';
    $m.='<div style="padding:20px;text-align:left">';
    $m.= '<h1>'.$props['name'].'</h1>';
    $m.= '<table cellpadding="4" cellspacing="1" border="0">';
    
    $m.='<tr><td valign="top" width="450" style="padding-right:15px">';
    for($i=0;$i < count($paragraphs['1']);$i++)
    {
      $m.='<p>'.$paragraphs['1'][$i]['body'].'</p>';
      $t.= strip_tags($paragraphs['1'][$i]['body'],'<a>'); //translate links
    }
    $m.='</td><td valign="top" width="220" style="padding-left:15px;border-left:1px solid #e3e3e3">';

    $t.= '\n-------------------------------------------------------------------\n';
    for($i=0;$i < count($paragraphs['2']);$i++)
    {
       $m.='<p>'.$paragraphs['2'][$i]['body'].'</p>';
       $t.= strip_tags($paragraphs['2'][$i]['body'],'<a>'); //translate links
    }
    $m.='</td></tr></table>';
    
    $m.='<p>&Oslash;nsker du ikke l&aelig;ngere at modtage nyhedsbrevet, klik <a href="*|UNSUB|*">her</a></p>';
    $m.='<p>*|REWARDS|*</p>';
    $m.='</div>';
    $m.='</td></tr></table>';
    $m.='<div style="margin-bottom:5px;font-size:10px;color:#999;width:700px;text-align:center">&copy; Copyright bygviden.dk 2002</div>';
    $m.='</center>';
    $m.='</body></html>';

    $t.= '\n-------------------------------------------------------------------\n';
    $t.= '*|UNSUB|*\n*|REWARDS|*';


    $m = str_replace('../..//','http://www.bygviden.dk/',$m);
    $t = str_replace('../..//','http://www.bygviden.dk/',$t);

    $m = str_replace('../../','http://www.bygviden.dk/',$m);
    $t = str_replace('../../','http://www.bygviden.dk/',$t);
    
    //replace the a tag with a couple of []
    
    $content = array('html'=>$m,'text'=>$t,'name'=>$props['name']);
    return $content;
}
?>

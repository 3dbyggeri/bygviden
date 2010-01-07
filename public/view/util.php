<?php
function displayFreeDocument($uri,$kilde)
{
  global $_GET,$_SESSION;

  // added by JL 2010-01-06
  $dba  = new dba();
  $bruger = new bruger( $dba );
  $bruger->setId( $_SESSION['bruger_id'] );
  $pris = 0;
  $periode = 0;

  $doc_url = ( $uri )? $uri: $kilde->kilde_url;
  $title   = ( $_GET['title'] )? $_GET['title']: $kilde->name;
  if( !$doc_url ) die("Missing url in free charge");

  if( stristr( $doc_url , 'TIL_BYG_CONTENT' ) )
  {
    //log the purchase - added by JL 2010-01-06
    $bruger->purchase( $_GET['pub'],$doc_url,$title, $pris, $periode );
    $_SESSION['retrieve_name'] = 'autonomy'; 
    $_SESSION['retrieve_password'] = '32ReCvQa';
    $_SESSION['retrieve_url']  = $doc_url;
    Header('Location:retrieve.php');
  }
  elseif( $kilde->log_in == 'y' )
  {
    $_SESSION['retrieve_name'] = $kilde->log_name; 
    $_SESSION['retrieve_password'] = $kilde->log_password;
    $_SESSION['retrieve_url']  = $doc_url;
    Header('Location:retrieve.php');
  }
  else
  {
    die("<script>document.location.href='$doc_url';</script>");
  }
  exit();
}

function fetchBIPS()
{
  Header("Location:retrievePDF.php");
}

function fetchMBK()
{
  $msg='
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html><head><title>MBK Kataloget</title>
        <link href="../styles/main.css" rel="stylesheet" type="text/css" />
        <link href="../styles/links.css" rel="stylesheet" type="text/css" />
        </head>
        <body>
          <div id="topbar">
            <img src="../graphics/bygvidenlogo.gif" alt="Bygviden.dk" height="28" width="79" 
              border="0" class="toplogo" />
            <img src="../graphics/bygvidenslogan.gif" alt="Bygviden strukturerer og formidler byggeteknisk viden efter dine behov" 
            height="28" width="419" border="0" class="topslogan" />
          </div>
          <div style="text-align:center;margin:40px;">
            <a href="http://www.teknologisk.dk/byggeri/mbk/katalog">Klik her for at se MBK kataloget</a>
          </div>
        </body>
        </html>';
  die($msg);
}

function fetchVOTContent($doc_url)
{
  $vot = str_replace( 'http://vot.teknologisk.dk/','',$doc_url); 
  $str = '
  <html><head><title>VOT</title></head><body>
  <form name=vot method=post action="http://vot.teknologisk.dk/'.$vot.'">
    <input type="hidden" name="username" value="byg">
    <input type="hidden" name="password" value="si355">
  </form>
  <script>document.vot.submit();</script>
  </body>
  </html>';
  die($str);
}
function fetchPasswordProtectedContent($kilde)
{
  $_SESSION['retrieve_name'] = $kilde->log_name; 
  $_SESSION['retrieve_password'] = $kilde->log_password;
  Header("Location:retrieve.php");
}
function fetchLocalContent()
{
  $_SESSION['retrieve_name'] = 'autonomy'; 
  $_SESSION['retrieve_password'] = '32ReCvQa';
  Header("Location:retrieve.php");
}
function knowledgeSupplierPayment($kilde,$payment)
{
  $payment['betaling'] =($kilde->lev_betaling == 'y')? true:false;

  if($kilde->lev_enkelt_betaling == 'y')
  {
    $payment['enkelt_betaling'] = true;
    $payment['enkelt_pris'] =($kilde->lev_enkelt_pris)? $kilde->lev_enkelt_pris:0;
  }
  if($kilde->lev_abonament_betaling == 'y')
  {
    $payment['abonament_betaling']=true;
    $payment['abonament_pris'] =($kilde->lev_abonament_pris)? $kilde->lev_abonament_pris:0;
    $payment['abonament_periode'] = $kilde->lev_abonament_periode;
  }

  if($kilde->lev_bruger_rabat =='y') $payment['bruger_rabat'] = 'y';
  return $payment;
}

function categoryPayment($kilde,$payment)
{
  $payment['betaling'] =($kilde->cat_betaling == 'y')? true:false;

  if($kilde->cat_enkelt_betaling == 'y')
  {
    $payment['enkelt_betaling'] = true;
    $payment['enkelt_pris'] =($kilde->cat_enkelt_pris)? $kilde->cat_enkelt_pris:0;
  }
  if($kilde->cat_abonament_betaling == 'y')
  {
    $payment['abonament_betaling'] = true;
    $payment['abonament_pris'] =($kilde->cat_abonament_pris)? $kilde->cat_abonament_pris:0;
    $payment['abonament_periode'] = $kilde->cat_abonament_periode;
  }
  if($kilde->cat_bruger_rabat =='y') $payment['bruger_rabat'] = 'y';
  return $payment;
}

function publicationPayment($kilde,$payment)
{
  $payment['betaling'] = ($kilde->betaling == 'y')? true:false;
  if($kilde->enkelt_betaling == 'y')
  {
    $payment['enkelt_betaling'] = true;
    $payment['enkelt_pris'] =($kilde->enkelt_pris)? $kilde->enkelt_pris:0;
  }
  if($kilde->abonament_betaling == 'y')
  {
    $payment['abonament_betaling'] = true;
    $payment['abonament_pris'] =($kilde->abonament_pris)? $kilde->abonament_pris:0;
    $payment['abonament_periode'] = $kilde->abonament_periode;
  }
  if($kilde->bruger_rabat =='y') $payment['bruger_rabat'] = 'y';
  return $payment;
}
?>

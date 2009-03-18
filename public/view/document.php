<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
                                                     // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0

$uri = urldecode( $_GET['uri'] );
$current_page = 'document';
$publication_id = $_GET['pub']; 

require_once('util.php');
?>
<?php
/********************** Document.php *****************************
  -required parameters:
  pub ( publications id )

  -optional parameters:
  doc ( autonomy document id )
  url ( document url )
  title ( document title )

  steps:
  -load the publications properties
  check if the publication is active
  check if it cost money and wich payment models it supports

  -if its free and not password protected:
    - if there is an url parameter, load the url
      else load the publication url

  -if it cost money
    - Present the user for the payment methods if there is more than
      one
    - Check the users balance
    - Substract the amount from the users account

    - fetch the publication 
*****************************************************************/
if( !is_numeric( $_GET['pub'] ) ) Header("Location:error.php?id=2");

//load publication props
require_once("../admin/util/dba.php");
require_once("../admin/util/kilde.php");
require_once('../admin/util/bruger.php');

# define the id of the videns leverand?r
$TOP_ID = 1372;
# define the id for the vitus studens
$VITUS_BERING_ID = 9330;

session_start();


//check if this is a producer
if($_SESSION['producent']) 
{
    Header('Location:error.php?id=12&pub='.$_GET['pub'] );
    die();
}

$dba  = new dba();

// Instantiate the kilde object
$kilde = new kilde( $dba, $_GET['pub'] );
$kilde->loadProperties();

//check if publish
if(!$kilde->publish) Header('Location:error.php?id=1');   


//check if there is an url
if(!$kilde->kilde_url) Header('Location:error.php?id=3');   


//check brugsbetingelserne
if( $kilde->brugsbetingelser == 'resume_alle' ) Header('Location:error.php?id=4');

//******************** RESOLVE PAYMENT *********************//
$betaling = false;
$enkelt_betaling   = false;
$enkelt_pris       = 0;
$abonament_betaling = false;
$abonament_pris    = 0;
$abonament_periode = '';

$payment_template = array(
                      'betaling'=>false,
                      'enkelt_betaling'=>false,
                      'enkelt_pris'=>0,
                      'abonament_betaling'=>false,
                      'abonament_pris'=>0,
                      'abonament_periode'=>''
                    );
$payment_status = $payment_template;

$brugsbetingelser = $kilde->lev_brugsbetingelser;
if(!$brugsbetingelser) $brugsbetingelser = $kilde->cat_brugsbetingelser;
if(!$brugsbetingelser) $brugsbetingelser = $kilde->brugsbetingelser;

#check if betaling is define for the knowledge supplier
if($kilde->lev_overrule_betaling == 'y') 
{
    $payment_status = knowledgeSupplierPayment($kilde,$payment_template);
    $brugsbetingelser = $kilde->lev_brugsbetingelser;
}

#check if betaling is define for the category
if($kilde->cat_overrule_betaling == 'y') 
{
    $payment_status = categoryPayment($kilde,$payment_template);
    $brugsbetingelser = $kilde->cat_brugsbetingelser;
}

#check if betaling is define for the publication
if($kilde->overrule_betaling == 'y') 
{
    $payment_status = publicationPayment($kilde,$payment_template);
    $brugsbetingelser = $kilde->brugsbetingelser;
}




//check if the user is logged in and is a member
if( !$_SESSION['bruger_id'] && $brugsbetingelser == 'fuldtekst_medlemmer' ) 
{
    Header('Location:error.php?id=5&pub='.$_GET['pub'] );
    die();
}


# No payment display the document
if(!$payment_status['betaling'] && $brugsbetingelser != 'fuldtekst_medlemmer') 
{
  require_once('../loggin.php');
  displayFreeDocument($uri,$kilde);
}

//check if the user is logged
if( !$_SESSION['bruger_id']  ) 
{
    Header("Location:error.php?id=6&pub=".$_GET['pub']);
    die();
}

//check if the publication is free for all log-in users
if( $payment_status['bruger_rabat'] != 'y' && $payment_status['enkelt_pris'] == 0 && $payment_status['abonament_pris'] == 0)
{
  require_once('../loggin.php');
  displayFreeDocument($uri,$kilde);
}


//check if the user have allready paid for this publikation
//if he has show him the doc
$bruger = new bruger( $dba );
$bruger->setId( $_SESSION['bruger_id'] );
$doc_url = ( $uri )? $uri: $kilde->kilde_url;
$title   = ( $_GET['title'] )? $_GET['title']: $kilde->name;


if( stristr( $doc_url , 'TIL_BYG_CONTENT/MBK' ) ) 
{
  displayFreeDocument($uri,$kilde);
  die();
}

if( $bruger->hasPaid($kilde, $doc_url))
{
  require_once('../loggin.php');
  if(!$doc_url) die('Missing url parameter in user has paid');
  $_SESSION['retrieve_url']  = $doc_url;

  if( stristr( $doc_url , 'TIL_BYG_CONTENT' ) ) 
  {
    fetchLocalContent();
    die();
  }
  if( $kilde->log_in == 'y' ) 
  {
    fetchPasswordProtectedContent($kilde);
    die();
  }

  if( stristr( $doc_url , 'BipsDanskByggeri' ) ) 
  {
    fetchPasswordProtectedContent($kilde);
    die();
  }

  if( stristr( $doc_url , 'http://vot.teknologisk.dk' ) ) 
  {
    fetchVOTContent($doc_url);
    die();
  }

  die("<script>document.location.href='$doc_url';</script>");
}

//check if the user can afford to pay
if( !$_SESSION['is_mester'] && is_numeric( $_SESSION['clipkort'] ) )
{
  if( $kilde->abonament_pris > $_SESSION['clipkort'] || 
      $kilde->enkelt_pris > $_SESSION['clipkort'] ) 
  {
    require_once("ikke_raad.php");
    exit();
  }
}

//Catch the Vitus users here
if($_SESSION['parent'] == $VITUS_BERING_ID) if($kilde->lev_id == $TOP_ID) Header("Location:error.php?id=11");

//Show the user the price and conditions
require_once("pricing.php");
?>

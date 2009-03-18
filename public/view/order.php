<?php
//load publication props
require_once('../admin/util/dba.php');
require_once('../admin/util/kilde.php');
require_once('../admin/util/bruger.php');
require_once('util.php');
session_start();
if( !$_SESSION['bruger_id'] ) Header('Location:error.php?id=6');

$dba  = new dba();
$kilde = new kilde( $dba, $_POST['pub'] );
$kilde->loadProperties();
$bruger = new bruger( $dba );
$bruger->setId( $_SESSION['bruger_id'] );

$pris = 'nan';
$periode = 0;

//******************** RESOLVE PAYMENT *********************//
$enkelt_betaling   = false;
$enkelt_pris       = 0;
$abonament_betaling = false;
$abonament_pris    = 0;
$abonament_periode = '';

#check if betaling is define for the knowledge supplier
if( $kilde->lev_overrule_betaling == 'y' )
{
  $enkelt_betaling   = false;
  $enkelt_pris       = 0;
  $abonament_betaling = false;
  $abonament_pris    = 0;
  $abonament_periode = '';

  if( $kilde->lev_enkelt_betaling == 'y' )
  {
    $enkelt_betaling = true;
    $enkelt_pris  =( $kilde->lev_enkelt_pris )? $kilde->lev_enkelt_pris:0;
  }
  if( $kilde->lev_abonament_betaling == 'y' )
  {
    $abonament_betaling = true;
    $abonament_pris     =( $kilde->lev_abonament_pris )? $kilde->lev_abonament_pris:0;
    $abonament_periode  = $kilde->lev_abonament_periode;
  }
}

#check if betaling is define for the category
if( $kilde->cat_overrule_betaling == 'y' )
{
  $enkelt_betaling   = false;
  $enkelt_pris       = 0;
  $abonament_betaling = false;
  $abonament_pris    = 0;
  $abonament_periode = '';

  if( $kilde->cat_enkelt_betaling == 'y' )
  {
    $enkelt_betaling = true;
    $enkelt_pris  =( $kilde->cat_enkelt_pris )? $kilde->cat_enkelt_pris:0;
  }
  if( $kilde->cat_abonament_betaling == 'y' )
  {
    $abonament_betaling = true;
    $abonament_pris     =( $kilde->cat_abonament_pris )? $kilde->cat_abonament_pris:0;
    $abonament_periode  = $kilde->cat_abonament_periode;
  }
}

#check if betaling is define for the publication
if( $kilde->overrule_betaling == 'y' )
{
  $enkelt_betaling   = false;
  $enkelt_pris       = 0;
  $abonament_betaling = false;
  $abonament_pris    = 0;
  $abonament_periode = '';

  if( $kilde->enkelt_betaling == 'y' )
  {
    $enkelt_betaling = true;
    $enkelt_pris  =( $kilde->enkelt_pris )? $kilde->enkelt_pris:0;
  }
  if( $kilde->abonament_betaling == 'y' )
  {
    $abonament_betaling = true;
    $abonament_pris     =( $kilde->abonament_pris )? $kilde->abonament_pris:0;
    $abonament_periode  = $kilde->abonament_periode;
  }
}

if( $_POST['abonament'] )
{
  /*
  $periode = $kilde->abonament_periode;
  $pris = $kilde->abonament_pris;
  */
  $periode = $abonament_periode;
  $pris = $abonament_pris;
}
if( $_POST['enkelt_visning'] )
{
  //$pris = $kilde->enkelt_pris;
  $pris = $enkelt_pris;
}

if( $_POST['bruger_rabat'])
{
  $priser = $kilde->getBrugerRabatPriser();
  $pris = $priser[$_POST['bruger_interval']];
  $periode = 12;
}

if( !is_numeric( $pris ) ) Header("Location:error.php?id=7");
$doc_url = ( $_POST['uri'] )? $_POST['uri']: $kilde->kilde_url;
$title   = ( $_POST['title'] )? $_POST['title']: $kilde->name;

//log the purchase
$bruger->purchase( $_POST['pub'],$doc_url,$title, $pris, $periode );

//if user is a "svend" reduce the price from his account
//set the price ind the clipcort session
if( !$_SESSION['is_mester'] && is_numeric( $_SESSION['clipcort'] ) ) $bruger->debit( $pris );

//Show the publication
if( !$doc_url ) die("no url in user has paid");

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
if( stristr( $doc_url , 'http://vot.teknologisk.dk' ) ) 
{
  fetchVOTContent($doc_url);
  die();
}
if(stristr($doc_url,'www.teknologisk.dk/byggeri/mbk/katalog')) 
{
  fetchMBK();
  die();
}

Header("Location:$doc_url");
?>

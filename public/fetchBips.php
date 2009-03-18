<?
$url = $_REQUEST['u'];

if(stristr($url,'B210-2005-02-11.pdf'))
{
  header("Content-type: application/pdf");
  header('Content-Disposition: inline; filename="B210-2005-02-11.pdf"');
  $f = 'befaestelser.pdf';
  $f = fopen($f,'rb');
  fpassthru($f);
  exit;
}
if(stristr($url,'uranus.ramboll'))
{
  //$uname_password ="BipsDanskByggeri:BDan521Byg346";
  //$url = str_replace('uranus.ramboll.dk','www.bips.dk',$url);

  $file_name = basename($url);
  $url = "http://www.bips.dk/bipsdanskbyggeri/Published/$file_name";

  $ch = curl_init (); 
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt ($ch, CURLOPT_URL, $url); 
  //curl_setopt ($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY); 
  //curl_setopt ($ch,CURLOPT_USERPWD,$uname_password); 
  curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
  $content = curl_exec ($ch); 
  curl_close ($ch); 
  
  $fileName = basename($url);
  header("Content-type: application/pdf");
  header('Content-Disposition: inline; filename="'.$fileName.'"');
  die($content);
}
?>

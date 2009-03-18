<?
require_once("rot13.php");
if(!is_numeric($_POST['t'] ) ) die('missing timestamp');
if(time() - $_POST['t'] > 60 ) die('Session expired');
$url = rotting($_POST['u']);

$type = 'octet-stream';
if( stristr( $url, '.pdf' ) ) $type = 'pdf'; 
if( stristr( $url, '.doc' ) ) $type = 'doc'; 
if( stristr( $url, '.rtf' ) ) $type = 'rtf'; 
if( stristr( $url, '.xsl' ) ) $type = 'xsl'; 

#header("Content-Length: ".filesize($path));
if( stristr( $url,'TIL_BYG_CONTENT') )
{
  #find the size of the file
  $path_parts = pathinfo($url);
  $basename = $path_parts["basename"];
  #echo "<!--basename $basename-->";
  #remove prefix
  $name = ereg_replace('.*TIL_BYG_CONTENT/','',$url);

  #remove any fragment
  $name = ereg_replace('#page=.*','',$name);

  #echo "<!--name $name-->";
  #build the path
  $p = '../TIL_BYG_CONTENT/'. $name;
  #echo "<!--p: $p-->";
  $path = realpath('../TIL_BYG_CONTENT/'. $name );
  #echo "<!--path: $path-->";
  if(file_exists($path)) 
  {
    $z = filesize($path);
    header("Content-type: application/pdf");
    header("Content-Length: ".$z);
    header('Content-Disposition: inline; filename="bygviden_dokument.pdf"');
    #header("Content-Disposition:inline; filename=\"".trim(htmlentities($basename))."\"");
    $fd=fopen($path,'rb');
      while(!feof($fd)) {
        print fread($fd, 4096);
      }
    fclose($fd);
    die();
  }
}

if(stristr($url,'www.bips.dk/basisbeskrivelser'))
{
  $u = split("@",$url);
  $url = 'http://'.$u[1]; //get the bare url

  $u = $u[0];
  $u = split("://",$u);
  $uname_password = $u[1]; //ignore protocol and get name and password

  $ch = curl_init (); 
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt ($ch, CURLOPT_URL, $url); 
  curl_setopt ($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY); 
  curl_setopt ($ch,CURLOPT_USERPWD,$uname_password); 
  curl_setopt ($ch, CURLOPT_TIMEOUT, 300); 
  $content = curl_exec ($ch); 
  curl_close ($ch); 

  header("Content-type: application/pdf");
  header('Content-Disposition: inline; filename="document.pdf"');
  die($content);
}
if(stristr($url,'uranus.ramboll'))
{
  // http://BipsDanskByggeri:BDan521Byg346@uranus.ramboll.dk/BipsDanskByggeri/arbejdsbeskrivelser/Murværk/B211-2003-12-12.pdf
  //$u = split("@",$url);
  //$url = 'http://'.$u[1]; //get the bare url
  //$url = str_replace('uranus.ramboll.dk','www.bips.dk',$url);

  //$u = $u[0];
  //$u = split("://",$u);
  //$uname_password = $u[1]; //ignore protocol and get name and password
  $file_name = basename($url);
  $url = "http://www.bips.dk/bipsdanskbyggeri/Published/$file_name";
  $txt = '<html><body style="margin:0;padding:0"><iframe src="'. $url .'" width="100%" height="100%" frameborder="0" ';
  $txt.= ' marginwidth="0" marginheigt="0" scrolling="no" style="width:100%;height:100%"></iframe></body></html>';
  die($txt);
  //die($url);
  $ch = curl_init (); 
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt ($ch, CURLOPT_URL, $url); 
  //curl_setopt ($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY); 
  //curl_setopt ($ch,CURLOPT_USERPWD,$uname_password); 
  curl_setopt ($ch, CURLOPT_TIMEOUT, 300); 
  $content = curl_exec ($ch); 
  curl_close ($ch); 

  header("Content-type: application/pdf");
  header('Content-Disposition: inline; filename="document.pdf"');
  die($content);
}
header("Content-type: application/pdf");
$fp = @fopen( $url, "rb" );
fpassthru( $fp );
?>

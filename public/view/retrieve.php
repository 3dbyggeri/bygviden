<?php
  set_time_limit(0);
  require_once("rot13.php");
  session_start();

  if($_GET['test'])
  {
    fetchAndWaterMarkNorm('http://www.bygviden.dk/TIL_BYG_CONTENT/dansk_standart/ds_409.pdf');
    exit;
  }

  if( !$_SESSION['retrieve_name']  ||
      !$_SESSION['retrieve_password'] ||
      !$_SESSION['retrieve_url'] )
  {
    Header("Location:error.php?id=9");
  }

  $path     = pathinfo( $_SESSION['retrieve_url'] );
  $dirName  = $path["dirname"];
  $baseName = $path["basename"];
  $extension= $path["extension"];

  $baseUrl = translateUrl( $dirName,
                           $_SESSION['retrieve_name'], 
                           $_SESSION['retrieve_password']);
  
  /*
  echo "fetch url:". $url2request ."/".$baseName."<br>";  
  echo "url: $url2request <br>";
  echo "dirName: $dirName<br>";
  echo "baseName: $baseName<br>";
  echo "extension: $extension<br>";
  die();
  */

  if( $_GET['media'] )
  {
    $media = $_GET['media'];
    $media = stripslashes($media);
    $media = str_replace('\'',"",$media);
    $url = $baseUrl.'/'.$media;
    retrieveMedia($url,$extenstion); 
  }
  elseif( $_GET['page'] )
  {
    retrievePage($baseUrl.'/'.$_GET['page'] );
  }
  else
  {
     retrievePage($baseUrl.'/'.$baseName );
  }
  
  function retrieveMedia($url)
  {
      global $baseUrl; 
      $path     = pathinfo( $url );
      $extension= strtolower($path["extension"]);

      $media = $_GET['media'];
      $media = stripslashes($media);
      $media = str_replace('\'',"",$media);

      if( $extension == 'htm' ) return retrievePage($baseUrl.'/'.$media);

      //logging("Retrieveing url: $url with extension $extension\n");
      if( $extension == "jpg" || 
          $extension == "gif" || 
          $extension == "png" || 
          $extension == "css" )
      {
          Header ("Content-type: image/$extension");

          $fp = fopen( $url, "r" );
          fpassthru( $fp );
      }
  }
  function fetchAndWaterMarkNorm($url)
  {
    if(!$_SESSION['bruger_navn']) die('The session is closed');

    include_once('../admin/util/dba.php');
    include_once('../admin/util/bruger.php');

    $bestillerNavn = $_SESSION['bruger_navn'];
    $mesterNavn = '';
    if(!$_SESSION['is_mester'])
    {
      $bruger = new bruger(new dba());
      $bruger->setId( $_SESSION['parent'] );
      $props = $bruger->loadBruger();
      
      $mesterNavn = $props['firmanavn1'] .' '.$props['firmanavn2'] .' '.$props['firmanavn3'];
      $mesterNavn = (trim($mesterNavn))? trim($mesterNavn): trim($props['bruger_navn']);
    }
    
    $urlParts = explode('www.bygviden.dk',$url);
    $fileName = '/home/bygviden/docs'.$urlParts[1];
    if(! file_exists($fileName)) 
    {
      echo '<!--'.$fileName.'-->';
      echo '<!--'.$url.'-->';
      die('Kunne ikke finde pdf');
    }

    $url = "http://localhost:8080/watermarker/Watermarker";
    $params.= "stream=yes";
    $params.= "&file_name=$fileName";
    $params.= "&watermark_text=Hentet fra www.bygviden.dk af ";
    $params.= "&user_name=". $bestillerNavn;
    if($mesterNavn) $params.= ' ('. $mesterNavn .')';
    $params.= "&new_name=test.pdf";

    $ch = curl_init (); 
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt ($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt ($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_PORT,8080);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
    curl_setopt ($ch, CURLOPT_URL, $url); 
    curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
    $content = curl_exec ($ch); 
    curl_close ($ch); 
    $fileName = 'tmp_'.time().'.pdf';
    $TMP ='FREE_BYG_CONTENT'; 
    $p = realpath('../'.$TMP) .'/'.$fileName;
    $fp = fopen($p,'w');
    fwrite($fp,$content);
    fclose($fp);
    header("Location:../$TMP/$fileName");
  }

  function retrievePage($url)
  {
     if( stristr( $url, 'TIL_BYG_CONTENT/dansk_standart' ) ) return fetchAndWaterMarkNorm($url);

     if( stristr( $url, '.pdf' ) || stristr( $url, '.doc' ) || stristr( $url, '.rtf') )
     {
        $str = '<html><head><title>Dokument visning</title></head>';
        $str.= '<body onload="document.myform.submit()">';
        $str.= '<form action="retrievebin.php?type=pdf" method="post" name="myform">';
        $str.= '<input type="hidden" name="u" value="'. rotting($url) .'">';
        $str.= '<input type="hidden" name="pdf" value="1">';
        $str.= '<input type="hidden" name="t" value="'. time() .'">';
        $str.= '</form>';
        $str.= '</body></html>';
        
        die($str);
     }

     $content=""; 
     $ch = curl_init (); 
     curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
     curl_setopt ($ch, CURLOPT_URL, $url); 
     curl_setopt ($ch, CURLOPT_TIMEOUT, 60); 
     $content = curl_exec ($ch); 
     curl_close ($ch); 

     $content = replaceImg( $content );
     $content = convertRelaviteLinks( $content );
     print $content;
  }
   
  /*
  echo "fetching url ". $_SESSION['retrieve_url'] ."<br>";
  echo "with name ". $_SESSION['retrieve_name'] ."<br>";
  echo "and password ". $_SESSION['retrieve_password'] ."<br>";
  */

function convertRelaviteLinks( $str )
{
    global $ref, $url, $id;
    global $baseUrl;

    $str = stripslashes( $str );

    //replace relative links
    //$pattern = "'<a([^>]*)href=([^\w]*)([^\"]*)\.?([^\W\"]*)([^\w]*)([^<]*)>'ie"; 
    $pattern = "'<a([^>]*)href=([^\w]*)([^\"]*)\.?([^\W\"]*)([^\w]*)([^<]*)>'ie"; 

    //$str = preg_replace( $pattern,"'<a\\1  href=\"'. replaceIfRelative('\\3') .'\\4\"'. checkImg('\\6') .'>'" ,$str);
    $str = preg_replace( $pattern,"'<a\\1  href=\\2'. replaceIfRelative('\\3') .'\\4\"'. checkImg('\\6') .'>'" ,$str);
    $str = stripslashes( $str );

    //replace MBK javascript links
    $str = replaceMBKJavaScript($str);

    $pattern = "'<link([^>]*)href=([^\w]*)([^\"]*)\.?([^\W\"]*)([^\w]*)([^<]*)>'ie";
    $str = preg_replace( $pattern,"'<link\\1  href=\"'. replaceIfRelative('\\3','media') .'\\4\"'. checkImg('\\6') .'>'" ,$str);

    $str = str_replace(' _parent','_parent',$str);
    $str = stripslashes( $str );

    return $str;
}
function replaceMBKJavaScript($content)
{
  global $ref, $url, $id;
  $base = '';
  $d = ($_GET['page'])? $_GET['page']:$_GET['media'];
  if( $d ) 
  {
    $base = dirname($d);
    if($base=='.') $base = '';
    else  $base .= "/";
  }

  $str1 = 'fname = "LEX/" + fname + ".HTM";';
  $str2 = 'fname = "retrieve.php?page='.$base.'LEX/"+ fname +".HTM";'; 
  $content = str_replace($str1,$str2,$content);

  $str1 = 'loc = "ved_" + Sel.options[Sel.selectedIndex].value.substring(0,3) + ".htm";';
  $str2 = 'loc = "retrieve.php?page='.$base.'VED_" + Sel.options[Sel.selectedIndex].value.substring(0,3) + ".HTM";';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'loc = "nyb_" + Sel.options[Sel.selectedIndex].value.substring(0,3) + ".htm";';
  $str2 = 'loc = "retrieve.php?page='.$base.'NYB_" + Sel.options[Sel.selectedIndex].value.substring(0,3) + ".HTM";';
  $content = str_replace($str1,$str2,$content);

  $str1 = '" VNum[i]+".HTM\' target=\'main\'>"+VNum[i]+"</A><BR>n");';
  $str2 = '"+ VNum[i]+".HTM\' target=\'main\'>"+VNum[i]+"</A><BR>");';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'parent.list.document.write("<body bgcolor=\'#F0F0F0\'>n");';
  $str2 = 'parent.list.document.write("<body bgcolor=\'#F0F0F0\'>");';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'parent.list.document.write("<B>Anvisninger</B><P>n");';
  $str2 = 'parent.list.document.write("<B>Anvisninger</B><P>");';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'parent.list.document.write("</body>n");';
  $str2 = 'parent.list.document.write("</body>");';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'parent.list.document.write("<body bgcolor=\'#F0F0F0\'>n<center>");';
  $str2 = 'parent.list.document.write("<body bgcolor=\'#F0F0F0\'><center>");';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'parent.list.document.write("</center></body>n");';
  $str2 = 'parent.list.document.write("</center></body>");';
  $content = str_replace($str1,$str2,$content);

  $baseUp = dirname($base);
  $str1 = 'function Bagside(){ location.href="../ved/V'; 
  $str2 = 'function Bagside(){ location.href="retrieve.php?page='.$baseUp.'/VED/V'; 
  $content = str_replace($str1,$str2,$content);

  $str1 = 'function Dyrup() { location.href="../Dyrup/V';
  $str2 = 'function Dyrup() { location.href="retrieve.php?page='.$baseUp.'/DYRUP/V';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'function Sadolin() { location.href="../Sadolin/V';
  $str2 = 'function Sadolin() { location.href="retrieve.php?page='.$baseUp.'/SADOLIN/V';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'function Sigma() { location.href="../Sigma/V';
  $str2 = 'function Sigma() { location.href="retrieve.php?page='.$baseUp.'/SIGMA/V';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'function BJ() { location.href="../BJ/V';
  $str2 = 'function BJ() { location.href="retrieve.php?page='.$baseUp.'/BJ/V';
  $content = str_replace($str1,$str2,$content);

  $str1 = '<img src="grafik/';
  $str2 = '<img src="GRAFIK/';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'loc = "nyb/V" + val + ".htm";';
  $str2 = 'loc = "retrieve.php?page='.$base.'NYB/V" + val + ".HTM";';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'loc = "ved/V" + val + ".htm";';
  $str2 = 'loc = "retrieve.php?page='.$base.'VED/V" + val + ".HTM";';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'loc = "ved/V" + val + "c.htm";';
  $str2 = 'loc = "retrieve.php?page='.$base.'VED/V" + val + "C.HTM";';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'loc = "ved/V" + val + "b.htm";';
  $str2 = 'loc = "retrieve.php?page='.$base.'VED/V" + val + "B.HTM";';
  $content = str_replace($str1,$str2,$content);

  $str1 = 'href="../../retrieve.php?page=';
  $str2 = 'href="retrieve.php?page=';
  $content = str_replace($str1,$str2,$content);

  return $content;
}

function checkImg( $str )
{
  if( stristr( $str, "IMG" ) ) return "><$str";
  else return " ".$str;
}
function replaceIfRelative( $str,$prefix='page')
{
  global $ref, $url, $id;
  if( stristr( $str, 'http://www' ) ) return $str;

  $base = '';
  $d = ($_GET['page'])? $_GET['page']:$_GET['media'];
  if( $d ) 
  {
    $base = dirname($d);
    if($base=='.') $base = '';
    else  $base .= "/";
  }

  $str = "retrieve.php?$prefix=".$base."$str";
  return $str;
}

function replaceImg( $str,$base='' )
{
    if( $_GET['page'] ) 
    {
      $base = dirname($_GET['page']);
      if($base=='.') $base = '';
      else  $base .= "/";
    }

    //and a 'snooper in front of all images
    $pattern = "'<([^>]*)src=\"?\/?(\.?\/?)([^http]?.*)\.(...)([^>]*)>'i";
    $replace = "<\\1src=\"retrieve.php?media=".$base."\\3.\\4\\5>";
    $str = preg_replace( $pattern,$replace,$str);
    return $str;
}
function translateUrl( $src, $name, $pass )
{
    return str_replace( "http://", "http://". $name .":". $pass ."@", $src );
}
function replaceLinks( $str )
{
    $pattern1 = "'<a[^>]*href[^>]*>'i";
    $pattern2 = "'<\/a>'i";
    $str = preg_replace( $pattern1,'',$str);
    $str = preg_replace( $pattern2,'',$str);
    return $str;
}
function logging( $str )
{
  echo "testing the logg<br>";
  $filename = 'log.txt';
  $handle = fopen($filename, 'a');
  fwrite($handle, $str);
  fclose($handle);
  die();
}
?>

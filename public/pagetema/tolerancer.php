<?php
class Tolerancer extends View
{
  var $menu;
  function Tolerancer()
  {
    //$this->headLine = 'Tolerancer';
  }


  
  function Content()
  {
     $html = implode('', file('http://www.tolerancer.dk/index.php?id=217'));
     $html = '<xmp>'.$html.'</xmp>';
     $html = explode('<!--##MAIN##-->',$html);
     $main = $html[1];
     $main = preg_replace('/<h1>[^<]*<\/h1>/i','',$main);
     #image
     //$main = preg_replace("'src=\"([^\"]*)\"'i",'src="http://www.tolerancer.dk/\\1"',$main);
     $main = preg_replace("'src=\"([^\"]*)\"'ie","'src=\"'.isrelative('\\1').'\"'",$main);

     #links
     $main = preg_replace("'href=\"([^\"]*)\"'ie","'href=\"'.isrelative('\\1').'\"'",$main);
     return $main;
  }
}

function isrelative($str)
{
  if( stristr( $str, 'http://www' ) ) return $str;
  return 'http://www.tolerancer.dk/'.$str;
}

?>

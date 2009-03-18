<?php
$url = 'TIL_BYG_CONTENT/struktoerhaandbog/kapitel_03.pdf';
$name = 'kapitel_03.pdf#page=8';
$path = realpath('../'.$url);
$z = filesize($path);
#echo "<!--filesize: $z-->";
header("Content-type: application/pdf");
header("Content-Disposition:inline; filename=\"".trim(htmlentities($name))."\"");
header("Content-Description: ".trim(htmlentities($name)));
header("Content-Length: ".$z);

$fd=fopen($path,'rb');
  while(!feof($fd)) {
    print fread($fd, 4096);
  }
fclose($fd);
?>
